<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\RfidController;
use App\Http\Controllers\Api\FingerprintController;

/*
|--------------------------------------------------------------------------
| RFID ROUTES
|--------------------------------------------------------------------------
*/

Route::post('/rfid/register', [RfidController::class, 'register']);

Route::get('/rfid/get-siswa', [RfidController::class, 'getSiswa']);

/*
|--------------------------------------------------------------------------
| FINGERPRINT ROUTES
|--------------------------------------------------------------------------
*/

// ESP8266 kirim fingerprint
Route::post('/fingerprint/register', [FingerprintController::class, 'register']);
