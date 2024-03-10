<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
//    $audioFilePath = 'quran/audio';
////    $audioFiles = Storage::disk('r2')->files();
////    dd($audioFiles);
//    Storage::disk('r2')->put('example.txt', 'Hello World');
    $FilesPath = 'pages/';
    $Files = Storage::disk('r2')->files($FilesPath);
    $media = array(
        array(
            'type' => 'audio',
            'media' => 'https://quranicmesseges.abdo.ly/files/00001- A.mp3',
        ),
        array(
            'type' => 'audio',
            'media' => 'https://quranicmesseges.abdo.ly/files/00002- B.mp3',
        ),
        array(
            'type' => 'audio',
            'media' => 'https://quranicmesseges.abdo.ly/files/00002- A.mp3',
        ),
        array(
            'type' => 'audio',
            'media' => 'https://quranicmesseges.abdo.ly/files/00002- B.mp3',
        ),

    );
////    dd($media);
////
    $response = Telegram::sendMediaGroup([
        'chat_id' => '@testtorgidly',
        'media' => json_encode($media),
    ]);
////
////    $medias = [
////        \Telegram\Bot\Objects\InputMedia\InputMediaAudio::make(['media' => new \Telegram\Bot\FileUpload\InputFile($audioFiles[1])]),
////        \Telegram\Bot\Objects\InputMedia\InputMediaAudio::make(['media' => new \Telegram\Bot\FileUpload\InputFile($audioFiles[2])]),
////    ];
//////    dd($medias, $media, json_encode($media));
////
////    Telegram::bot()->sendMediaGroup(
////        [
////            'chat_id' => '1234567',
////            'media' => json_encode([
////                \Telegram\Bot\Objects\InputMedia\InputMediaAudio::make([
////                    'audio' => new \Telegram\Bot\Objects\InputMedia\InputMedia(
////                        Storage::disk('local')->path('quran/q/00001.jpg')
////                    ),
////
////                ]),
////            ], JSON_THROW_ON_ERROR)
////        ]
////    );
////

//

//    $imageFilePath = 'audio/00002- B.mp3';
////    $imageFilePath = 'quran/audio/00002- B.mp3';
//    $imagePath = Storage::disk('r2')->readStream($imageFilePath);
//    //show the file size
//    return Telegram::sendAudio([
//        'chat_id' => '@testtorgidly',
//        'audio' => \Telegram\Bot\FileUpload\InputFile::create($imagePath),
//        'caption' => 'Hello World'
//    ]);

});


Route::get('/files/{filename}', function ($filename) {
    $fileStream = Storage::disk('r2')->readStream('audio/'.$filename);

    if ($fileStream) {
        return Response::stream(function () use ($fileStream) {
            fpassthru($fileStream);
        }, 200, [
            'Content-Type' => Storage::disk('r2')->mimeType($filename),
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    } else {
        abort(404);
    }
});
