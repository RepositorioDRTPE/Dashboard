<?php

namespace App\Exports;

use App\Models\SubEvent;
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
use Carbon\Carbon;

class GeneralPeriodReportExport implements WithMultipleSheets
{
    protected $startDate;
    protected $endDate;
    protected $period;

    public function __construct($startDate, $endDate, $period)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->period = $period;
    }

    public function sheets(): array
    {
        return [
            new GeneralPeriodDataSheet($this->startDate, $this->endDate, $this->period),
            new GeneralPeriodChartSheet($this->startDate, $this->endDate, $this->period),
        ];
    }
}

// Hoja de datos
class GeneralPeriodDataSheet implements WithTitle, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $period;

    public function __construct($startDate, $endDate, $period)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->period = $period;
    }

    public function title(): string
    {
        return 'Reporte del período';
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
        $start = $this->startDate;
        $end = $this->endDate;
        $periodNames = ['day' => 'Día', 'week' => 'Semana', 'month' => 'Mes', 'quarter' => 'Trimestre'];
        $periodName = $periodNames[$this->period] ?? 'Período';

        $subEvents = SubEvent::with('event')
                        ->whereBetween('event_date', [$start, $end])
                        ->orderBy('event_date')
                        ->get();

        $sheet->setCellValue('A1', 'REPORTE GENERAL DE ACTIVIDADES');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1F4E79']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->setCellValue('A3', "$periodName del: " . $start->format('d/m/Y') . " al " . $end->format('d/m/Y'));
        $sheet->mergeCells('A3:F3');

        // Encabezados tabla
        $sheet->setCellValue('A5', 'Código');
        $sheet->setCellValue('B5', 'Actividad');
        $sheet->setCellValue('C5', 'Reporte');
        $sheet->setCellValue('D5', 'Asistentes');
        $sheet->setCellValue('E5', 'Acumulado previo');
        $sheet->setCellValue('F5', 'Nuevo acumulado');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];
        $sheet->getStyle('A5:F5')->applyFromArray($headerStyle);

        $row = 6;
        $totalPeriodo = 0;
        $totalAcumuladoPrevio = 0;
        $totalNuevoAcumulado = 0;

        foreach ($subEvents as $reporte) {
            $acumuladoPrevio = SubEvent::where('event_id', $reporte->event_id)
                                ->where('event_date', '<', $start)
                                ->sum('attendees_count');
            
            $nuevoAcumulado = $acumuladoPrevio + $reporte->attendees_count;
            $totalPeriodo += $reporte->attendees_count;
            $totalAcumuladoPrevio += $acumuladoPrevio;
            $totalNuevoAcumulado += $nuevoAcumulado;

            $sheet->setCellValue('A' . $row, $reporte->event->event_code ?? 'N/A');
            $sheet->setCellValue('B' . $row, $reporte->event->name ?? '');
            $sheet->setCellValue('C' . $row, $reporte->report_title);
            $sheet->setCellValue('D' . $row, $reporte->attendees_count);
            $sheet->setCellValue('E' . $row, $acumuladoPrevio);
            $sheet->setCellValue('F' . $row, $nuevoAcumulado);
            $row++;
        }

        // Totales
        $sheet->setCellValue('C' . $row, 'TOTALES');
        $sheet->setCellValue('D' . $row, $totalPeriodo);
        $sheet->setCellValue('E' . $row, $totalAcumuladoPrevio);
        $sheet->setCellValue('F' . $row, $totalNuevoAcumulado);
        $sheet->getStyle('C' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9E1F2']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Ajustar anchos
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $lastRow = $row - 1;
        if ($lastRow >= 6) {
            $sheet->getStyle('A5:F' . $lastRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);
        }
    }
}

// Hoja de gráfico
class GeneralPeriodChartSheet implements WithTitle, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $period;

    public function __construct($startDate, $endDate, $period)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->period = $period;
    }

    public function title(): string
    {
        return 'Gráfico';
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
        $subEvents = SubEvent::with('event')
                        ->whereBetween('event_date', [$this->startDate, $this->endDate])
                        ->orderBy('event_date')
                        ->get();

        if ($subEvents->isEmpty()) {
            $sheet->setCellValue('A1', 'No hay datos para graficar.');
            return;
        }

        $sheet->setCellValue('A1', 'Actividad');
        $sheet->setCellValue('B1', 'Asistentes');

        $row = 2;
        $labels = [];
        $values = [];
        foreach ($subEvents as $r) {
            $label = $r->event->event_code ?? 'Evento ' . $r->id;
            $sheet->setCellValue('A' . $row, $label);
            $sheet->setCellValue('B' . $row, $r->attendees_count);
            $labels[] = $label;
            $values[] = $r->attendees_count;
            $row++;
        }
        $lastRow = $row - 1;

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
        $legend = new Legend(Legend::POSITION_RIGHT);
        $title = new Title('Avance por Actividad - Período');

        $chart = new Chart(
            'generalChart',
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
}