<?php


namespace App\ViewComposers;


use App\Risk;
use Illuminate\View\View;

class RiskViewComposer
{
    public function compose(View $view)
    {
        $levelClasses = [
            Risk::LEVEL_CRITICAL      => 'danger',
            Risk::LEVEL_HIGH          => 'danger',
            Risk::LEVEL_MODERATE      => 'warning',
            Risk::LEVEL_MEDIUM        => 'warning',
            Risk::LEVEL_LOW           => 'primary',
            Risk::LEVEL_INSIGNIFICANT => 'primary',

        ];

        $statusClasses = [
            Risk::STATUS_CREATED    => 'primary',
            Risk::STATUS_PROCESSING => 'info',
            Risk::STATUS_FINISHED   => 'success',
            Risk::STATUS_EXPIRED    => 'danger',

        ];

        return $view->with(compact('statusClasses', 'levelClasses'));
    }
}
