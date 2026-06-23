<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\Siswa;
use App\Models\Rfid;
use App\Models\Transaksi;

class RfidController extends Controller
{
    /**
     * =====================================
     * CEK SISWA BELUM TERDAFTAR RFID
     * =====================================
     */
    public function pending()
    {
        $siswa = Siswa::where('status', 'belum_terdaftar')
            ->whereDoesntHave('rfid')
            ->orderBy('id')
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
     * =====================================
     * REGISTER RFID
     * =====================================
     */
    public function register(Request $request)
    {
        $request->validate([
            'uid' => 'required'
        ]);

        $uid = strtoupper(trim($request->uid));

        $cekRfid = Rfid::where('uid', $uid)->first();

        if ($cekRfid) {

            return response()->json([
                'success' => false,
                'message' => 'UID sudah digunakan'
            ]);
        }

        $siswa = Siswa::where('status', 'belum_terdaftar')
            ->whereDoesntHave('rfid')
            ->orderBy('id')
            ->first();

        if (!$siswa) {

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada siswa pending RFID'
            ]);
        }

        Rfid::create([
            'uid'      => $uid,
            'siswa_id' => $siswa->id
        ]);

        /**
         * Jika fingerprint sudah ada
         * maka siswa aktif
         */
        if ($siswa->fingerprint) {

            $siswa->update([
                'status' => 'terdaftar'
            ]);
        }

        return response()->json([
            'success'  => true,
            'message'  => 'RFID berhasil didaftarkan',
            'uid'      => $uid,
            'siswa_id' => $siswa->id,
            'siswa'    => $siswa->nama
        ]);
    }

    /**
     * =====================================
     * VERIFIKASI RFID TRANSAKSI
     * =====================================
     */
    public function tab_kartu(Request $request)
{
    $request->validate([
        'uid' => 'required'
    ]);

    try {

        $result = DB::transaction(function () use ($request) {

            $uid = strtoupper(trim($request->uid));

            $rfid = Rfid::with('siswa')->where('uid', $uid)->first();

            if (!$rfid) {
                return [
                    'success' => false,
                    'message' => 'RFID tidak terdaftar'
                ];
            }

            $siswa = $rfid->siswa;

            if (!$siswa || $siswa->status !== 'terdaftar') {
                return [
                    'success' => false,
                    'message' => 'Siswa tidak valid'
                ];
            }

            $transaksi = Transaksi::where('siswa_id', $siswa->id)
                ->where('status', 'pending')
                ->latest()
                ->lockForUpdate()
                ->first();

            if (!$transaksi) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada transaksi pending'
                ];
            }

            if ($siswa->saldo < $transaksi->total) {
                return [
                    'success' => false,
                    'message' => 'Saldo tidak cukup'
                ];
            }

            $produk = Produk::find($transaksi->produk_id);

            if (!$produk || $produk->stok < $transaksi->qty) {
                return [
                    'success' => false,
                    'message' => 'Stok tidak cukup'
                ];
            }

            // EKSEKUSI AMAN
            $siswa->decrement('saldo', $transaksi->total);
            $produk->decrement('stok', $transaksi->qty);

            $transaksi->update([
                'status' => 'success',
                'rfid_uid' => $uid
            ]);

            return [
                'success' => true,
                'message' => 'Pembayaran berhasil',
                'siswa' => $siswa->nama,
                'total' => $transaksi->total,
                'saldo_sisa' => $siswa->saldo
            ];
        });

        return response()->json($result);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => 'Server error',
            'error' => $e->getMessage()
        ]);
    }
}
}