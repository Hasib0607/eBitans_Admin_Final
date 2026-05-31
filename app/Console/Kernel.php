<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected $commands = [
        'App\Console\Commands\DatabaseBackUp',
        Commands\PseVisitorReport::class,
        Commands\OctaneManagerCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('database:backup')->hourly();
        $schedule->command('pseVisitor:report')->saturdays()->at('10:00')->timezone('Asia/Dhaka');
        $schedule->command('backup:nightly-local')->dailyAt('23:00')->timezone('Asia/Dhaka');

        $schedule->command('action:activeStoreNextPlan')->dailyAt('00:01')->timezone('Asia/Dhaka');
        $schedule->command('action:deleteStoreDomainAndFile')->dailyAt('00:01')->timezone('Asia/Dhaka');
        $schedule->command('Action:deleteAnalyticsReports')->dailyAt('00:01')->timezone('Asia/Dhaka');

        $schedule->command('report:analyticEmailSend')->dailyAt('10:00')->timezone('Asia/Dhaka');
        $schedule->command('report:sendPaymentNotification')->dailyAt('10:00')->timezone('Asia/Dhaka');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
