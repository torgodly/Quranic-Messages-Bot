<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Storage;
use Telegram;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        // $schedule->command('inspire')->hourly();
        //call function every 5 minutes
        $schedule->call(function () {
            //sent quran
            $imageFilePath = 'quran/q/00001.jpg';
            $imagePath = Storage::disk('r2')->path($imageFilePath);

            if (file_exists($imagePath)) {
                return Telegram::sendPhoto([
                    'chat_id' => '@testtorgidly',
                    'photo' => new \Telegram\Bot\FileUpload\InputFile($imagePath),
//                    'caption' => 'Hello World'
                ]);
            } else {
                return 'Image file does not exist';
            }

            //sent media group


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
