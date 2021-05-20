<?php

namespace App\Exports;

use App\Risk;
use Maatwebsite\Excel\Excel;
use App\Services\RiskExportDataService;
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

    private $fileName    = 'risks',
            $writerType = Excel::XLSX,
            $filters    = [],
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
        return collect(Risk::with($this->riskExportDataService->getRelations($this->filters->cols))
            ->whereIn('division_id', auth()->user()->getDivisions()->pluck('id')->all())
            ->whereBetween(
                'created_at',
                [$this->filters->from . ' 00:00:00', $this->filters->to . ' 23:59:59']
            )->get($this->riskExportDataService->getQueryCols($this->filters->cols))
            ->groupBy('division.name')
            ->all()
        );
    }

    /**
     * Подготовка коллекции
     * Поскольку map производится без ключей,
     * то необходимо добавить заголовок (подразделение)
     * на этапе подготовки
     * в начало коллекции
     *
     * @param $rows
     * @return mixed
     */
    public function prepareRows($rows)
    {
        foreach ($rows as $division => $records) {
            $records->prepend($division);
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
        return array_merge(
            [[$risksWithHeading->pull(0)]],
            $this->riskExportDataService->getRisksMappedData($risksWithHeading, $this->filters->cols)
        );
    }
}
