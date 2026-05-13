<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\SubEvent;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Chart\Axis;

// Importaciones de PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReportController extends Controller
{
    public function index()
    {
        $events = Event::with('category')->orderBy('event_code')->get();
        return view('reports.index', compact('events'));
    }

    public function generateGeneral(Request $request)
    {
        $request->validate([
            'period' => 'required|in:day,week,month,quarter,year',
            'date'   => 'required|date',
        ]);

        $period = $request->period;
        $date = Carbon::parse($request->date);
        $range = $this->getDateRange($period, $date);

        $hasReports = SubEvent::whereBetween('event_date', [$range['start'], $range['end']])->exists();

        if (!$hasReports) {
            $periodName = ['day' => 'el día', 'week' => 'la semana', 'month' => 'el mes', 'quarter' => 'el trimestre', 'year' => 'el año'][$period];
            return redirect()->route('reports.index')
                ->with('error', "No se encontraron reportes en $periodName seleccionado.");
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte');

        // --- 1. DISEÑO DE ENCABEZADOS ---
        $encabezados = ['Código PP', 'Cód. Evento', 'Actividad Operativa', 'Fecha', 'Detalle del Reporte', 'Asistentes', 'Acumulado Previo', 'Nuevo Acumulado'];
        $sheet->fromArray($encabezados, null, 'A1');

        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F4E78']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        foreach(['A'=>12, 'B'=>12, 'C'=>35, 'D'=>15, 'E'=>35, 'F'=>12, 'G'=>18, 'H'=>18] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // --- 2. EXTRACCIÓN DE DATOS ---
        $subEvents = SubEvent::with(['event.category'])
            ->whereBetween('event_date', [$range['start'], $range['end']])
            ->get()
            ->sortBy([
                ['event.category.pp_code', 'asc'],
                ['event.event_code', 'asc'],
                ['event_date', 'asc'],
            ]);

        $rowNum = 2;
        $runningAccumulators = [];
        $totalPeriodo = 0;
        $currentEventCode = null;
        
        // Arrays para gráficos
        $chartDataBar = [];
        $chartDataPie = [];
        $chartDataLine = [];

        foreach ($subEvents as $reporte) {
            $eventId = $reporte->event_id;
            $categoriaNombre = $reporte->event->category->name ?? 'Sin Categoría';
            $codigoEvento = $reporte->event->event_code ?? 'N/A';
            $ppCode = $reporte->event->category->pp_code ?? 'N/A';

            if (!isset($runningAccumulators[$eventId])) {
                $runningAccumulators[$eventId] = SubEvent::where('event_id', $eventId)
                    ->where('event_date', '<', $range['start'])
                    ->sum('attendees_count');
            }

            $acumuladoPrevio = $runningAccumulators[$eventId];
            $nuevoAcumulado = $acumuladoPrevio + $reporte->attendees_count;
            $runningAccumulators[$eventId] = $nuevoAcumulado;

            if ($codigoEvento !== $currentEventCode) {
                $sheet->setCellValue('A' . $rowNum, $ppCode);
                $sheet->setCellValue('B' . $rowNum, $codigoEvento);
                $sheet->setCellValue('C' . $rowNum, $reporte->event->description ?? '');
            }
            $currentEventCode = $codigoEvento;

            $fechaStr = Carbon::parse($reporte->event_date)->format('Y-m-d');
            $sheet->setCellValue('D' . $rowNum, Carbon::parse($fechaStr)->format('d/m/Y'));
            $sheet->setCellValue('E' . $rowNum, $reporte->report_title);
            $sheet->setCellValue('F' . $rowNum, $reporte->attendees_count);
            $sheet->setCellValue('G' . $rowNum, $acumuladoPrevio);
            $sheet->setCellValue('H' . $rowNum, $nuevoAcumulado);

            $totalPeriodo += $reporte->attendees_count;
            
            // Llenar datos de gráficos
            if (!isset($chartDataBar[$codigoEvento])) $chartDataBar[$codigoEvento] = 0;
            $chartDataBar[$codigoEvento] += $reporte->attendees_count;

            if (!isset($chartDataPie[$categoriaNombre])) $chartDataPie[$categoriaNombre] = 0;
            $chartDataPie[$categoriaNombre] += $reporte->attendees_count;

            if (!isset($chartDataLine[$fechaStr])) $chartDataLine[$fechaStr] = 0;
            $chartDataLine[$fechaStr] += $reporte->attendees_count;

            $rowNum++;
        }

        // --- 3. FILA DE TOTALES GLOBALES ---
        $totalAcumuladoPrevioGlobal = 0;
        $totalNuevoAcumuladoGlobal = 0;
        foreach ($runningAccumulators as $eventId => $ultimoAcumulado) {
            $totalAcumuladoPrevioGlobal += SubEvent::where('event_id', $eventId)->where('event_date', '<', $range['start'])->sum('attendees_count');
            $totalNuevoAcumuladoGlobal += $ultimoAcumulado;
        }

        $sheet->mergeCells('A' . $rowNum . ':E' . $rowNum);
        $sheet->setCellValue('A' . $rowNum, 'TOTALES GLOBALES DEL PERIODO');
        $sheet->setCellValue('F' . $rowNum, $totalPeriodo);
        $sheet->setCellValue('G' . $rowNum, $totalAcumuladoPrevioGlobal);
        $sheet->setCellValue('H' . $rowNum, $totalNuevoAcumuladoGlobal);

        $sheet->getStyle('A'.$rowNum.':H'.$rowNum)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getStyle('F'.$rowNum.':H'.$rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // --- 4. PREPARACIÓN DE DATOS PARA GRÁFICOS ---
        $sheetTitle = 'Reporte';

        // A. Datos para Barras (K, L)
        $rowK = 2;
        $sheet->setCellValueExplicit('K1', 'Código', DataType::TYPE_STRING); 
        $sheet->setCellValueExplicit('L1', 'Asistentes', DataType::TYPE_STRING);
        foreach ($chartDataBar as $codigo => $asistentes) {
            $sheet->setCellValueExplicit('K' . $rowK, (string)$codigo, DataType::TYPE_STRING); 
            $sheet->setCellValueExplicit('L' . $rowK, (int)$asistentes, DataType::TYPE_NUMERIC); 
            $rowK++;
        }
        $barRows = count($chartDataBar);

        // B. Datos para Torta 1 - Categorías (M, N)
        $rowM = 2;
        $sheet->setCellValueExplicit('M1', 'Categoría', DataType::TYPE_STRING); 
        $sheet->setCellValueExplicit('N1', 'Distribución', DataType::TYPE_STRING);
        foreach ($chartDataPie as $categoria => $asistentes) {
            $sheet->setCellValueExplicit('M' . $rowM, (string)$categoria, DataType::TYPE_STRING); 
            $sheet->setCellValueExplicit('N' . $rowM, (int)$asistentes, DataType::TYPE_NUMERIC);
            $rowM++;
        }
        $pieRows = count($chartDataPie);

        // C. Datos para Torta 2 - Meta vs Faltante Global (O, P)
        $metaFisicaTotal = (int)Event::sum('goal_people');
        $avanceHistorico = (int)SubEvent::where('event_date', '<=', $range['end'])->sum('attendees_count');
        $metaFaltante = max(0, $metaFisicaTotal - $avanceHistorico);
        
        $sheet->setCellValueExplicit('O1', 'Estado Global', DataType::TYPE_STRING); 
        $sheet->setCellValueExplicit('P1', 'Personas', DataType::TYPE_STRING);
        
        $sheet->setCellValueExplicit('O2', 'Avance Realizado', DataType::TYPE_STRING); 
        $sheet->setCellValueExplicit('P2', (int)$avanceHistorico, DataType::TYPE_NUMERIC);
        
        $sheet->setCellValueExplicit('O3', 'Meta Faltante', DataType::TYPE_STRING); 
        $sheet->setCellValueExplicit('P3', (int)$metaFaltante, DataType::TYPE_NUMERIC);
        
        // D. Datos para Líneas - Evolución (Q, R)
        $rowQ = 2;
        $sheet->setCellValueExplicit('Q1', 'Fecha', DataType::TYPE_STRING); 
        $sheet->setCellValueExplicit('R1', 'Evolución Diaria', DataType::TYPE_STRING);
        ksort($chartDataLine); 
        foreach ($chartDataLine as $fecha => $asistentes) {
            $fechaFormat = Carbon::parse($fecha)->format('d/m');
            $sheet->setCellValueExplicit('Q' . $rowQ, (string)$fechaFormat, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('R' . $rowQ, (int)$asistentes, DataType::TYPE_NUMERIC);
            $rowQ++;
        }
        $lineRows = count($chartDataLine);

        // --- 5. RENDERIZADO DE GRÁFICOS ---
        if ($barRows > 0) {
            $barLabels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetTitle . '!$L$1', null, 1)];
            $barCategories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetTitle . '!$K$2:$K$' . ($barRows + 1), null, $barRows)];
            $barValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $sheetTitle . '!$L$2:$L$' . ($barRows + 1), null, $barRows)];
            
            $barSeries = new DataSeries(DataSeries::TYPE_BARCHART, DataSeries::GROUPING_CLUSTERED, range(0, count($barValues) - 1), $barLabels, $barCategories, $barValues);
            $barSeries->setPlotDirection(DataSeries::DIRECTION_COL);
            
            $xAxisBar = new Axis();
            $yAxisBar = new Axis();
            $barChart = new Chart('barChart', new Title('Asistencias por Actividad'), new Legend(Legend::POSITION_BOTTOM, null, false), new PlotArea(null, [$barSeries]), true, 'gap', null, null, $xAxisBar, $yAxisBar);
            $barChart->setTopLeftPosition('A' . ($rowNum + 3));
            $barChart->setBottomRightPosition('D' . ($rowNum + 18));
            $sheet->addChart($barChart);
        }

        if ($pieRows > 0) {
            $pieLabels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetTitle . '!$N$1', null, 1)];
            $pieCategories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetTitle . '!$M$2:$M$' . ($pieRows + 1), null, $pieRows)];
            $pieValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $sheetTitle . '!$N$2:$N$' . ($pieRows + 1), null, $pieRows)];
            
            $pieSeries = new DataSeries(DataSeries::TYPE_PIECHART, null, range(0, count($pieValues) - 1), $pieLabels, $pieCategories, $pieValues);
            
            $pieChart = new Chart('pieChart1', new Title('Distribucion por Categoria'), new Legend(Legend::POSITION_BOTTOM, null, false), new PlotArea(null, [$pieSeries]), true, 'gap');
            $pieChart->setTopLeftPosition('E' . ($rowNum + 3));
            $pieChart->setBottomRightPosition('H' . ($rowNum + 18));
            $sheet->addChart($pieChart);
        }

        // Torta Meta Global
        $pie2Labels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetTitle . '!$P$1', null, 1)];
        $pie2Categories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetTitle . '!$O$2:$O$3', null, 2)];
        $pie2Values = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $sheetTitle . '!$P$2:$P$3', null, 2)];
        
        $pie2Series = new DataSeries(DataSeries::TYPE_PIECHART, null, range(0, count($pie2Values) - 1), $pie2Labels, $pie2Categories, $pie2Values);
        
        $pie2Chart = new Chart('pieChart2', new Title('Meta Fisica: Avance vs Faltante'), new Legend(Legend::POSITION_BOTTOM, null, false), new PlotArea(null, [$pie2Series]), true, 'gap');
        $pie2Chart->setTopLeftPosition('I' . ($rowNum + 3));
        $pie2Chart->setBottomRightPosition('L' . ($rowNum + 18));
        $sheet->addChart($pie2Chart);

        if ($lineRows > 1) {
            $lineLabels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetTitle . '!$R$1', null, 1)];
            $lineCategories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetTitle . '!$Q$2:$Q$' . ($lineRows + 1), null, $lineRows)];
            $lineValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $sheetTitle . '!$R$2:$R$' . ($lineRows + 1), null, $lineRows)];
            
            $lineSeries = new DataSeries(DataSeries::TYPE_LINECHART, DataSeries::GROUPING_STANDARD, range(0, count($lineValues) - 1), $lineLabels, $lineCategories, $lineValues);
            
            $xAxisLine = new Axis();
            $yAxisLine = new Axis();
            $lineChart = new Chart('lineChart', new Title('Linea de Tiempo de Asistencias'), new Legend(Legend::POSITION_TOP, null, false), new PlotArea(null, [$lineSeries]), true, 'gap', null, null, $xAxisLine, $yAxisLine);
            $lineChart->setTopLeftPosition('A' . ($rowNum + 20));
            $lineChart->setBottomRightPosition('L' . ($rowNum + 35));
            $sheet->addChart($lineChart);
        }

        // --- 6. DESCARGA ---
        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true); 

        $fileName = 'reporte-gerencial-metas-' . $range['start']->format('Y-m-d') . '_' . $range['end']->format('Y-m-d') . '.xlsx';

        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . urlencode($fileName) . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function generateSpecific(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'period'   => 'required|in:day,week,month,quarter,year',
            'date'     => 'required|date',
        ]);

        $event = Event::findOrFail($request->event_id);
        $period = $request->period;
        $date = Carbon::parse($request->date);
        $range = $this->getDateRange($period, $date);

        $reportes = SubEvent::where('event_id', $event->id)
                        ->whereBetween('event_date', [$range['start'], $range['end']])
                        ->orderBy('event_date')
                        ->get();

        if ($reportes->isEmpty()) {
            $periodName = ['day' => 'el día', 'week' => 'la semana', 'month' => 'el mes', 'quarter' => 'el trimestre', 'year' => 'el año'][$period];
            return redirect()->route('reports.index')
                ->with('error', "La actividad \"{$event->event_code}\" no tiene reportes en $periodName seleccionado.");
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte Actividad');

        // --- ENCABEZADOS ---
        $encabezados = ['Fecha', 'Título', 'Asistentes', 'Acumulado Previo', 'Nuevo Acumulado'];
        $sheet->fromArray($encabezados, null, 'A1');

        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F4E78']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        foreach(['A'=>12, 'B'=>35, 'C'=>12, 'D'=>18, 'E'=>18] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // --- DATOS ---
        $rowNum = 2;
        $acumuladoPrevioInicial = SubEvent::where('event_id', $event->id)
                                    ->where('event_date', '<', $range['start'])
                                    ->sum('attendees_count');
        $runningAcum = $acumuladoPrevioInicial;
        $totalPeriodo = 0;
        
        $chartLineData = []; // fecha => asistentes

        foreach ($reportes as $reporte) {
            $acumuladoPrevio = $runningAcum;
            $nuevoAcumulado = $acumuladoPrevio + $reporte->attendees_count;
            $runningAcum = $nuevoAcumulado;

            $fechaStr = Carbon::parse($reporte->event_date)->format('Y-m-d');
            $fechaFormat = Carbon::parse($fechaStr)->format('d/m/Y');

            $sheet->setCellValue('A' . $rowNum, $fechaFormat);
            $sheet->setCellValue('B' . $rowNum, $reporte->report_title);
            $sheet->setCellValue('C' . $rowNum, $reporte->attendees_count);
            $sheet->setCellValue('D' . $rowNum, $acumuladoPrevio);
            $sheet->setCellValue('E' . $rowNum, $nuevoAcumulado);

            $totalPeriodo += $reporte->attendees_count;
            if (!isset($chartLineData[$fechaStr])) $chartLineData[$fechaStr] = 0;
            $chartLineData[$fechaStr] += $reporte->attendees_count;

            $rowNum++;
        }

        // Fila de totales de la actividad
        $sheet->mergeCells('A' . $rowNum . ':B' . $rowNum);
        $sheet->setCellValue('A' . $rowNum, 'TOTAL DE LA ACTIVIDAD');
        $sheet->setCellValue('C' . $rowNum, $totalPeriodo);
        $sheet->setCellValue('D' . $rowNum, $acumuladoPrevioInicial);
        $sheet->setCellValue('E' . $rowNum, $runningAcum);

        $sheet->getStyle('A'.$rowNum.':E'.$rowNum)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // --- GRÁFICOS ---
        $sheetTitle = "'Reporte Actividad'";

        // Línea de evolución diaria
        if (count($chartLineData) > 1) {
            $rowL = 2;
            $sheet->setCellValueExplicit('G1', 'Fecha', DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('H1', 'Asistentes', DataType::TYPE_STRING);
            ksort($chartLineData);
            foreach ($chartLineData as $fecha => $valor) {
                $fechaFormatShort = Carbon::parse($fecha)->format('d/m');
                $sheet->setCellValueExplicit('G' . $rowL, (string)$fechaFormatShort, DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('H' . $rowL, (int)$valor, DataType::TYPE_NUMERIC);
                $rowL++;
            }
            $lineRows = count($chartLineData);

            $lineLabels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetTitle . '!$H$1', null, 1)];
            $lineCategories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetTitle . '!$G$2:$G$' . ($lineRows + 1), null, $lineRows)];
            $lineValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $sheetTitle . '!$H$2:$H$' . ($lineRows + 1), null, $lineRows)];

            $lineSeries = new DataSeries(DataSeries::TYPE_LINECHART, DataSeries::GROUPING_STANDARD, range(0, count($lineValues) - 1), $lineLabels, $lineCategories, $lineValues);
            $xAxisLine = new Axis();
            $yAxisLine = new Axis();
            $lineChart = new Chart('lineSpecific', new Title('Evolución Diaria de Asistentes'), new Legend(Legend::POSITION_TOP, null, false), new PlotArea(null, [$lineSeries]), true, 'gap', null, null, $xAxisLine, $yAxisLine);
            $lineChart->setTopLeftPosition('A' . ($rowNum + 3));
            $lineChart->setBottomRightPosition('E' . ($rowNum + 18));
            $sheet->addChart($lineChart);
        }

        // Torta de avance vs meta de la actividad
        $metaActividad = (int)$event->goal_people;
        $avanceHistoricoActividad = (int)SubEvent::where('event_id', $event->id)
                                        ->where('event_date', '<=', $range['end'])
                                        ->sum('attendees_count');
        $faltanteActividad = max(0, $metaActividad - $avanceHistoricoActividad);

        $sheet->setCellValueExplicit('J1', 'Estado', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('K1', 'Personas', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('J2', 'Avance Realizado', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('K2', (int)$avanceHistoricoActividad, DataType::TYPE_NUMERIC);
        $sheet->setCellValueExplicit('J3', 'Meta Faltante', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('K3', (int)$faltanteActividad, DataType::TYPE_NUMERIC);

        $pieLabels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetTitle . '!$K$1', null, 1)];
        $pieCategories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetTitle . '!$J$2:$J$3', null, 2)];
        $pieValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $sheetTitle . '!$K$2:$K$3', null, 2)];

        $pieSeries = new DataSeries(DataSeries::TYPE_PIECHART, null, range(0, 0), $pieLabels, $pieCategories, $pieValues);
        $pieChart = new Chart('pieSpecific', new Title('Meta: Avance vs Faltante'), new Legend(Legend::POSITION_BOTTOM, null, false), new PlotArea(null, [$pieSeries]), true, 'gap');
        $pieChart->setTopLeftPosition('F' . ($rowNum + 3));
        $pieChart->setBottomRightPosition('J' . ($rowNum + 18));
        $sheet->addChart($pieChart);

        // --- DESCARGA ---
        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        $fileName = 'reporte-actividad-' . $event->event_code . '-' . $range['start']->format('Y-m-d') . '_' . $range['end']->format('Y-m-d') . '.xlsx';

        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . urlencode($fileName) . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    private function getDateRange(string $period, Carbon $date): array
    {
        switch ($period) {
            case 'day':
                return ['start' => $date->copy()->startOfDay(), 'end' => $date->copy()->endOfDay()];
            case 'week':
                return ['start' => $date->copy()->startOfWeek(), 'end' => $date->copy()->endOfWeek()];
            case 'month':
                return ['start' => $date->copy()->startOfMonth(), 'end' => $date->copy()->endOfMonth()];
            case 'quarter':
                $quarter = ceil($date->month / 3);
                $startMonth = ($quarter - 1) * 3 + 1;
                return [
                    'start' => Carbon::create($date->year, $startMonth, 1)->startOfDay(),
                    'end'   => Carbon::create($date->year, $startMonth + 2, 1)->endOfMonth()->endOfDay(),
                ];
            case 'year':
                return [
                    'start' => Carbon::create($date->year, 1, 1)->startOfDay(),
                    'end'   => Carbon::create($date->year, 12, 31)->endOfDay(),
                ];
            default:
                return ['start' => $date->copy()->startOfDay(), 'end' => $date->copy()->endOfDay()];
        }
    }
}