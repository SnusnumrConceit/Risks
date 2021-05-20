<?php

namespace App\Exports;

use App\Risk;
use Carbon\Carbon;
use Illuminate\Support\Str;
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
            ->whereIn('division_id', auth()->user()->getDivisions()->pluck('id')->all())
            ->whereBetween(
                'created_at',
                [$this->filters->from . ' 00:00:00', $this->filters->to . ' 23:59:59']
            )->get();
    }

    /**
     * Получить сформированные данные к табличному представлению
     *
     * @param \App\Risk $risk
     * @return array
     */
    public function map($risk): array
    {
        $records = $risk->only(array_merge(['name', 'division'], $this->filters->cols));
        $records['division'] = optional($records['division'])->name;

        foreach ($records as $attribute => $record) {
            if (in_array($attribute, ['level', 'status'])) {
                $records[$attribute] = __(implode('.', ['risks', Str::plural($attribute), $record]));

                continue;
            }

            if ($attribute === 'factors') {
                $records[$attribute] = $this->getMappedFactors($record);

                continue;
            }

            if ($attribute === 'types') {
                $records[$attribute] = $this->getMappedTypes($record);

                continue;
            }

            if (in_array($attribute, ['created_at', 'expired_at'])) {
                $records[$attribute] = $this->getMappedDate($record);

                continue;
            }
        }

        return $records;
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
