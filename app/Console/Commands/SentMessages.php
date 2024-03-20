<?php

namespace App\Console\Commands;

use App\Models\SentImages;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\InputMedia\InputMediaAudio;

class SentMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sent-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (\App\Models\Adan::getTodayFajrAdan() !== now()->format('H:i')){
            return 'not time';
        }
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

            //send the quran page
            Telegram::sendPhoto([
                'chat_id' => '@testtorgidly',
                'photo' => new \Telegram\Bot\FileUpload\InputFile(Storage::disk('r2')->readStream('pages/' . $PageName . '.jpg')),
            ]);

            //send the tafsirs if exists
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



//            SentImages::create(['name' => $PageName, 'date' => now()]);
        return 'done' . $PageName;

    }
}
