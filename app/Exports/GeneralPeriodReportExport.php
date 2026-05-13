<?php

namespace App\Exports;

use App\Models\SubEvent;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\BeforeWriting;
use Carbon\Carbon;

class GeneralPeriodReportExport implements FromView, WithEvents, WithTitle, WithColumnWidths
{
    protected $startDate;
    protected $endDate;
    protected $period;
    protected $dataRowsCount = 0; 
    protected $chartDataBar = []; // Datos para barras (Por Actividad Operativa)
    protected $chartDataPie = []; // Datos para torta (Por Categoría/PP)

    public function __construct($startDate, $endDate, $period=null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->period = $period;
    }

    public function title(): string
    {
        return 'Reporte Gerencial';
    }

    public function columnWidths(): array
    {
        // Ajustamos los anchos para las nuevas columnas
        return [
            'A' => 12, // PP
            'B' => 12, // Código Evento
            'C' => 35, // Actividad Desc
            'D' => 15, // Fecha
            'E' => 30, // Reporte
            'F' => 12, // Asistentes
            'G' => 18, // Acumulado Previo
            'H' => 18, // Nuevo Acumulado
        ];
    }

    public function view(): View
    {
        // Traemos los datos y los ORDENAMOS por Categoría -> Evento -> Fecha
        $subEvents = SubEvent::with(['event.category'])
            ->whereBetween('event_date', [$this->startDate, $this->endDate])
            ->get()
            ->sortBy([
                ['event.category.pp_code', 'asc'],
                ['event.event_code', 'asc'],
                ['event_date', 'asc'],
            ]);

        $data = [];
        $runningAccumulators = [];
        $totalPeriodo = 0;
        
        // Variables para la suma global final
        $totalAcumuladoPrevioGlobal = 0;
        $totalNuevoAcumuladoGlobal = 0;

        foreach ($subEvents as $reporte) {
            $eventId = $reporte->event_id;
            $categoriaNombre = $reporte->event->category->name ?? 'Sin Categoría';
            $codigoEvento = $reporte->event->event_code ?? 'N/A';

            // Lógica del Acumulado Histórico/Cronológico
            if (!isset($runningAccumulators[$eventId])) {
                // Si es la primera vez que vemos este evento en el bucle, buscamos su historia ANTES del startDate
                $runningAccumulators[$eventId] = SubEvent::where('event_id', $eventId)
                    ->where('event_date', '<', $this->startDate)
                    ->sum('attendees_count');
            }

            $acumuladoPrevio = $runningAccumulators[$eventId];
            $nuevoAcumulado = $acumuladoPrevio + $reporte->attendees_count;
            
            // Actualizamos el acumulador para el siguiente ciclo del mismo evento
            $runningAccumulators[$eventId] = $nuevoAcumulado;

            $data[] = [
                'pp_code' => $reporte->event->category->pp_code ?? 'N/A',
                'event_code' => $codigoEvento,
                'actividad' => $reporte->event->description ?? '',
                'fecha' => Carbon::parse($reporte->event_date)->format('d/m/Y'),
                'reporte' => $reporte->report_title,
                'asistentes' => $reporte->attendees_count,
                'acumulado_previo' => $acumuladoPrevio,
                'nuevo_acumulado' => $nuevoAcumulado,
            ];

            // Sumas globales
            $totalPeriodo += $reporte->attendees_count;

            // Recopilar datos para gráficos
            if (!isset($this->chartDataBar[$codigoEvento])) $this->chartDataBar[$codigoEvento] = 0;
            $this->chartDataBar[$codigoEvento] += $reporte->attendees_count;

            if (!isset($this->chartDataPie[$categoriaNombre])) $this->chartDataPie[$categoriaNombre] = 0;
            $this->chartDataPie[$categoriaNombre] += $reporte->attendees_count;
        }

        // Para los totales finales de la tabla sumamos el último "nuevo acumulado" de cada evento procesado
        foreach ($runningAccumulators as $eventId => $ultimoAcumulado) {
            $historiaPreviaTotal = SubEvent::where('event_id', $eventId)->where('event_date', '<', $this->startDate)->sum('attendees_count');
            $totalAcumuladoPrevioGlobal += $historiaPreviaTotal;
            $totalNuevoAcumuladoGlobal += $ultimoAcumulado;
        }

        $this->dataRowsCount = count($data);

        return view('exports.general_period_report', [
            'data' => $data,
            'totalPeriodo' => $totalPeriodo,
            'totalAcumuladoPrevio' => $totalAcumuladoPrevioGlobal,
            'totalNuevoAcumulado' => $totalNuevoAcumuladoGlobal,
        ]);
    }

public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // 1. Estilos de Encabezado
                $sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F4E78']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);

                // 2. Estilo de Fila Totales
                $lastRow = $this->dataRowsCount + 1;
                $totalRow = $lastRow + 1;
                $sheet->getStyle('A'.$totalRow.':H'.$totalRow)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);

                // 3. Generar Gráficos
                if (!empty($this->chartDataBar)) {
                    $this->buildCharts($sheet, $totalRow);
                }
            },
        ];
    }


    protected function buildCharts(Worksheet $sheet, int $totalRow)
    {
        // Obtenemos el nombre exacto de la hoja para evitar referencias rotas
        $sheetTitle = "'" . str_replace("'", "''", $sheet->getTitle()) . "'";

        // ---- 1. GRÁFICO DE BARRAS (Columnas K y L) ----
        $rowK = 2;
        $sheet->setCellValue('K1', 'Código'); // Encabezado oculto
        $sheet->setCellValue('L1', 'Asistentes'); // Encabezado oculto
        
        foreach ($this->chartDataBar as $codigo => $asistentes) {
            $sheet->setCellValue('K' . $rowK, $codigo);
            $sheet->setCellValue('L' . $rowK, $asistentes);
            $rowK++;
        }
        $barRows = count($this->chartDataBar);

        if ($barRows > 0) {
            $barLabels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetTitle . '!$L$1', null, 1)];
            $barCategories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetTitle . '!$K$2:$K$' . ($barRows + 1), null, $barRows)];
            $barValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $sheetTitle . '!$L$2:$L$' . ($barRows + 1), null, $barRows)];

            $barSeries = new DataSeries(DataSeries::TYPE_BARCHART, DataSeries::GROUPING_CLUSTERED, range(0, count($barValues) - 1), $barLabels, $barCategories, $barValues);
            $barSeries->setPlotDirection(DataSeries::DIRECTION_COL);
            $barChart = new Chart('barChart', new Title('Asistentes por Actividad'), new Legend(Legend::POSITION_RIGHT, null, false), new PlotArea(null, [$barSeries]), true, 0, null, null);
            
            // Posición del gráfico de Barras
            $barChart->setTopLeftPosition('A' . ($totalRow + 3));
            $barChart->setBottomRightPosition('D' . ($totalRow + 18));
            $sheet->addChart($barChart);
        }

        // ---- 2. GRÁFICO DE TORTA (Columnas M y N) ----
        $rowM = 2;
        $sheet->setCellValue('M1', 'Categoría'); // Encabezado oculto
        $sheet->setCellValue('N1', 'Distribución'); // Encabezado oculto
        
        foreach ($this->chartDataPie as $categoria => $asistentes) {
            $sheet->setCellValue('M' . $rowM, $categoria);
            $sheet->setCellValue('N' . $rowM, $asistentes);
            $rowM++;
        }
        $pieRows = count($this->chartDataPie);

        if ($pieRows > 0) {
            $pieLabels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetTitle . '!$N$1', null, 1)];
            $pieCategories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetTitle . '!$M$2:$M$' . ($pieRows + 1), null, $pieRows)];
            $pieValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $sheetTitle . '!$N$2:$N$' . ($pieRows + 1), null, $pieRows)];

            $pieSeries = new DataSeries(DataSeries::TYPE_PIECHART, null, range(0, count($pieValues) - 1), $pieLabels, $pieCategories, $pieValues);
            $pieChart = new Chart('pieChart', new Title('Distribución por Categoría'), new Legend(Legend::POSITION_BOTTOM, null, false), new PlotArea(null, [$pieSeries]), true, 0, null, null);
            
            // Posición del gráfico de Torta
            $pieChart->setTopLeftPosition('E' . ($totalRow + 3));
            $pieChart->setBottomRightPosition('H' . ($totalRow + 18));
            $sheet->addChart($pieChart);
        }
    }


}

