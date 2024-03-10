<?php

use App\Models\Package;
use App\Models\SentImages;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

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

    $PageName = Str::PadLeft(SentImages::query()->count() + 1, 5, '0'); // 00001.jpg

    $tafsirs = array_filter(Storage::disk('r2')->allFiles('tafsir/'), function ($value) use ($PageName) {
        return Str::startsWith($value, 'tafsir/' . $PageName);
    });
    $audio = array_filter(Storage::disk('r2')->allFiles('audio/'), function ($value) use ($PageName) {
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

    $mediaAudio = array();
    foreach ($audio as $audio) {
        // Assuming each $tafsir contains an audio link
        $mediaAudio[] = array(
            'type' => 'audio',
            'media' => 'http://quranicmesseges.abdo.ly/' . $audio . '?random=58',
            'thumb' => 'https://quranicmesseges.test/cover/A.JPEG?random=58',
            // You can include additional properties like caption and parse_mode if needed
        );
    }


//    Telegram::sendPhoto([
//        'chat_id' => '@testtorgidly',
//        'photo' => new \Telegram\Bot\FileUpload\InputFile(Storage::disk('r2')->readStream('pages/' . $PageName . '.jpg')),
//    ]);
//
//    Telegram::sendMediaGroup(
//        [
//            'chat_id' => '@testtorgidly',
//            'media' => json_encode($mediaTafsirs)
//
//        ]
//    );
//dd(asset('cover/images.JPEG'));
    // Load the original image from URL
//    $manager = new ImageManager(
//        new Intervention\Image\Drivers\Gd\Driver()
//    );
//
//    $image = $manager->read(public_path('cover/A.JPEG'));
//
//    $image->resize(width: 80, height: 80);
//
//    $encoded = $image->toJpeg();
//    $encoded->save(public_path('cover/As.JPEG'));

    $thumbInputFile = \Telegram\Bot\FileUpload\InputFile::create(public_path('cover/As.JPEG'), 'cover.jpg');

    Telegram::sendMediaGroup([
        'chat_id' => '@testtorgidly',
        'media' => json_encode([
            new Telegram\Bot\Objects\InputMedia\InputMediaAudio([
                'media' => 'http://quranicmesseges.abdo.ly/audio/00001- A.mp3?random=58',
                'thumb' => 'https://quranicmesseges.test/cover/As.JPEG?random=58', // Provide thumbnail URL as string
                'type' => 'audio'
            ]),
        ], JSON_THROW_ON_ERROR)
    ]);

//
//    Telegram::sendAudio(
//        [
//            'chat_id' => '@testtorgidly',
//            'audio' => new \Telegram\Bot\FileUpload\InputFile(Storage::disk('r2')->readStream('audio/00001- A.mp3')),
//            'thumb' => new \Telegram\Bot\FileUpload\InputFile(public_path('cover/As.JPEG')),
//        ]
//    );
//        foreach ($mediaAudio as $audio) {
////            dd($audio);
//            Telegram::sendAudio([
//                'chat_id' => '@testtorgidly',
//                'audio' => new \Telegram\Bot\FileUpload\InputFile($audio['media']),
//                'thumb' => new \Telegram\Bot\FileUpload\InputFile($audio['thumb']),
//            ]);
//        }

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
