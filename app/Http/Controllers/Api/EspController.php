<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\EspScan;
use Carbon\Carbon;

class EspController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SCAN QR DARI ESP32
    |--------------------------------------------------------------------------
    | ESP hanya kirim: kode_barang
    */
    public function scan(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required'
        ]);

        // cari produk berdasarkan kode_barang
        $produk = Produk::where('kode_barang', $request->kode_barang)->first();

        // jika sudah ada produk dengan kode tersebut
        if ($produk) {

            // update log scan
            $scan = EspScan::create([
                'produk_id'   => $produk->id,
                'kode_barang' => $request->kode_barang,
                'waktu_scan'  => Carbon::now()
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Scan berhasil',
                'data'    => $scan,
                'produk'  => $produk
            ]);
        }

        // jika kode belum terdaftar → cari produk kosong
        $produkKosong = Produk::whereNull('kode_barang')->first();

        if (!$produkKosong) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada slot produk kosong'
            ], 404);
        }

        // assign kode_barang ke produk kosong
        $produkKosong->update([
            'kode_barang' => $request->kode_barang
        ]);

        // simpan log scan
        $scan = EspScan::create([
            'produk_id'   => $produkKosong->id,
            'kode_barang' => $request->kode_barang,
            'waktu_scan'  => Carbon::now()
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Produk berhasil di-assign dan scan tersimpan',
            'data'    => $scan,
            'produk'  => $produkKosong
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LAST SCAN (REALTIME MONITORING ADMIN)
    |--------------------------------------------------------------------------
    */
    public function lastScan()
    {
        $scan = EspScan::with('produk')
            ->latest()
            ->first();

        return response()->json([
            'status' => true,
            'data'   => $scan
        ]);
    }
}