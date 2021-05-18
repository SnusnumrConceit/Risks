<?php

namespace App\Http\Controllers;

use Cache;
use App\Risk;
use App\User;
use App\Division;
use App\Services\RiskMetricService;

class MetricController extends Controller
{
    private $riskMetricService, $user, $canViewAllRisks;

    const CACHE_DEFAULT = 30;

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

        $this->middleware(function($request, $next) {
            $this->user = auth()->user();
            $this->canViewAllRisks = $this->user->hasPermission('risks_view');

            return $next($request);
        });
    }

    public function index()
    {
        if ($this->canViewAllRisks) {
            $usersAmount = Cache::remember(RiskMetricService::USERS_AMOUNT_METRIC, self::CACHE_DEFAULT, function () {
                return User::count();
            });
        }

        $risks = Risk::with(['division', 'factors', 'types']);

        if (! $this->canViewAllRisks) {
            if ($this->user->is_responsible) {
                $risks = $risks->whereIn(
                    'division_id',
                    Division::getDescendantsIds($this->user->division_id, $this->user->division->level)
                );
            } else {
                $risks = $risks->where('division_id', $this->user->division_id);
            }
        }

        $risks = $risks->get();

        if ($this->canViewAllRisks) {
            $extra = [];
        } else {
            $extra = ['division_id' => $this->user->division_id];

            if ($this->user->is_responsible) $extra['is_responsible'] = $this->user->is_responsible;
        }

        $risksTypesMetric = Cache::remember(
            $this->riskMetricService->getMetricCacheKey(RiskMetricService::RISKS_TYPES_METRIC, $extra),
            self::CACHE_DEFAULT,
            function() use ($risks) {
                return $this->riskMetricService->getTypesMetric($risks);
            }
        );

        $risksFactorsMetric = Cache::remember(
            $this->riskMetricService->getMetricCacheKey(RiskMetricService::RISKS_FACTORS_METRIC, $extra),
            self::CACHE_DEFAULT,
            function() use ($risks) {
                return $this->riskMetricService->getFactorsMetric($risks);
            }
        );

        $risksStatusesMetric = Cache::remember(
            $this->riskMetricService->getMetricCacheKey(RiskMetricService::RISKS_STATUSES_METRIC, $extra),
            self::CACHE_DEFAULT,
            function () use ($risks) {
                return $this->riskMetricService->getStatusesMetric($risks);
            }
        );

        $risksDivisionsMetric = Cache::remember(
            $this->riskMetricService->getMetricCacheKey(RiskMetricService::RISKS_DIVISIONS_METRIC, $extra),
            self::CACHE_DEFAULT,
            function () use ($risks) {
                return (! $this->canViewAllRisks)
                    ? $this->riskMetricService->getDivisionsMetric($risks)
                    : $this->riskMetricService->getMainDivisionsMetric($risks);
            }
        );

        return view('metrics', [
            'users_amount'           => $usersAmount ?? [],
            'risks_amount'           => $risks->count(),
            'risks_types_metric'     => $risksTypesMetric,
            'risks_factors_metric'   => $risksFactorsMetric,
            'risks_statuses_metric'  => $risksStatusesMetric,
            'risks_divisions_metric' => $risksDivisionsMetric
        ]);
    }
}
