<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EventProgressExport implements WithMultipleSheets
{
    protected $event;
    protected $groupBy = 'month'; // 'day', 'week', 'month', 'quarter'

    public function __construct(Event $event, $groupBy = 'month')
    {
        $this->event = $event;
        $this->groupBy = $groupBy;
    }

    public function sheets(): array
    {
        return [
            new EventDataSheet($this->event),
            new TemporalChartSheet($this->event, $this->groupBy),
            new ProgressAnalysisSheet($this->event),
        ];
    }
}

/**
 * Hoja 1: Datos tabulares
 */
class EventDataSheet implements WithTitle, WithEvents
{
    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function title(): string
    {
        return 'Datos Detallados';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $this->generateDataSheet($event->sheet);
            },
        ];
    }

    protected function generateDataSheet($sheet)
    {
        $eventModel = $this->event;
        $subEvents = $eventModel->subEvents()->orderBy('event_date')->get();

        $sheet->setCellValue('A1', 'INFORME DE ACTIVIDAD OPERATIVA');
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1F4E79']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->setCellValue('A3', 'Código:');
        $sheet->setCellValue('B3', $eventModel->event_code);
        $sheet->setCellValue('A4', 'Actividad:');
        $sheet->setCellValue('B4', $eventModel->name ?? '');
        $sheet->setCellValue('A5', 'Descripción:');
        $sheet->setCellValue('B5', $eventModel->description);
        $sheet->setCellValue('A6', 'Meta total:');
        $sheet->setCellValue('B6', $eventModel->goal_people . ' ' . ($eventModel->unit_measure ?? 'personas'));

        $sheet->getStyle('A3:B6')->getFont()->setSize(12);
        $sheet->getStyle('A3:A6')->getFont()->setBold(true);

        // Encabezados
        $sheet->setCellValue('A8', 'Fecha');
        $sheet->setCellValue('B8', 'Título del Reporte');
        $sheet->setCellValue('C8', 'Asistentes');
        $sheet->setCellValue('D8', 'Acumulado');
        $sheet->setCellValue('E8', '% Avance');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];
        $sheet->getStyle('A8:E8')->applyFromArray($headerStyle);

        $row = 9;
        $acumulado = 0;
        $meta = $eventModel->goal_people;

        foreach ($subEvents as $reporte) {
            $acumulado += $reporte->attendees_count;
            $porcentaje = $meta > 0 ? round(($acumulado / $meta) * 100, 1) : 0;

            $sheet->setCellValue('A' . $row, $reporte->event_date->format('d/m/Y'));
            $sheet->setCellValue('B' . $row, $reporte->report_title);
            $sheet->setCellValue('C' . $row, $reporte->attendees_count);
            $sheet->setCellValue('D' . $row, $acumulado);
            $sheet->setCellValue('E' . $row, $porcentaje . '%');
            $row++;
        }

        $sheet->setCellValue('B' . $row, 'TOTAL ACUMULADO');
        $sheet->setCellValue('D' . $row, $acumulado);
        $sheet->setCellValue('E' . $row, ($meta > 0 ? round(($acumulado / $meta) * 100, 1) : 0) . '%');
        $sheet->getStyle('B' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9E1F2']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        $lastDataRow = $row - 1;
        $sheet->getStyle('A8:E' . $lastDataRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}

/**
 * Hoja 2: Gráfico de barras por período (mensual por defecto)
 */
class TemporalChartSheet implements WithTitle, WithEvents
{
    protected $event;
    protected $groupBy;

    public function __construct(Event $event, $groupBy = 'month')
    {
        $this->event = $event;
        $this->groupBy = $groupBy;
    }

    public function title(): string
    {
        return 'Evolución Temporal';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $this->generateChart($event->sheet);
            },
        ];
    }

    protected function generateChart($sheet)
    {
        $subEvents = $this->event->subEvents()->orderBy('event_date')->get();
        if ($subEvents->isEmpty()) {
            $sheet->setCellValue('A1', 'No hay reportes para graficar.');
            return;
        }

        // Agrupar por período
        $grouped = [];
        foreach ($subEvents as $r) {
            $key = $this->getPeriodKey($r->event_date);
            if (!isset($grouped[$key])) {
                $grouped[$key] = 0;
            }
            $grouped[$key] += $r->attendees_count;
        }

        // Escribir datos para el gráfico
        $sheet->setCellValue('A1', 'Período');
        $sheet->setCellValue('B1', 'Total Asistentes');
        $row = 2;
        $labels = [];
        $values = [];
        foreach ($grouped as $period => $total) {
            $sheet->setCellValue('A' . $row, $period);
            $sheet->setCellValue('B' . $row, $total);
            $labels[] = $period;
            $values[] = $total;
            $row++;
        }
        $lastRow = $row - 1;

        // Crear gráfico de barras
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', null, 1),
        ];
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$' . $lastRow, null, count($labels)),
        ];
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$2:$B$' . $lastRow, null, count($values)),
        ];

        $series = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            $xAxisTickValues,
            $dataSeriesValues
        );
        $series->setPlotDirection(DataSeries::DIRECTION_COL);

        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);
        $title = new Title('Asistentes por ' . $this->getPeriodName());

        $chart = new Chart(
            'temporalChart',
            $title,
            $legend,
            $plotArea,
            true,
            0,
            null,
            null
        );

        $chart->setTopLeftPosition('D2');
        $chart->setBottomRightPosition('M20');
        $sheet->addChart($chart);

        foreach (range('A', 'B') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    protected function getPeriodKey($date)
    {
        switch ($this->groupBy) {
            case 'day':
                return $date->format('Y-m-d');
            case 'week':
                return 'Sem ' . $date->weekOfYear . ' ' . $date->year;
            case 'quarter':
                $quarter = ceil($date->month / 3);
                return 'T' . $quarter . ' ' . $date->year;
            case 'month':
            default:
                return $date->format('M Y');
        }
    }

    protected function getPeriodName()
    {
        return [
            'day' => 'Día',
            'week' => 'Semana',
            'month' => 'Mes',
            'quarter' => 'Trimestre',
        ][$this->groupBy] ?? 'Mes';
    }
}

/**
 * Hoja 3: Análisis de Progreso (Tendencia acumulada + Pastel de distribución)
 */
class ProgressAnalysisSheet implements WithTitle, WithEvents
{
    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function title(): string
    {
        return 'Análisis de Progreso';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $this->generateCharts($event->sheet);
            },
        ];
    }

    protected function generateCharts($sheet)
    {
        $subEvents = $this->event->subEvents()->orderBy('event_date')->get();
        if ($subEvents->isEmpty()) {
            $sheet->setCellValue('A1', 'No hay reportes.');
            return;
        }

        // --- Datos para tendencia acumulada ---
        $sheet->setCellValue('A1', 'Fecha');
        $sheet->setCellValue('B1', 'Acumulado');
        $sheet->setCellValue('C1', 'Meta');

        $row = 2;
        $acumulado = 0;
        $meta = $this->event->goal_people;
        $labels = [];
        $acumValues = [];
        $metaValues = [];

        foreach ($subEvents as $r) {
            $acumulado += $r->attendees_count;
            $sheet->setCellValue('A' . $row, $r->event_date->format('d/m/Y'));
            $sheet->setCellValue('B' . $row, $acumulado);
            $sheet->setCellValue('C' . $row, $meta);
            $labels[] = $r->event_date->format('d/m');
            $acumValues[] = $acumulado;
            $metaValues[] = $meta;
            $row++;
        }
        $lastRow = $row - 1;

        // Gráfico de líneas: Acumulado vs Meta
        $dataSeriesLabelsLine = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$1', null, 1),
        ];
        $xAxisLine = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$' . $lastRow, null, count($labels)),
        ];
        $dataSeriesValuesLine = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$2:$B$' . $lastRow, null, count($acumValues)),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$2:$C$' . $lastRow, null, count($metaValues)),
        ];

        $seriesLine = new DataSeries(
            DataSeries::TYPE_LINECHART,
            DataSeries::GROUPING_STANDARD,
            range(0, 1),
            $dataSeriesLabelsLine,
            $xAxisLine,
            $dataSeriesValuesLine
        );

        $plotAreaLine = new PlotArea(null, [$seriesLine]);
        $legendLine = new Legend(Legend::POSITION_BOTTOM);
        $titleLine = new Title('Progreso Acumulado vs Meta');

        $chartLine = new Chart(
            'lineChart',
            $titleLine,
            $legendLine,
            $plotAreaLine,
            true,
            0,
            null,
            null
        );

        $chartLine->setTopLeftPosition('E2');
        $chartLine->setBottomRightPosition('M20');
        $sheet->addChart($chartLine);

        // --- Datos para gráfico de pastel (distribución por reporte) ---
        $pieStartRow = $lastRow + 3;
        $sheet->setCellValue('A' . $pieStartRow, 'Reporte');
        $sheet->setCellValue('B' . $pieStartRow, 'Asistentes');
        $pieRow = $pieStartRow + 1;
        foreach ($subEvents as $r) {
            $sheet->setCellValue('A' . $pieRow, $r->report_title);
            $sheet->setCellValue('B' . $pieRow, $r->attendees_count);
            $pieRow++;
        }
        $pieLastRow = $pieRow - 1;

        $dataSeriesLabelsPie = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$' . ($pieStartRow+1) . ':$A$' . $pieLastRow, null, $subEvents->count()),
        ];
        $xAxisPie = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$' . ($pieStartRow+1) . ':$A$' . $pieLastRow, null, $subEvents->count()),
        ];
        $dataSeriesValuesPie = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$' . ($pieStartRow+1) . ':$B$' . $pieLastRow, null, $subEvents->count()),
        ];

        $seriesPie = new DataSeries(
            DataSeries::TYPE_PIECHART,
            null,
            range(0, count($dataSeriesValuesPie) - 1),
            $dataSeriesLabelsPie,
            $xAxisPie,
            $dataSeriesValuesPie
        );

        $plotAreaPie = new PlotArea(null, [$seriesPie]);
        $legendPie = new Legend(Legend::POSITION_RIGHT);
        $titlePie = new Title('Distribución por Reporte');

        $chartPie = new Chart(
            'pieChart',
            $titlePie,
            $legendPie,
            $plotAreaPie,
            true,
            0,
            null,
            null
        );

        $chartPie->setTopLeftPosition('E22');
        $chartPie->setBottomRightPosition('M40');
        $sheet->addChart($chartPie);

        // Ajustar anchos
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}