<?php

use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
    $url = 'http://quranicmesseges.test/files/a00001.jpg?test=1';
//    $url = 'https://ebidhub.abdo.ly/storage/319/683.jpeg';

    $media = array(
        array(
            'type' => 'photo',
//            'media' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQxw9rSm0LKHQUQrpSgPrEyS5CxPwfpZo5VagNdFiXK7g&s',
            'media' => $url,
        )

    );

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
    try {
        $filePath = 'pages/' . $filename;
        $fileStream = Storage::disk('r2')->readStream($filePath);
        if ($fileStream) {
            $fileLastModified = Storage::disk('r2')->lastModified($filePath);
            if (strtotime(request()->header('If-Modified-Since')) >= $fileLastModified) {
                return response(null, 304);
            }
            return Response::stream(function () use ($fileStream) {
                fpassthru($fileStream);
            }, 304, [
                'Content-Type' => Storage::disk('r2')->mimeType($filename),
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'Last-Modified' => gmdate('D, d M Y H:i:s', $fileLastModified) . ' GMT',
            ]);
        } else {
            abort(404);
        }
    } catch (RequestException $e) {
        // Handle Guzzle HTTP request exception
        abort(500, 'Failed to retrieve the file.');
    } catch (\Exception $e) {
        // Handle other exceptions
        abort(500, 'An unexpected error occurred.');
    }
});
