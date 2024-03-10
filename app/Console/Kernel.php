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

        // $schedule->command('inspire')->hourly();
        //call function every 5 minutes
        $schedule->call(function () {

            $PageName = Str::PadLeft(SentImages::query()->count() + 1, 5, '0'); // 00001.jpg

            $tafsirs = array_filter(Storage::disk('r2')->allFiles('tafsir/'), function ($value) use ($PageName) {
                return Str::startsWith($value, 'tafsir/' . $PageName);
            });
            $audios = array_filter(Storage::disk('r2')->allFiles('audio/'), function ($value) use ($PageName) {
                return Str::startsWith($value, 'audio/' . $PageName);
            });
            $mediaTafsirs = array();

            foreach ($tafsirs as $tafsir) {
                // Assuming each $tafsir contains an audio link
                $mediaTafsirs[] = array(
                    'type' => 'photo',
                    'media' => 'http://quranicmesseges.abdo.ly/' . $tafsir . '?random=58',
                    // You can include additional properties like caption and parse_mode if needed
                );
            }


            Telegram::sendPhoto([
                'chat_id' => '@testtorgidly',
                'photo' => new \Telegram\Bot\FileUpload\InputFile(Storage::disk('r2')->readStream('pages/' . $PageName . '.jpg')),
            ]);

            if (!empty($mediaTafsirs)) {
                Telegram::sendMediaGroup(
                    [
                        'chat_id' => '@testtorgidly',
                        'media' => json_encode($mediaTafsirs)

                    ]
                );
            }


            foreach ($audios as $audio) {
                Telegram::sendAudio(
                    [
                        'chat_id' => '@testtorgidly',
                        'audio' => new \Telegram\Bot\FileUpload\InputFile(Storage::disk('r2')->readStream($audio)),
                        'thumb' => new \Telegram\Bot\FileUpload\InputFile(public_path('cover/' . substr($audio, -5, 1) . '.JPEG')),

                    ]
                );
            }

            SentImages::create(['name' => $PageName, 'date' => now()]);
            return 'done' . $PageName;


        })->everyTwoMinutes();
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
