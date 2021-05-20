<?php

namespace App\Exports;

use App\Risk;
use Carbon\Carbon;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RisksExport implements FromCollection, Responsable, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    public $fileName    = 'risks',
            $writerType = Excel::XLSX,
            $filters    = [];

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

        $this->fileName = $this->getPreparedFileName();
    }

    /**
     * Подготовленное название файла
     *
     * @return string
     */
    public function getPreparedFileName() : string
    {
        $from = $this->getMappedDate($this->filters->from);
        $to   = $this->getMappedDate($this->filters->to);

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
            __('divisions.division')
        ];

        foreach (config('report.cols') as $col) {
            if (! in_array($col, $this->filters->cols)) continue;

            array_push($headings, __('risks.' . $col));
        }

        return $headings;
    }

    /**
     * Стили
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]] // первая строка выделена жирным
        ];
    }

    /**
     * Данные для таблицы
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Risk::with('factors:id,name', 'types:id,name', 'division:id,name')
            ->whereBetween(
                'created_at',
                [$this->filters->from . ' 00:00:00', $this->filters->to . ' 23:59:59']
            )->get();
    }

    /**
     * Подготовка данных к табличному представлению
     *
     * @param \App\Risk $risk
     * @return array
     */
    public function map($risk): array
    {
        return [
            $risk->name,
            optional($risk->division)->name,
            $risk->summa,
            $risk->damage,
            __('risks.levels.' . $risk->level),
            $risk->likelihood,
            $risk->impact,
            __('risks.statuses.' . $risk->status),
            $this->getMappedTypes($risk->types),
            $this->getMappedFactors($risk->factors),
            $this->getMappedDate($risk->created_at),
            $this->getMappedDate($risk->expired_at),
        ];
    }

    /**
     * Подготовленные виды рисков
     *
     * @param $types
     * @return string
     */
    public function getMappedTypes($types)
    {
        $types = $types->pluck('name')->all();

        return empty($types) ? '' : implode(', ', $types);
    }

    /**
     * Подготовленные факторы рисков
     *
     * @param $factors
     * @return string
     */
    public function getMappedFactors($factors)
    {
        $factors = $factors->pluck('name')->all();

        return empty($factors) ? '' : implode(', ', $factors);
    }

    /**
     * Подготовленная дата
     *
     * @param string $date
     * @return string
     */
    public function getMappedDate(string $date) : string
    {
        return Carbon::parse($date)->format('d.m.Y');
    }
}
