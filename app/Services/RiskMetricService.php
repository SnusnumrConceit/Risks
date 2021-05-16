<?php


namespace App\Services;


use App\Type;
use App\Factor;

class RiskMetricService
{
    const METRIC_PREFIX          = '_metric';
    const RESPONSIBLE_PREFIX     = '_responsible';
    const USERS_AMOUNT_METRIC    = 'users_amount'    . self::METRIC_PREFIX;
    const RISKS_TYPES_METRIC     = 'risks_types'     . self::METRIC_PREFIX;
    const RISKS_FACTORS_METRIC   = 'risks_factors'   . self::METRIC_PREFIX;
    const RISKS_STATUSES_METRIC  = 'risks_statuses'  . self::METRIC_PREFIX;
    const RISKS_DIVISIONS_METRIC = 'risks_divisions' . self::METRIC_PREFIX;

    /**
     * Метрика рисков по видам
     *
     * @param \Illuminate\Support\Collection $risks
     * @return array
     */
    public function getTypesMetric($risks) : array
    {
        $types = Type::all(['id', 'name']);

        $metrics = [];
        foreach ($risks as $risk) {
            foreach ($risk->types as $type) {
                if  ($types->contains($type)) {
                    if (! array_key_exists($type->name, $metrics)) {
                        $metrics[$type->name] = 1;
                    } else {
                        $metrics[$type->name]++;
                    }
                }
            }
        }

        return $metrics;
    }

    /**
     * Метрика рисков по факторам
     *
     * @param \Illuminate\Support\Collection $risks
     * @return array
     */
    public function getFactorsMetric($risks) : array
    {
        $factors = Factor::all(['id', 'name']);

        $metrics = [];
        foreach ($risks as $risk) {
            foreach ($risk->factors as $factor) {
                if  ($factors->contains($factor)) {
                    if (! array_key_exists($factor->name, $metrics)) {
                        $metrics[$factor->name] = 1;
                    } else {
                        $metrics[$factor->name]++;
                    }
                }
            }
        }

        return $metrics;
    }

    /**
     * Метрика рисков по статусам
     *
     * @param \Illuminate\Support\Collection $risks
     * @return array
     */
    public function getStatusesMetric($risks) : array
    {
        return $risks->groupBy('status')->mapWithKeys(function($risks, $status) {
            return [ __('risks.statuses.' . $status) => $risks->count() ];
        })->all();
    }

    /**
     *  Метрика рисков по головным подразделениям (0го и 1го уровня)
     *
     * @param $risks
     * @return array
     */
    public function getMainDivisionsMetric($risks) : array
    {
        return $this->getDivisionsMetric($risks->where('division.level', '<=', 1));
    }

    /**
     * Метрика рисков по подразделениям
     *
     * @param $risks
     * @return array
     */
    public function getDivisionsMetric($risks) : array
    {
        return $risks->groupBy('division_id')
            ->mapWithKeys(function ($risks) {
                return [ $risks->first()->division->name => $risks->count() ];
            })
            ->all();
    }

    /**
     * Получить сформированный ключ метрики
     * Состав $extra:
     * - is_responsible
     * - division_id
     *
     * @param string $key
     * @param array $extra
     * @return string
     */
    public function getMetricCacheKey(string $key, array $extra = []) : string
    {
        if (empty($extra)) return $key;

        $key = str_replace(self::METRIC_PREFIX, '', $key);

        if (array_key_exists('is_responsible', $extra)) {
            $key .= self::RESPONSIBLE_PREFIX;
        }

        return $key . '_' . $extra['division_id'] . self::METRIC_PREFIX;
    }
}
