<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\EspScan;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::latest()->get();
        return view('admin.produk.index', compact('produk'));
    }

    public function add()
    {
        return view('admin.produk.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'kategori'    => 'nullable',
            'harga'       => 'required|numeric',
            'stok'        => 'required|numeric',
        ]);

        $produk = Produk::create([
            'kode_barang' => null,
            'nama_produk' => $request->nama_produk,
            'kategori'    => $request->kategori,
            'harga'       => $request->harga,
            'stok'        => $request->stok,
        ]);

        return redirect()->route('admin.produk.scan', $produk->id)
            ->with('success', 'Produk berhasil dibuat, menunggu scan ESP');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required',
            'kategori'    => 'nullable',
            'harga'       => 'required|numeric',
            'stok'        => 'required|numeric',
        ]);

        $produk = Produk::findOrFail($id);

        $produk->update([
            'nama_produk' => $request->nama_produk,
            'kategori'    => $request->kategori,
            'harga'       => $request->harga,
            'stok'        => $request->stok,
        ]);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Data produk berhasil diupdate');
    }

    public function delete($id)
    {
        Produk::findOrFail($id)->delete();

        return redirect()->route('admin.produk.index')
            ->with('success', 'Data produk berhasil dihapus');
    }

    /*
    |-----------------------------------------
    | SCAN PAGE
    |-----------------------------------------
    */
    public function scan($id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produk.scan', compact('produk'));
    }

    /*
    |-----------------------------------------
    | SAVE KODE BARANG (ADMIN / MANUAL fallback)
    |-----------------------------------------
    */
    public function saveKodeBarang(Request $request, $id)
    {
        $request->validate([
            'kode_barang' => 'required|unique:produk,kode_barang'
        ]);

        $produk = Produk::findOrFail($id);

        // kalau sudah ada, tolak
        if ($produk->kode_barang) {
            return response()->json([
                'status'  => false,
                'message' => 'Produk sudah memiliki kode barang'
            ], 409);
        }

        // cek duplikat kode barang
        $exists = Produk::where('kode_barang', $request->kode_barang)->exists();

        if ($exists) {
            return response()->json([
                'status'  => false,
                'message' => 'Kode barang sudah digunakan'
            ], 409);
        }

        $produk->update([
            'kode_barang' => $request->kode_barang
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Kode barang berhasil disimpan'
        ]);
    }

    /*
    |-----------------------------------------
    | CHECK SCAN (AJAX REALTIME)
    |-----------------------------------------
    */
   public function checkScan($id)
{
    $produk = Produk::findOrFail($id);

    // kalau sudah punya kode barang
    if ($produk->kode_barang) {
        return response()->json([
            'kode_barang' => $produk->kode_barang
        ]);
    }

    // ambil scan terbaru yang belum digunakan
    $scan = EspScan::where('used', 0)
        ->latest()
        ->first();

    if (!$scan) {
        return response()->json([
            'kode_barang' => null
        ]);
    }

    // cek duplikat
    $exists = Produk::where('kode_barang', $scan->kode_barang)
        ->exists();

    if ($exists) {

        $scan->update([
            'used' => 1
        ]);

        return response()->json([
            'kode_barang' => null
        ]);
    }

    // simpan ke produk
    $produk->update([
        'kode_barang' => $scan->kode_barang
    ]);

    // lock scan
    $scan->update([
        'used' => 1
    ]);

    return response()->json([
        'kode_barang' => $scan->kode_barang
    ]);
}
}