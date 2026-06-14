<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;

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
            'status'      => 'draft'
        ]);

        return redirect()
            ->route('admin.produk.scan', $produk->id)
            ->with('success', 'Produk berhasil dibuat, silakan scan barcode.');
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
            'status'      => $produk->kode_barang ? 'ready' : 'draft'
        ]);

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Data produk berhasil diupdate');
    }

    public function delete($id)
    {
        $produk = Produk::findOrFail($id);

        $produk->delete();

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Data produk berhasil dihapus');
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN SCAN BARCODE
    |--------------------------------------------------------------------------
    */
    public function scan($id)
    {
        $produk = Produk::findOrFail($id);

        // jika sudah ready tidak perlu scan lagi
        if ($produk->status === 'ready') {
            return redirect()
                ->route('admin.produk.index')
                ->with('success', 'Produk sudah memiliki barcode.');
        }

        return view('admin.produk.scan', compact('produk'));
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE KODE BARANG MANUAL (OPTIONAL)
    |--------------------------------------------------------------------------
    */
    public function saveKodeBarang(Request $request, $id)
    {
        $request->validate([
            'kode_barang' => 'required|unique:produk,kode_barang'
        ]);

        $produk = Produk::findOrFail($id);

        if ($produk->kode_barang) {
            return response()->json([
                'status'  => false,
                'message' => 'Produk sudah memiliki kode barang'
            ], 409);
        }

        $exists = Produk::where('kode_barang', $request->kode_barang)
            ->exists();

        if ($exists) {
            return response()->json([
                'status'  => false,
                'message' => 'Kode barang sudah digunakan'
            ], 409);
        }

        $produk->update([
            'kode_barang' => $request->kode_barang,
            'status'      => 'ready'
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Kode barang berhasil disimpan',
            'produk'  => [
                'id'          => $produk->id,
                'kode_barang' => $request->kode_barang,
                'status'      => 'ready'
            ]
        ]);
    }
}