<?php

namespace App\Exports;

use App\Models\Event;
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
use Carbon\Carbon;

class SpecificPeriodReportExport implements FromView, WithEvents, WithTitle, WithColumnWidths
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
        return 'Reporte ' . $this->event->event_code;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 40,
            'C' => 15,
            'D' => 50,
        ];
    }

    public function view(): View
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
        $porcentaje = $meta > 0 ? round(($avancePosterior / $meta) * 100, 1) : 0;

        return view('exports.specific_period_report', [
            'event' => $event,
            'periodName' => $periodName,
            'startDate' => $start,
            'endDate' => $end,
            'avancePrevio' => $avancePrevio,
            'avancePeriodo' => $avancePeriodo,
            'avancePosterior' => $avancePosterior,
            'meta' => $meta,
            'porcentaje' => $porcentaje,
            'reportesPeriodo' => $reportesPeriodo,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Estilos de encabezados
                $sheet->getStyle('A14:D14')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);
                
                $reportesCount = $this->view()->getData()['reportesPeriodo']->count();
                if ($reportesCount > 0) {
                    $lastRow = 15 + $reportesCount - 1;
                    $sheet->getStyle('A14:D' . $lastRow)->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    ]);
                    
                    // Crear gráfico de torta debajo de la tabla
                    $this->addPieChart($sheet, $lastRow);
                }
            },
        ];
    }

    protected function addPieChart(Worksheet $sheet, int $dataLastRow)
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

        // Escribir datos en columnas auxiliares (F, G)
        $sheet->setCellValue('F1', 'Concepto');
        $sheet->setCellValue('G1', 'Cantidad');
        $sheet->setCellValue('F2', 'Avance previo');
        $sheet->setCellValue('G2', $avancePrevio);
        $sheet->setCellValue('F3', 'Avance del período');
        $sheet->setCellValue('G3', $avancePeriodo);
        $sheet->setCellValue('F4', 'Restante por alcanzar');
        $sheet->setCellValue('G4', $restante);

        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$F$2:$F$4', null, 3),
        ];
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$F$2:$F$4', null, 3),
        ];
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$G$2:$G$4', null, 3),
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

        $chart->setTopLeftPosition('A' . ($dataLastRow + 3));
        $chart->setBottomRightPosition('H' . ($dataLastRow + 20));
        $sheet->addChart($chart);
    }
}