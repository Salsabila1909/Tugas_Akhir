<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\EspScan;
use App\Models\Transaksi;
use Carbon\Carbon;

class EspController extends Controller
{
    /*
    |--------------------------------------
    | SCAN UMUM
    |--------------------------------------
    */
    public function scan(Request $request)
{
    $request->validate([
        'kode_barang' => 'required'
    ]);

    $scan = EspScan::create([
        'kode_barang' => $request->kode_barang,
        'used' => 0,
        'waktu_scan' => now()
    ]);

    return response()->json([
        'status' => true,
        'scan_id' => $scan->id,
        'kode_barang' => $request->kode_barang
    ]);
}

    /*
    |--------------------------------------
    | LAST SCAN (ADMIN MONITOR)
    |--------------------------------------
    */
    public function lastScan()
{
    $scan = EspScan::where('used', 0)
        ->latest()
        ->first();

    if (!$scan) {
        return response()->json([
            'status' => false
        ]);
    }

    return response()->json([
        'status' => true,
        'scan_id' => $scan->id,
        'kode_barang' => $scan->kode_barang
    ]);
}
    /*
    |--------------------------------------
    | PAYMENT SCAN (ESP32)
    |--------------------------------------
    */
    public function paymentScan(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required'
        ]);

        $produk = Produk::where('kode_barang', $request->kode_barang)->first();

        if (!$produk) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan'
            ]);
        }

        $scan = EspScan::create([
            'produk_id'   => $produk->id,
            'kode_barang' => $request->kode_barang,
            'used'        => 0,
            'waktu_scan'  => now()
        ]);

        return response()->json([
            'status'  => true,
            'scan_id' => $scan->id,
            'produk'  => [
                'id'    => $produk->id,
                'nama'  => $produk->nama_produk,
                'harga' => $produk->harga,
            ]
        ]);
    }

    /*
    |--------------------------------------
    | LAST PAYMENT SCAN (REALTIME UI)
    |--------------------------------------
    */
    public function lastPaymentScan()
    {
        $scan = EspScan::with('produk')
            ->where('used', 0)
            ->orderBy('id', 'desc')
            ->first();

        if (!$scan || !$scan->produk) {
            return response()->json([
                'status' => false
            ]);
        }

        return response()->json([
            'status'  => true,
            'scan_id' => $scan->id,
            'produk'  => [
                'id'    => $scan->produk->id,
                'nama'  => $scan->produk->nama_produk,
                'harga' => $scan->produk->harga,
            ]
        ]);
    }

    /*
    |--------------------------------------
    | MARK USED (LOCK SCAN)
    |--------------------------------------
    */
    public function markUsed(Request $request)
    {
        $request->validate([
            'scan_id' => 'required'
        ]);

        $scan = EspScan::where('id', $request->scan_id)
            ->where('used', 0)
            ->first();

        if (!$scan) {
            return response()->json([
                'status' => false,
                'message' => 'Scan tidak valid'
            ]);
        }

        $scan->update([
            'used' => 1
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Scan locked'
        ]);
    }
}