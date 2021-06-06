<?php

namespace App\Console;

use App\Risk;
use App\Events\RiskExpired;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Risk::expiring()->update([
                'status' => Risk::STATUS_EXPIRED
            ]);
        })->everyMinute(); // TODO для тестирования

        $schedule->call(function () {
            for ($beforeDays = 0; $beforeDays <= 3; $beforeDays++) {
                if ($beforeDays === 2) continue;

                event(RiskExpired::class, $beforeDays);
            }
        })->everyMinute(); // TODO для тестирования
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
