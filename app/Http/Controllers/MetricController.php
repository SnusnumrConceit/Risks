<?php

namespace App\Http\Controllers;

use Cache;
use App\Risk;
use App\User;
use App\Services\RiskMetricService;

class MetricController extends Controller
{
    private $riskMetricService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\RiskMetricService $riskMetricService
     * @return void
     */
    public function __construct(RiskMetricService $riskMetricService)
    {
        $this->middleware('auth');
        $this->riskMetricService = $riskMetricService;
    }

    public function index()
    {
        $usersAmount = Cache::remember('users_amount', 300, function () {
            return User::count();
        });

        $risks = Risk::with(['division', 'factors', 'types'])->get();

        $risksTypesMetric = Cache::remember('risks_types_metric', 300, function() use ($risks) {
            return $this->riskMetricService->getTypesMetric($risks);
        });

        $risksFactorsMetric = Cache::remember('risks_factors_metric', 300, function() use ($risks) {
            return $this->riskMetricService->getFactorsMetric($risks);
        });

        $risksStatusesMetric = Cache::remember('risks_statuses_metric', 300, function () use ($risks) {
            return $this->riskMetricService->getStatusesMetric($risks);
        });

        $riskMainDivisionsMetric = Cache::remember('risks_main_divisions_metric', 300, function () use ($risks) {
            return $this->riskMetricService->getMainDivisionsMetric($risks);
        });

        return view('metrics', [
            'users_amount'           => $usersAmount,
            'risks_amount'           => $risks->count(),
            'risks_types_metric'     => $risksTypesMetric,
            'risks_factors_metric'   => $risksFactorsMetric,
            'risks_statuses_metric'  => $risksStatusesMetric,
            'risks_divisions_metric' => $riskMainDivisionsMetric
        ]);
    }
}
