<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use App\ViewComposers\RiskViewComposer;
use App\ViewComposers\RiskMetricColorViewComposer;

use App\ViewComposers\ReportViewComposer;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composers([
            RiskViewComposer::class => [
                'risks.index',
                'risks.show',
            ],

            RiskMetricColorViewComposer::class => [
                'metrics'
            ],

            ReportViewComposer::class => [
                'reports.index'
            ]
        ]);
    }
}
