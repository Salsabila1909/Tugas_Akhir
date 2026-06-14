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

    $kode = $request->kode_barang;

    // cari produk READY
    $produk = Produk::where('kode_barang', $kode)
        ->where('status', 'ready')
        ->first();

    /*
    |--------------------------------------------------------------------------
    | BARCODE BELUM TERDAFTAR
    |--------------------------------------------------------------------------
    */
    if (!$produk) {

        EspScan::create([
            'kode_barang' => $kode,
            'used' => 0,
            'waktu_scan' => now()
        ]);

        return response()->json([
            'status' => true,
            'mode' => 'draft',
            'message' => 'Barcode tersimpan, assign ke produk'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT
    |--------------------------------------------------------------------------
    */
    $scan = EspScan::create([
        'produk_id'   => $produk->id,
        'kode_barang' => $kode,
        'used'        => 0,
        'waktu_scan'  => now()
    ]);

    return response()->json([
        'status' => true,
        'mode' => 'payment',
        'scan_id' => $scan->id,
        'produk' => [
            'id'    => $produk->id,
            'nama'  => $produk->nama_produk,
            'harga' => $produk->harga,
            'stok'  => $produk->stok,
        ]
    ]);
}

public function checkScan($produk_id)
{
    $scan = EspScan::where('used', 0)
        ->whereNull('produk_id')
        ->latest()
        ->first();

    if (!$scan) {
        return response()->json([
            'status' => false
        ]);
    }

    $produk = Produk::find($produk_id);

    if (!$produk) {
        return response()->json([
            'status' => false
        ]);
    }

    $exists = Produk::where('kode_barang', $scan->kode_barang)->exists();

    if ($exists) {

        $scan->update([
            'used' => 1
        ]);

        return response()->json([
            'status' => false,
            'message' => 'Kode barang sudah digunakan'
        ]);
    }

    $produk->update([
        'kode_barang' => $scan->kode_barang,
        'status' => 'ready'
    ]);

    $scan->update([
        'used' => 1,
        'produk_id' => $produk->id
    ]);

    return response()->json([
        'status' => true,
        'kode_barang' => $scan->kode_barang
    ]);
}

public function paymentRealtime()
{
    $scan = EspScan::with('produk')
        ->where('used', 0)
        ->whereHas('produk', function ($q) {
            $q->where('status', 'ready');
        })
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
        'produk' => [
            'id' => $scan->produk->id,
            'nama' => $scan->produk->nama_produk,
            'harga' => $scan->produk->harga,
        ]
    ]);
}

public function markUsed(Request $request)
{
    $request->validate([
        'scan_id' => 'required|integer'
    ]);

    $scan = EspScan::find($request->scan_id);

    if (!$scan) {
        return response()->json([
            'status' => false,
            'message' => 'Scan tidak ditemukan'
        ], 404);
    }

    $scan->update([
        'used' => 1
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Scan berhasil dikunci'
    ]);
}
}