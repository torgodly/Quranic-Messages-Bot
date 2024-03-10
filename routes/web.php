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

    $firstpage = 'https://quranicmesseges.abdo.ly/pages/00001.jpg';
//    Telegram::sendPhoto([
//        'chat_id' => '@testtorgidly',
//        'photo' => new \Telegram\Bot\FileUpload\InputFile($firstpage),
//    ]);

    Telegram::sendMediaGroup(
        [
            'chat_id' => '@testtorgidly',
            'media' => json_encode([
                [
                    'type' => 'photo',
                    'media' => 'https://quranicmesseges.abdo.ly/pages/00001.jpg',
//                    'caption' => 'Hello World'
                ], [
                    'type' => 'photo',
                    'media' => 'https://quranicmesseges.abdo.ly/pages/00002.jpg',
//                    'caption' => 'Hello World'
                ],

            ])
        ]
    );

});
Route::get('/audio/{filename}', function ($filename) {
    $fileStream = Storage::disk('r2')->readStream('audio/' . $filename);

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

//pages
Route::get('/pages/{filename}', function ($filename) {
    $fileStream = Storage::disk('r2')->readStream('pages/' . $filename);

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
Route::get('/tafsir/{filename}', function ($filename) {
    $fileStream = Storage::disk('r2')->readStream('tafsir/' . $filename);

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

