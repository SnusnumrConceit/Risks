<?php

namespace App\Providers;

use App\ViewComposers\RiskViewComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
            ]
        ]);
    }
}
