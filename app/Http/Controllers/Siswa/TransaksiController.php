<?php

namespace App\Http\Controllers\Siswa;

use Illuminate\Support\Facades\Auth;
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
        $siswa = Siswa::where('user_id', Auth::id())->first();

        if (!$siswa) {
            return redirect()->route('siswa.home')
                ->with('error', 'Siswa tidak ditemukan.');
        }

        $transaksi = Transaksi::with(['siswa', 'produk'])
            ->where('siswa_id', $siswa->id)
            ->latest()
            ->get();

        return view('siswa.transaksi.index', compact('transaksi'));
    }

    /**
     * GET SISWA LOGIN
     */
    private function getSiswaLogin()
    {
        return Siswa::where('user_id', Auth::id())
            ->where('status', 'terdaftar')
            ->first();
    }

    /**
     * FORM PAYMENT
     */
    public function createPayment()
    {
        $siswa = $this->getSiswaLogin();

        if (!$siswa) {
            return redirect()->route('siswa.home')
                ->with('error', 'Siswa belum terdaftar.');
        }

        return view('siswa.transaksi.payment', compact('siswa'));
    }

    /**
     * SIMPAN PAYMENT
     */
    public function storePayment(Request $request)
    {
        $siswa = $this->getSiswaLogin();

        if (!$siswa) {
            return redirect()->route('siswa.home')
                ->with('error', 'Siswa belum terdaftar.');
        }

        $request->validate([
            'produk_id' => 'required',
            'qty' => 'required|integer|min:1'
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        if ($produk->stok < $request->qty) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $total = $produk->harga * $request->qty;

        $transaksi = Transaksi::create([
            'type' => 'payment',
            'siswa_id' => $siswa->id,
            'produk_id' => $produk->id,
            'qty' => $request->qty,
            'harga_satuan' => $produk->harga,
            'total' => $total,
            'status' => 'pending'
        ]);

        return redirect()->route('siswa.transaksi.tab_kartu', $transaksi->id);
    }

    /**
     * FORM TOPUP
     */
    public function createTopup()
    {
        $siswa = $this->getSiswaLogin();

        if (!$siswa) {
            return redirect()->route('siswa.home')
                ->with('error', 'Siswa belum terdaftar.');
        }

        return view('siswa.transaksi.topup', compact('siswa'));
    }

    /**
     * SIMPAN TOPUP
     */
    public function storeTopup(Request $request)
    {
        $siswa = $this->getSiswaLogin();

        if (!$siswa) {
            return redirect()->route('siswa.home')
                ->with('error', 'Siswa belum terdaftar.');
        }

        $request->validate([
            'total' => 'required|numeric|min:1000'
        ]);

        $transaksi = Transaksi::create([
            'type' => 'topup',
            'siswa_id' => $siswa->id,
            'total' => $request->total,
            'status' => 'pending'
        ]);

        return redirect()->route('siswa.transaksi.tab_kartu', $transaksi->id);
    }

    /**
     * PAGE TAP KARTU (RFID ONLY)
     */
    public function tabKartu($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status == 'success') {
            return redirect()
                ->route('siswa.transaksi.index')
                ->with('success', 'Transaksi berhasil');
        }

        return view('siswa.transaksi.tab_kartu', compact('transaksi'));
    }

    /**
     * CHECK RFID (POLLING ESP)
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
     * DELETE TRANSAKSI
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status == 'success') {
            return back()->with('error', 'Transaksi sukses tidak bisa dihapus');
        }

        $transaksi->delete();

        return redirect()
            ->route('siswa.transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }

    public function profil()
{
    $siswa = auth()->user()->siswa; // atau sesuai sistem kamu

    return view('siswa.transaksi.profil', compact('siswa'));
}
}