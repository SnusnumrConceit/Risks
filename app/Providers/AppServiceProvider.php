<?php

namespace App\Providers;

use App\User;
use App\Risk;
use App\Role;
use App\Division;
use App\Observers\UserObserver;
use App\Observers\RoleObserver;
use App\Observers\RiskObserver;
use App\Observers\DivisionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Role::observe(RoleObserver::class);
        Division::observe(DivisionObserver::class);
        Risk::observe(RiskObserver::class);
    }
}
