<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Siswa;
use App\Models\Rfid;

class RfidController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'uid' => 'required'
        ]);

        // =========================
        // FORMAT UID
        // =========================
        $uid = strtoupper(trim($request->uid));

        // =========================
        // CEK UID SUDAH ADA
        // =========================
        $cekUid = Rfid::where('uid', $uid)->first();

        if ($cekUid) {

            return response()->json([
                'success' => false,
                'message' => 'UID sudah digunakan'
            ]);
        }

        // =========================
        // AMBIL SISWA BELUM TERDAFTAR
        // =========================
        $siswa = Siswa::where('status', 'belum_terdaftar')
                    ->whereDoesntHave('rfid')
                    ->orderBy('id', 'asc')
                    ->first();

        if (!$siswa) {

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada siswa untuk registrasi RFID'
            ]);
        }

        // =========================
        // SIMPAN RFID
        // =========================
        Rfid::create([
            'uid' => $uid,
            'siswa_id' => $siswa->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'RFID berhasil didaftarkan',
            'siswa' => $siswa->nama,
            'siswa_id' => $siswa->id,
            'next' => 'fingerprint'
        ]);
    }
}