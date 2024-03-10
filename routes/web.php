<?php

use App\Models\Package;
use App\Models\SentImages;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

//get thumb

Route::get('/tump', function () {
    $fileStream = Storage::disk('local')->readStream('images.JPEG');

    if ($fileStream) {
        return Response::stream(function () use ($fileStream) {
            fpassthru($fileStream);
        }, 200, [
            'Content-Type' => Storage::disk('local')->mimeType('images.JPEG'),
            'Content-Disposition' => 'inline; filename="' . 'images.JPEG' . '"',
        ]);
    } else {
        abort(404);
    }
});
