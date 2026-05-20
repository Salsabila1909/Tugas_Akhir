<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Siswa;
use App\Models\Fingerprint;

class FingerprintController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'finger_id' => 'required'
        ]);

        $finger_id = trim($request->finger_id);

        // =========================
        // CEK FINGERPRINT SUDAH ADA
        // =========================
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

        // =========================
        // CARI SISWA YANG BELUM TERDAFTAR
        // =========================
        $siswa = Siswa::whereDoesntHave(
            'fingerprint'
        )->first();

        if (!$siswa) {

            return response()->json([
                'success' => false,
                'message' => 'Semua siswa sudah terdaftar'
            ]);
        }

        // =========================
        // SIMPAN FINGERPRINT
        // =========================
        Fingerprint::create([
            'finger_id' => $finger_id,
            'siswa_id' => $siswa->id
        ]);

        // =========================
        // UPDATE STATUS
        // =========================
        $siswa->update([
            'status' => 'terdaftar'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fingerprint berhasil didaftarkan',
            'finger_id' => $finger_id,
            'siswa' => $siswa->nama
        ]);
    }
}