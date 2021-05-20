<?php


namespace App\Services;


use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RiskExportDataService
{
    /**
     * Получить список полей на выборку
     *
     * @param array $cols
     * @return array
     */
    public function getQueryCols(array $cols) : array
    {
        Arr::forget($cols, ['factors, types']);

        return array_merge(['name', 'division_id'], $cols);
    }

    /**
     * Получить массив отношений
     *
     * @param array $cols
     * @return array
     */
    public function getRelations(array $cols) : array
    {
        $relations = ['division:id,name'];

        if (in_array('factors', $cols)) {
            array_push($relations, 'factors:id,name');
        }

        if (in_array('types', $cols)) {
            array_push($relations, 'types:id,name');
        }

        return $relations;
    }

    /**
     * @param $risks
     * @param array $cols
     * @return array
     */
    public function getRisksMappedData($risks, array $cols) : array
    {
        $lines = [];

        foreach ($risks as $key => $risk) {
            $params = $risk->only(array_merge(['name'], $cols));
//            $params['division'] = optional($params['division'])->name;

            foreach ($params as $attribute => $param) {
                if (in_array($attribute, ['level', 'status'])) {
                    $params[$attribute] = __(implode('.', ['risks', Str::plural($attribute), $param]));
                    continue;
                }

                if ($attribute === 'factors') {
                    $params[$attribute] = $this->getMappedFactors($param);
                    continue;
                }

                if ($attribute === 'types') {
                    $params[$attribute] = $this->getMappedTypes($param);
                    continue;
                }

                if (in_array($attribute, ['created_at', 'expired_at'])) {
                    $params[$attribute] = $this->getMappedDate($param);
                    continue;
                }
            }

            array_push($lines, $params);
        }

        return $lines;
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
