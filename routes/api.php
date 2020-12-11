<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Auth')->prefix('auth')->group(function () {
    Route::post('register', 'RegisterController');
    Route::post('login', 'LoginController');
    Route::post('logout', 'LogoutController');
});

Route::get('qrcode', function (Request $request) {
    $req = $request->get('string');
    $qrCode = new QrCode($req);

    $qrCode->setSize(500);
    $qrCode->setMargin(20);
    
    // Set advanced options
    $qrCode->setWriterByName('png');
    $qrCode->setEncoding('UTF-8');
    $qrCode->setRoundBlockSize(true, QrCode::ROUND_BLOCK_SIZE_MODE_MARGIN);

    // Save it to a file
    $path = public_path('img/' . $req . '.png');
    $qrCode->writeFile($path);
});
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
