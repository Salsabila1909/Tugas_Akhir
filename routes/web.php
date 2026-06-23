<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Siswa\TransaksiController;
use App\Http\Controllers\Siswa\HomeController as SiswaHomeController;

/*
|--------------------------------------------------------------------------
| CLEAR CACHE
|--------------------------------------------------------------------------
*/
Route::get('/clear', function () {
    Artisan::call('optimize:clear');
    return '<h1>Berhasil dibersihkan</h1>';
});

/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/', [LoginController::class, 'index']);
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::get('/fingerprint/check-login', [LoginController::class, 'checkFingerprintLogin']);

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        Route::get('/home', [HomeController::class, 'index'])
            ->name('admin.home');

        /*
        |------------------------------
        | ADMIN SISWA
        |------------------------------
        */
        Route::prefix('siswa')
            ->name('admin.siswa.')
            ->controller(SiswaController::class)
            ->group(function () {

                Route::get('/', 'index')->name('read');
                Route::get('/add', 'create')->name('add');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/update/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'destroy')->name('delete');

                Route::get('/tap-kartu/{id}', 'tapKartu')->name('tap');
                Route::post('/assign-rfid', 'assignRfid')->name('rfid.assign');

                Route::get('/fingerprint/{id}', 'fingerprintPage')->name('fingerprint');
                Route::get('/fingerprint/check/{id}', 'checkFingerprint')->name('fingerprint.check');

                Route::get('/riwayat/{id}', 'riwayat')->name('riwayat');
            });

        /*
        |------------------------------
        | ADMIN PRODUK
        |------------------------------
        */
        Route::prefix('produk')
            ->name('admin.produk.')
            ->controller(ProdukController::class)
            ->group(function () {

                Route::get('/', 'index')->name('index');
                Route::get('/add', 'add')->name('add');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::put('/update/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'delete')->name('delete');

                Route::get('/scan/{id}', 'scan')->name('scan');
                Route::post('/kode/{id}', 'saveKodeBarang')
                    ->name('kode.store')
                    ->middleware('throttle:60,1');
            });
});

/*
|--------------------------------------------------------------------------
| SISWA AREA
|--------------------------------------------------------------------------
*/
Route::prefix('siswa')
    ->middleware('auth')
    ->group(function () {

        Route::get('/home', [SiswaHomeController::class, 'index'])
            ->name('siswa.home');

        Route::get('/change', [SiswaHomeController::class, 'change']);
        Route::post('/change_password', [SiswaHomeController::class, 'change_password']);

        /*
        |------------------------------
        | TRANSAKSI SISWA
        |------------------------------
        */
        Route::prefix('transaksi')
            ->name('siswa.transaksi.')
            ->controller(TransaksiController::class)
            ->group(function () {

                Route::get('/', 'index')->name('index');

                Route::get('/payment', 'createPayment')->name('payment');
                Route::post('/payment/store', 'storePayment')->name('storePayment');

                Route::get('/topup', 'createTopup')->name('topup');
                Route::post('/topup/store')->name('storeTopup');

                Route::get('/{id}/tab-kartu', 'tabKartu')->name('tab_kartu');
                Route::get('/{id}/check-rfid', 'checkRfid')->name('check_rfid');

                Route::get('/{id}/sidik_jari', 'fingerprintPage')->name('sidik_jari');
                Route::get('/{id}/check-fingerprint', 'checkFingerprint')->name('check_fingerprint');

                Route::post('/{id}/finish', 'finish')->name('finish');
                Route::delete('/{id}', 'destroy')->name('delete');

                Route::get('/profil', 'profil')->name('profil');
            });
    });