<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\RfidController;
use App\Http\Controllers\Api\FingerprintController;
use App\Http\Controllers\Api\EspController;

/*
|--------------------------------------------------------------------------
| RFID
|--------------------------------------------------------------------------
*/
Route::get(
    '/rfid/pending',
    [RfidController::class, 'pending']
);

Route::post(
    '/rfid/register',
    [RfidController::class, 'register']
);

Route::post(
    '/rfid/tab-kartu',
    [RfidController::class, 'tab_kartu']
);
/*
|--------------------------------------------------------------------------
| FINGERPRINT
|--------------------------------------------------------------------------
*/
Route::get(
    '/fingerprint/pending',
    [FingerprintController::class,'pending']
);

Route::post(
    '/fingerprint/register',
    [FingerprintController::class,'register']
);

Route::post(
    '/fingerprint/verify',
    [FingerprintController::class,'verify']
);

/*
|--------------------------------------------------------------------------
| ESP32 SCANNER
|--------------------------------------------------------------------------
*/


Route::prefix('esp')->group(function () {

    // scan dari ESP32
    Route::post('/scan', [EspController::class, 'scan']);

    // scan produk draft
    Route::get('/check-scan/{produk_id}', [EspController::class, 'checkScan']);

    // payment realtime
    Route::get('/payment-realtime', [EspController::class, 'paymentRealtime']);

    // lock scan
    Route::post('/mark-used', [EspController::class, 'markUsed']);
});