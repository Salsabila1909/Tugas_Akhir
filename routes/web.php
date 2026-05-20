<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\SiswaController;

/*
|--------------------------------------------------------------------------
| CLEAR CACHE
|--------------------------------------------------------------------------
*/
Route::get('/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('optimize');
    Artisan::call('route:cache');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');

    return '<h1>Berhasil dibersihkan</h1>';
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/keluar', [HomeController::class, 'keluar']);
Route::get('/admin/home', [HomeController::class, 'index']);
Route::get('/admin/change', [HomeController::class, 'change']);
Route::post('/admin/change_password', [HomeController::class, 'change_password']);

/*
|--------------------------------------------------------------------------
| KATEGORI
|--------------------------------------------------------------------------
*/
Route::prefix('admin/kategori')
    ->name('admin.kategori.')
    ->middleware('cekLevel:1,2')
    ->controller(KategoriController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

/*
|--------------------------------------------------------------------------
| SISWA (FIXED FULL)
|--------------------------------------------------------------------------
*/
Route::prefix('admin/siswa')
    ->name('admin.siswa.')
    ->middleware(['auth', 'cekLevel:1,2'])
    ->controller(SiswaController::class)
    ->group(function () {

        // READ
        Route::get('/', 'index')->name('read');

        // CREATE
        Route::get('/add', 'create')->name('add');
        Route::post('/store', 'store')->name('store');

        // EDIT
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');

        // DELETE
        Route::delete('/delete/{id}', 'destroy')->name('delete');

        // TAP RFID
        Route::get('/tap-kartu/{id}', 'tapKartu')->name('tap');

        // RFID
        Route::post('/assign-rfid', 'assignRfid')->name('rfid.assign');

        // ======================
        // FINGERPRINT (FIXED)
        // ======================
        Route::get('/fingerprint/{id}', 'fingerprintPage')->name('fingerprint');

        Route::get('/fingerprint/check/{id}', 'checkFingerprint')->name('fingerprint.check');

    });