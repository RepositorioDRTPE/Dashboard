<?php

namespace App\Exports;

use App\Models\Event;
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

class SpecificPeriodReportExport implements WithMultipleSheets
{
    protected $event;
    protected $startDate;
    protected $endDate;
    protected $period;

    public function __construct(Event $event, $startDate, $endDate, $period)
    {
        $this->event = $event;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->period = $period;
    }

    public function sheets(): array
    {
        return [
            new SpecificPeriodDataSheet($this->event, $this->startDate, $this->endDate, $this->period),
            new SpecificPeriodChartSheet($this->event, $this->startDate, $this->endDate),
        ];
    }
}

// Hoja de datos
class SpecificPeriodDataSheet implements WithTitle, WithEvents
{
    protected $event;
    protected $startDate;
    protected $endDate;
    protected $period;

    public function __construct(Event $event, $startDate, $endDate, $period)
    {
        $this->event = $event;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->period = $period;
    }

    public function title(): string
    {
        return 'Detalle del período';
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
        $event = $this->event;
        $start = $this->startDate;
        $end = $this->endDate;
        $periodNames = ['day' => 'Día', 'week' => 'Semana', 'month' => 'Mes', 'quarter' => 'Trimestre'];
        $periodName = $periodNames[$this->period] ?? 'Período';

        $avancePrevio = SubEvent::where('event_id', $event->id)
                        ->where('event_date', '<', $start)
                        ->sum('attendees_count');

        $reportesPeriodo = SubEvent::where('event_id', $event->id)
                            ->whereBetween('event_date', [$start, $end])
                            ->get();

        $avancePeriodo = $reportesPeriodo->sum('attendees_count');
        $avancePosterior = $avancePrevio + $avancePeriodo;
        $meta = $event->goal_people;

        $sheet->setCellValue('A1', 'REPORTE DE ACTIVIDAD: ' . $event->event_code);
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1F4E79']],
        ]);

        $sheet->setCellValue('A3', 'Actividad:');
        $sheet->setCellValue('B3', $event->name ?? $event->description);
        $sheet->setCellValue('A4', 'Meta total:');
        $sheet->setCellValue('B4', $meta . ' ' . ($event->unit_measure ?? 'personas'));
        $sheet->setCellValue('A5', "$periodName analizado:");
        $sheet->setCellValue('B5', $start->format('d/m/Y') . ' al ' . $end->format('d/m/Y'));

        $sheet->setCellValue('A7', 'RESUMEN');
        $sheet->mergeCells('A7:D7');
        $sheet->getStyle('A7')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A8', 'Avance acumulado antes del período:');
        $sheet->setCellValue('B8', $avancePrevio);
        $sheet->setCellValue('A9', 'Avance durante el período:');
        $sheet->setCellValue('B9', $avancePeriodo);
        $sheet->setCellValue('A10', 'Avance acumulado después del período:');
        $sheet->setCellValue('B10', $avancePosterior);
        $sheet->setCellValue('A11', 'Porcentaje de avance vs meta:');
        $porcentaje = $meta > 0 ? round(($avancePosterior / $meta) * 100, 1) : 0;
        $sheet->setCellValue('B11', $porcentaje . '%');

        $sheet->setCellValue('A13', 'Reportes realizados en el período');
        $sheet->mergeCells('A13:D13');
        $sheet->getStyle('A13')->getFont()->setBold(true);

        $sheet->setCellValue('A14', 'Fecha');
        $sheet->setCellValue('B14', 'Título');
        $sheet->setCellValue('C14', 'Asistentes');
        $sheet->setCellValue('D14', 'Comentario');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A14:D14')->applyFromArray($headerStyle);

        $row = 15;
        foreach ($reportesPeriodo as $r) {
            $sheet->setCellValue('A' . $row, $r->event_date->format('d/m/Y'));
            $sheet->setCellValue('B' . $row, $r->report_title);
            $sheet->setCellValue('C' . $row, $r->attendees_count);
            $sheet->setCellValue('D' . $row, $r->comment ?? '');
            $row++;
        }

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $lastRow = $row - 1;
        if ($lastRow >= 15) {
            $sheet->getStyle('A14:D' . $lastRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);
        }
    }
}

// Hoja de gráfico (torta)
class SpecificPeriodChartSheet implements WithTitle, WithEvents
{
    protected $event;
    protected $startDate;
    protected $endDate;

    public function __construct(Event $event, $startDate, $endDate)
    {
        $this->event = $event;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function title(): string
    {
        return 'Gráfico de Avance';
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
        $event = $this->event;
        $start = $this->startDate;

        $avancePrevio = SubEvent::where('event_id', $event->id)
                        ->where('event_date', '<', $start)
                        ->sum('attendees_count');

        $avancePeriodo = SubEvent::where('event_id', $event->id)
                            ->whereBetween('event_date', [$this->startDate, $this->endDate])
                            ->sum('attendees_count');

        $avanceTotal = $avancePrevio + $avancePeriodo;
        $meta = $event->goal_people;
        $restante = max(0, $meta - $avanceTotal);

        $sheet->setCellValue('A1', 'Concepto');
        $sheet->setCellValue('B1', 'Cantidad');

        $sheet->setCellValue('A2', 'Avance previo');
        $sheet->setCellValue('B2', $avancePrevio);
        $sheet->setCellValue('A3', 'Avance del período');
        $sheet->setCellValue('B3', $avancePeriodo);
        $sheet->setCellValue('A4', 'Restante por alcanzar');
        $sheet->setCellValue('B4', $restante);

        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$4', null, 3),
        ];
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$4', null, 3),
        ];
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$2:$B$4', null, 3),
        ];

        $series = new DataSeries(
            DataSeries::TYPE_PIECHART,
            null,
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            $xAxisTickValues,
            $dataSeriesValues
        );

        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_RIGHT);
        $title = new Title('Distribución del Avance vs Meta - ' . $event->event_code);

        $chart = new Chart(
            'pieChart',
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