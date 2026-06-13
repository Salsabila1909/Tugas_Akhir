<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return DB::transaction(function () use ($request) {

            $uid = strtoupper(trim($request->uid));

            /**
             * Cari RFID
             */
            $rfid = Rfid::with('siswa')
                ->where('uid', $uid)
                ->first();

            if (!$rfid) {

                return response()->json([
                    'success' => false,
                    'message' => 'RFID tidak terdaftar'
                ]);
            }

            /**
             * Cari siswa
             */
            $siswa = $rfid->siswa;

            if (!$siswa) {

                return response()->json([
                    'success' => false,
                    'message' => 'Data siswa tidak ditemukan'
                ]);
            }

            /**
             * Pastikan siswa aktif
             */
            if ($siswa->status !== 'terdaftar') {

                return response()->json([
                    'success' => false,
                    'message' => 'Siswa belum aktif'
                ]);
            }

            /**
             * Cari transaksi pending
             */
            $transaksi = Transaksi::where(
                    'siswa_id',
                    $siswa->id
                )
                ->where('status', 'pending')
                ->latest()
                ->lockForUpdate()
                ->first();

            if (!$transaksi) {

                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada transaksi aktif'
                ]);
            }

            /**
             * Update RFID verified
             */
            $transaksi->update([
                'status'   => 'rfid_verified',
                'rfid_uid' => $uid
            ]);

            return response()->json([
                'success'       => true,
                'message'       => 'RFID berhasil diverifikasi',
                'transaksi_id'  => $transaksi->id,
                'siswa_id'      => $siswa->id,
                'siswa'         => $siswa->nama,
                'type'          => $transaksi->type,
                'total'         => $transaksi->total,
                'next'          => 'fingerprint'
            ]);
        });
    }
}