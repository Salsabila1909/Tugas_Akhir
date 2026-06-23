<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Siswa;
use App\Models\Fingerprint;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Cache;

class FingerprintController extends Controller
{
    /**
     * =========================
     * CEK SISWA PENDING REGISTER
     * =========================
     */
    public function pending()
    {
        $siswa = Siswa::where('status', 'belum_terdaftar')
            ->whereDoesntHave('fingerprint')
            ->first();

        if (!$siswa) {
            return response()->json([
                'success' => false
            ]);
        }

        return response()->json([
            'success'  => true,
            'siswa_id' => $siswa->id,
            'nama'     => $siswa->nama
        ]);
    }

    /**
     * =========================
     * REGISTER FINGERPRINT
     * =========================
     */
    public function register(Request $request)
    {
        $request->validate([
            'finger_id' => 'required'
        ]);

        $finger_id = trim($request->finger_id);

        $cekFinger = Fingerprint::where(
            'finger_id',
            $finger_id
        )->first();

        if ($cekFinger) {

            return response()->json([
                'success' => false,
                'message' => 'Fingerprint sudah digunakan'
            ]);
        }

        $siswa = Siswa::where('status', 'belum_terdaftar')
            ->whereDoesntHave('fingerprint')
            ->first();

        if (!$siswa) {

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada siswa pending'
            ]);
        }

        Fingerprint::create([
            'siswa_id'  => $siswa->id,
            'finger_id' => $finger_id
        ]);

        /**
         * Jika RFID sudah ada
         * maka status menjadi terdaftar
         */
        if ($siswa->rfid) {

            $siswa->update([
                'status' => 'terdaftar'
            ]);
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Fingerprint berhasil didaftarkan',
            'siswa_id'  => $siswa->id,
            'siswa'     => $siswa->nama,
            'finger_id' => $finger_id
        ]);
    }

    /**
     * =========================
     * Login
     * =========================
     */

   public function login(Request $request)
{
    $request->validate([
        'finger_id' => 'required'
    ]);

    $finger = Fingerprint::with('siswa.user')
        ->where('finger_id', $request->finger_id)
        ->first();

    if (!$finger) {
        return response()->json([
            'success' => false,
            'message' => 'Fingerprint tidak dikenali'
        ]);
    }

    $siswa = $finger->siswa;

    if (!$siswa) {
        return response()->json([
            'success' => false,
            'message' => 'Siswa tidak ditemukan'
        ]);
    }

    if ($siswa->status !== 'terdaftar') {
        return response()->json([
            'success' => false,
            'message' => 'Siswa belum aktif'
        ]);
    }

    $user = $siswa->user;

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User siswa tidak ditemukan'
        ]);
    }

    if ($user->status != 1) {
        return response()->json([
            'success' => false,
            'message' => 'Akun tidak aktif'
        ]);
    }

    if ($user->level != 0) {
        return response()->json([
            'success' => false,
            'message' => 'Fingerprint hanya untuk siswa'
        ]);
    }

    Cache::put(
        'fingerprint_login',
        $user->id,
        now()->addMinutes(1)
    );

    return response()->json([
        'success' => true,
        'message' => 'Fingerprint dikenali',
        'nama'    => $siswa->nama
    ]);
}
//     public function verify(Request $request)
//     {
//         $request->validate([
//             'finger_id' => 'required'
//         ]);

//         return DB::transaction(function () use ($request) {

//             /**
//              * Cari fingerprint
//              */
//             $finger = Fingerprint::with('siswa')
//                 ->where('finger_id', $request->finger_id)
//                 ->first();

//             if (!$finger) {

//                 return response()->json([
//                     'success' => false,
//                     'message' => 'Fingerprint tidak dikenali'
//                 ]);
//             }

//             /**
//              * Cari siswa
//              */
//             $siswa = $finger->siswa;

//             if (!$siswa) {

//                 return response()->json([
//                     'success' => false,
//                     'message' => 'Siswa tidak ditemukan'
//                 ]);
//             }

//             /**
//              * Pastikan siswa aktif
//              */
//             if ($siswa->status !== 'terdaftar') {

//                 return response()->json([
//                     'success' => false,
//                     'message' => 'Siswa belum aktif'
//                 ]);
//             }

//             /**
//              * Cari transaksi aktif
//              */
//             $transaksi = Transaksi::with('produk')
//                 ->where('siswa_id', $siswa->id)
//                 ->where('status', 'rfid_verified')
//                 ->latest()
//                 ->lockForUpdate()
//                 ->first();

//             if (!$transaksi) {

//                 return response()->json([
//                     'success' => false,
//                     'message' => 'Tidak ada transaksi aktif'
//                 ]);
//             }

//             /**
//              * =========================
//              * TOPUP
//              * =========================
//              */
//             if ($transaksi->type == 'topup') {

//                 $saldoAwal = $siswa->saldo;

//                 $siswa->increment(
//                     'saldo',
//                     $transaksi->total
//                 );

//                 $transaksi->update([
//                     'status'    => 'success',
//                     'finger_id' => $request->finger_id,
//                     'paid_at'   => now()
//                 ]);

//                 $siswa->refresh();

//                 return response()->json([
//                     'success'      => true,
//                     'message'      => 'Topup berhasil',
//                     'transaksi_id' => $transaksi->id,
//                     'siswa'        => $siswa->nama,
//                     'saldo_awal'   => $saldoAwal,
//                     'saldo_akhir'  => $siswa->saldo
//                 ]);
//             }

//             /**
//              * =========================
//              * PAYMENT
//              * =========================
//              */
//             if ($transaksi->type == 'payment') {

//                 /**
//                  * Produk wajib ada
//                  */
//                 if (!$transaksi->produk) {

//                     $transaksi->update([
//                         'status' => 'failed'
//                     ]);

//                     return response()->json([
//                         'success' => false,
//                         'message' => 'Produk tidak ditemukan'
//                     ]);
//                 }

//                 /**
//                  * Cek stok
//                  */
//                 if (
//                     $transaksi->produk->stok <
//                     $transaksi->qty
//                 ) {

//                     $transaksi->update([
//                         'status' => 'failed'
//                     ]);

//                     return response()->json([
//                         'success' => false,
//                         'message' => 'Stok tidak mencukupi'
//                     ]);
//                 }

//                 /**
//                  * Cek saldo
//                  */
//                 if (
//                     $siswa->saldo <
//                     $transaksi->total
//                 ) {

//                     $transaksi->update([
//                         'status' => 'failed'
//                     ]);

//                     return response()->json([
//                         'success' => false,
//                         'message' => 'Saldo tidak cukup',
//                         'saldo'   => $siswa->saldo,
//                         'total'   => $transaksi->total
//                     ]);
//                 }

//                 $saldoAwal = $siswa->saldo;

//                 /**
//                  * Kurangi saldo
//                  */
//                 $siswa->decrement(
//                     'saldo',
//                     $transaksi->total
//                 );

//                 /**
//                  * Kurangi stok
//                  */
//                 $transaksi->produk->decrement(
//                     'stok',
//                     $transaksi->qty
//                 );

//                 /**
//                  * Selesaikan transaksi
//                  */
//                 $transaksi->update([
//                     'status'    => 'success',
//                     'finger_id' => $request->finger_id,
//                     'paid_at'   => now()
//                 ]);

//                 $siswa->refresh();

//                 return response()->json([
//                     'success'      => true,
//                     'message'      => 'Payment berhasil',
//                     'transaksi_id' => $transaksi->id,
//                     'siswa'        => $siswa->nama,
//                     'produk'       => $transaksi->produk->nama_produk,
//                     'qty'          => $transaksi->qty,
//                     'total'        => $transaksi->total,
//                     'saldo_awal'   => $saldoAwal,
//                     'saldo_akhir'  => $siswa->saldo,
//                     'stok_sisa'    => $transaksi->produk->fresh()->stok
//                 ]);
//             }

//             return response()->json([
//                 'success' => false,
//                 'message' => 'Type transaksi tidak valid'
//             ]);
//         });
//     }
 }