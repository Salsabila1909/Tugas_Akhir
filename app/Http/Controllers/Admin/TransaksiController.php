<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Siswa;
use App\Models\Produk;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * INDEX
     */
    public function index()
    {
        $transaksi = Transaksi::with([
            'siswa',
            'produk'
        ])->latest()->get();

        return view(
            'admin.transaksi.index',
            compact('transaksi')
        );
    }

    /**
     * FORM PAYMENT
     */
    public function createPayment()
    {
        $siswa = Siswa::where(
            'status',
            'terdaftar'
        )->get();

        return view(
            'admin.transaksi.payment',
            compact('siswa')
        );
    }

    /**
     * SIMPAN PAYMENT
     */
    public function storePayment(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required',
            'produk_id' => 'required',
            'qty' => 'required|integer|min:1'
        ]);

        $produk = Produk::findOrFail(
            $request->produk_id
        );

        if ($produk->stok < $request->qty) {
            return back()->with(
                'error',
                'Stok tidak mencukupi'
            );
        }

        $total =
            $produk->harga *
            $request->qty;

        $transaksi = Transaksi::create([
            'type' => 'payment',
            'siswa_id' => $request->siswa_id,
            'produk_id' => $produk->id,
            'qty' => $request->qty,
            'harga_satuan' => $produk->harga,
            'total' => $total,
            'status' => 'pending'
        ]);

        return redirect()->route(
            'admin.transaksi.tab_kartu',
            $transaksi->id
        );
    }

    /**
     * FORM TOPUP
     */
    public function createTopup()
    {
        $siswa = Siswa::where(
            'status',
            'terdaftar'
        )->get();

        return view(
            'admin.transaksi.topup',
            compact('siswa')
        );
    }

    /**
     * SIMPAN TOPUP
     */
    public function storeTopup(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required',
            'total' => 'required|numeric|min:1000'
        ]);

        $transaksi = Transaksi::create([
            'type' => 'topup',
            'siswa_id' => $request->siswa_id,
            'total' => $request->total,
            'status' => 'pending'
        ]);

        return redirect()->route(
            'admin.transaksi.tab_kartu',
            $transaksi->id
        );
    }

    /**
     * PAGE RFID
     */
    public function tabKartu($id)
    {
        $transaksi = Transaksi::with(
            'siswa.rfid'
        )->findOrFail($id);

        if ($transaksi->status == 'success') {

            return redirect()
                ->route('admin.transaksi.index')
                ->with(
                    'success',
                    'Transaksi berhasil'
                );
        }

        if ($transaksi->status == 'rfid_verified') {

            return redirect()->route(
                'admin.transaksi.sidik_jari',
                $transaksi->id
            );
        }

        return view(
            'admin.transaksi.tab_kartu',
            compact('transaksi')
        );
    }

    /**
     * PAGE FINGERPRINT
     */
    public function fingerprintPage($id)
    {
        $transaksi = Transaksi::with(
            'siswa.fingerprint'
        )->findOrFail($id);

        if ($transaksi->status == 'success') {

            return redirect()
                ->route('admin.transaksi.index')
                ->with(
                    'success',
                    'Transaksi berhasil'
                );
        }

        if ($transaksi->status == 'pending') {

            return redirect()
                ->route(
                    'admin.transaksi.tab_kartu',
                    $transaksi->id
                )
                ->with(
                    'error',
                    'Silakan scan RFID terlebih dahulu'
                );
        }

        return view(
            'admin.transaksi.sidik_jari',
            compact('transaksi')
        );
    }

    /**
     * HAPUS
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status == 'success') {

            return back()->with(
                'error',
                'Transaksi sukses tidak bisa dihapus'
            );
        }

        $transaksi->delete();

        return redirect()
            ->route('admin.transaksi.index')
            ->with(
                'success',
                'Transaksi berhasil dihapus'
            );
    }

    /**
     * AJAX RFID POLLING
     */
   public function checkRfid($id)
{
    $transaksi = Transaksi::findOrFail($id);

    return response()->json([
        'success' => true,
        'status' => $transaksi->status
    ]);
}

    /**
     * AJAX FINGERPRINT POLLING
     */
   public function checkFingerprint($id)
{
    $transaksi = Transaksi::findOrFail($id);

    return response()->json([
        'success' => true,
        'status' => $transaksi->status
    ]);
}
}