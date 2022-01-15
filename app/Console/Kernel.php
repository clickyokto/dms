<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Pramix\XInvoice\Models\InvoiceRecurringModel;

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


    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            InvoiceRecurringModel::generateRecurringInvoice();
        })->timezone('Asia/Colombo')
            ->between('23:00', '23:30');

        // $schedule->command('inspire')
        //          ->hourly();
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