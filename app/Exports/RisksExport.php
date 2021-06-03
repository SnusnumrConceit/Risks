<?php

namespace App\Exports;

use App\Risk;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\DB;
use App\Services\RiskExportDataService;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RisksExport implements FromCollection, Responsable, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    use Exportable;

    private $fileName    = 'risks',
            $writerType = Excel::XLSX,
            $filters    = [],
            $risksByDivisions = null,
            $totals = null,
            $divisionRows = [],
            $riskExportDataService;

    /**
     * @param array $filters
     * @param string|null $extension
     */
    public function __construct(array $filters, ?string $extension)
    {
        $this->filters = (object) $filters;

        if ($extension) {
            $this->writerType = $extension;
        }

        $this->riskExportDataService = app(RiskExportDataService::class);
        $this->fileName = $this->getPreparedFileName();
    }

    /**
     * Регистрация событий
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                /** добавление последней итоговой строки */
                $event->sheet->appendRows(array(
                    array_merge(
                        array('Итого'),
                        array_fill_keys(array_values($this->filters->cols), null),
                        $this->totals['']->first()->only('summa', 'damage')
                    )
                ), $event);

                /** @var  $totalColumnsLine - последняя строка "Итого" для мёржа колонок */
                $totalColumnsLine = $this->getDivisionLine($event->sheet->getHighestRow(), $this->getDivisionEndsColumn($event->sheet));

                $event->sheet->setMergeCells(array_merge($event->sheet->getMergeCells(), [$totalColumnsLine => $totalColumnsLine]));

                /** применение стилей к последней итоговой строке */
                $event->sheet->getStyle($this->getDivisionLine($event->sheet->getHighestRow(), $event->sheet->getHighestColumn()))->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                    'font' => ['bold' => true],
                ]);

                $event->sheet->getStyle(
                    $this->getRowByRange($this->getColumnByEndIndex($event->sheet, 1), 2, $event->sheet->getHighestColumn(), $event->sheet->getHighestRow())
                )->getNumberFormat()->applyFromArray([
                    'formatCode' => NumberFormat::FORMAT_NUMBER_00
                ]);
            }
        ];
    }

    /**
     * Подготовленное название файла
     *
     * @return string
     */
    public function getPreparedFileName() : string
    {
        $from = $this->riskExportDataService->getMappedDate($this->filters->from);
        $to   = $this->riskExportDataService->getMappedDate($this->filters->to);

        return implode('-', [$from, $to, $this->fileName]) . '.' . strtolower($this->writerType);
    }

    /**
     * Заголовки таблицы
     *
     * @return array
     */
    public function headings() : array
    {
        $headings = [
            __('risks.name'),
        ];

        foreach (config('report.cols') as $col) {
            if (! in_array($col, $this->filters->cols)) continue;

            array_push($headings, __('risks.' . $col));
        }

        array_push($headings, __('risks.summa'));
        array_push($headings, __('risks.damage'));

        return $headings;
    }

    /**
     * Стили
     *
     * @param Worksheet $sheet
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function styles(Worksheet $sheet)
    {
        $divisionColumn = $this->getDivisionEndsColumn($sheet);

        foreach ($sheet->getRowIterator() as $row) {
            if (in_array($index = $row->getRowIndex(), $this->divisionRows)) {
                $sheet->mergeCells($this->getDivisionLine($index, $divisionColumn));
            }
        }

        $styles = array_fill_keys($this->divisionRows, [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            'font' => ['bold' => true],
        ]);
        $styles['1'] = ['font' => ['bold' => true]];
        ksort($styles);

        return $styles;
    }

    /**
     * Данные для таблицы
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $this->risksByDivisions = collect(Risk::with($this->riskExportDataService->getRelations($this->filters->cols))
            ->whereIn('division_id', auth()->user()->getDivisions()->pluck('id')->all())
            ->whereBetween(
                'created_at',
                [$this->filters->from . ' 00:00:00', $this->filters->to . ' 23:59:59']
            )->get($this->riskExportDataService->getQueryCols($this->filters->cols))
            ->groupBy('division.name')
            ->all()
        );

        $this->totals = Risk::with('division:id,name')
            ->select(
                'division_id',
                DB::raw('SUM(summa) as summa'),
                DB::raw('SUM(damage) as damage')
            )->groupByRaw('division_id WITH ROLLUP')
            ->get()
            ->groupBy('division.name');

        return $this->risksByDivisions;
    }

    /**
     * Подготовка коллекции
     * Поскольку map производится без ключей, то необходимо добавить заголовок (подразделение)
     * на этапе подготовки в начало коллекции
     * Определение строк, на которых располагаются Подразделения
     *
     * @param $rows
     * @return mixed
     */
    public function prepareRows($rows)
    {
        $position = 2;

        foreach ($rows as $division => $records) {
            array_push($this->divisionRows, $position);

            $records->prepend($division);
            $position += count($records);
            $records->push($this->totals[$division]->first());
        }

        return $rows;
    }

    /**
     * Получить сформированные данные к табличному представлению
     *
     * @param $risksWithHeading
     * @return array
     */
    public function map($risksWithHeading): array
    {
        $heading = $risksWithHeading->pull(0);
        $totals = $risksWithHeading->pop()->only('summa', 'damage');

        return array_merge(
            [array_merge([$heading], array_fill_keys(array_values($this->filters->cols), null), $totals)],
            $this->riskExportDataService->getRisksMappedData($risksWithHeading, $this->filters->cols),
        );
    }

    /**
     * Получить колонку по индексу с конца таблицы
     *
     * @param $sheet
     * @param $index
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function getColumnByEndIndex($sheet, $index)
    {
        return Coordinate::stringFromColumnIndex(
            Coordinate::columnIndexFromString($sheet->getHighestColumn()) - $index
        );
    }

    /**
     * Получить координаты строк(-и)
     *
     * @param string $start
     * @param int $startPos
     * @param string $end
     * @param int $endPos
     * @return string
     */
    protected function getRowByRange(string $start, int $startPos, string $end, int $endPos)
    {
        return "{$start}{$startPos}:{$end}{$endPos}";
    }

    /**
     * Получить строку Подразделения
     *
     * @param int $index
     * @param string $column
     * @return string
     */
    protected function getDivisionLine(int $index, string $column)
    {
        return $this->getRowByRange('A', $index, $column, $index);
    }

    /**
     * Получить последнюю колонку с Подразделением
     *
     * @param $sheet
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function getDivisionEndsColumn($sheet)
    {
        return $this->getColumnByEndIndex($sheet, 2);
    }
}
