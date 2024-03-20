<?php

namespace App\Console;

use App\Models\SentImages;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Str;
use Storage;
use Telegram;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

//        // $schedule->command('inspire')->hourly();
//        //call function every 5 minutes
        $schedule->call(function () {

            SentImages::create(['name' => '00001', 'date' => now()]);

            print "Hello World".SentImages::count();

        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
