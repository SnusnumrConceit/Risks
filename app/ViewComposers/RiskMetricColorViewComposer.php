<?php


namespace App\ViewComposers;


use App\Risk;
use Illuminate\View\View;

class RiskMetricColorViewComposer
{
    public function compose(View $view)
    {
        $statusesColors = [
            __('risks.statuses.' . Risk::STATUS_CREATED)    => '#17a2b8',
            __('risks.statuses.' . Risk::STATUS_PROCESSING) => '#007bff',
            __('risks.statuses.' . Risk::STATUS_FINISHED)   => '#28a745',
            __('risks.statuses.' . Risk::STATUS_EXPIRED)    => '#dc3545',
        ];

        return $view->with(compact('statusesColors'));
    }
}
