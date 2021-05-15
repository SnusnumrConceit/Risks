<?php


namespace App\Services;


use App\Type;
use App\Factor;

class RiskMetricService
{
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
        return $risks->where('division.level', '<=', 1)
            ->groupBy('division_id')
            ->mapWithKeys(function ($risks) {
                return [ $risks->first()->division->name => $risks->count() ];
            })
            ->all();
    }
}
