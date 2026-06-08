<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\RfidController;
use App\Http\Controllers\Api\FingerprintController;
use App\Http\Controllers\Api\EspController;

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

// endpoint utama ESP scan
    Route::post('/scan', [EspController::class, 'scan']);

    // monitoring terakhir scan (optional dashboard realtime)
    Route::get('/last-scan', [EspController::class, 'lastScan']);