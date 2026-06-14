<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Auth\SiswaRegisterController;

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
| Guest
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/', [LoginController::class, 'index']);

    Route::get('/login', [LoginController::class, 'index'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login']);

    // Register siswa
    Route::get('/register-siswa', [SiswaRegisterController::class, 'create'])
        ->name('siswa.register');

    Route::post('/register-siswa', [SiswaRegisterController::class, 'store'])
        ->name('siswa.register.store');
});

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

});

Route::middleware('auth')->group(function () {

    Route::get('/admin/home', function () {
        return view('admin.home');
    });

    Route::get('/siswa/home', function () {
        return view('siswa.home');
    });

});


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

        // RIWAYAT TRANSAKSI SISWA
        Route::get('/riwayat/{id}', 'riwayat')
            ->name('riwayat');
    });

  Route::prefix('admin/produk')
    ->name('admin.produk.')
    ->middleware(['auth', 'cekLevel:1,2'])
    ->controller(ProdukController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');

        Route::get('/add', 'add')->name('add');
        Route::post('/store', 'store')->name('store');

        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');

        Route::delete('/delete/{id}', 'delete')->name('delete');

        // halaman scan barcode
        Route::get('/scan/{id}', 'scan')->name('scan');

        // input manual barcode
        Route::post('/kode/{id}', 'saveKodeBarang')
            ->name('kode.store')
            ->middleware('throttle:60,1');
    });



Route::prefix('admin/transaksi')
    ->name('admin.transaksi.')
    ->middleware(['auth', 'cekLevel:1,2'])
    ->controller(TransaksiController::class)
    ->group(function () {

        // =========================
        // INDEX
        // =========================
        Route::get('/', 'index')
            ->name('index');

        // =========================
        // PAYMENT
        // =========================
        Route::get('/payment', 'createPayment')
            ->name('payment');

        Route::post('/payment/store', 'storePayment')
            ->name('storePayment');

        // =========================
        // TOPUP
        // =========================
        Route::get('/topup', 'createTopup')
            ->name('topup');

        Route::post('/topup/store', 'storeTopup')
            ->name('storeTopup');

        // =========================
        // RFID PAGE
        // =========================
        Route::get('/{id}/tab-kartu', 'tabKartu')
            ->name('tab_kartu');

        // realtime polling RFID
        Route::get('/{id}/check-rfid', 'checkRfid')
            ->name('check_rfid');

        // =========================
        // FINGERPRINT PAGE
        // =========================
        Route::get('/{id}/sidik_jari', 'fingerprintPage')
            ->name('sidik_jari');

        // realtime polling fingerprint
        Route::get('/{id}/check-fingerprint', 'checkFingerprint')
            ->name('check_fingerprint');

        // =========================
        // FINISH TRANSAKSI
        // =========================
        Route::post('/{id}/finish', 'finish')
            ->name('finish');

        // =========================
        // DELETE
        // =========================
        Route::delete('/{id}', 'destroy')
            ->name('delete');
    });