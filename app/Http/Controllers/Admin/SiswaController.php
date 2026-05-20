<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Rfid;
use App\Models\Fingerprint;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // =========================
    // READ DATA SISWA + RFID
    // =========================
  public function index()
{
    $siswa = Siswa::with('fingerprint')
        ->leftJoin('rfid', 'rfid.siswa_id', '=', 'siswa.id')
        ->select(
            'siswa.*',
            'rfid.uid as uid'
        )
        ->get();

    return view('admin.siswa.index', compact('siswa'));
}
    // =========================
    // FORM ADD
    // =========================
    public function create()
    {
        return view('admin.siswa.tambah');
    }

    // =========================
    // STORE SISWA
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required',
            'nama' => 'required',
            'kontak' => 'required',
            'alamat' => 'required',
        ]);

        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('siswa', 'public');
        }

        $siswa = Siswa::create([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kontak' => $request->kontak,
            'alamat' => $request->alamat,
            'saldo' => $request->saldo ?? 0,
            'status' => 'belum_terdaftar',
            'foto' => $fotoPath,
        ]);

        return redirect('/admin/siswa/tap-kartu/' . $siswa->id)
            ->with('success', 'Siswa berhasil ditambahkan, silakan tap RFID');
    }

    // =========================
    // EDIT
    // =========================
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);

        return view('admin.siswa.edit', compact('siswa'));
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $data = $request->only([
            'nis',
            'nama',
            'kontak',
            'alamat',
            'saldo'
        ]);

        if ($request->hasFile('foto')) {

            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }

            $data['foto'] = $request->file('foto')
                ->store('siswa', 'public');
        }

        $siswa->update($data);

        return redirect('/admin/siswa')
            ->with('success', 'Data berhasil diupdate');
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);

        // hapus foto
        if ($siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
        }

        // hapus RFID
        Rfid::where('siswa_id', $siswa->id)->delete();

        // hapus fingerprint
        Fingerprint::where('siswa_id', $siswa->id)->delete();

        // hapus siswa
        $siswa->delete();

        return redirect('/admin/siswa')
            ->with('success', 'Data berhasil dihapus');
    }

    // =========================
    // TAP KARTU RFID
    // =========================
    public function tapKartu($id)
{
    $siswa = Siswa::findOrFail($id);

    return view('admin.siswa.tap_kartu', compact('siswa'));
}

    // =========================
    // ASSIGN RFID
    // =========================
    public function assignRfid(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required',
            'uid' => 'required'
        ]);

        $siswa = Siswa::findOrFail($request->siswa_id);

        // cek UID sudah dipakai
        $cekUid = Rfid::where('uid', $request->uid)->first();

        if ($cekUid) {

            return redirect()->back()
                ->with('error', 'UID RFID sudah digunakan');
        }

        // simpan RFID
        Rfid::create([
            'uid' => $request->uid,
            'siswa_id' => $siswa->id
        ]);

        // lanjut fingerprint
        return redirect('/admin/siswa/fingerprint/' . $siswa->id)
            ->with('success', 'RFID berhasil ditambahkan, lanjut fingerprint');
    }

    // =========================
    // PAGE FINGERPRINT
    // =========================
    public function fingerprintPage($id)
    {
        $siswa = Siswa::findOrFail($id);

        return view('admin.siswa.fingerprint', compact('siswa'));
    }

    // =========================
    // CHECK FINGERPRINT
    // =========================
    public function checkFingerprint($id)
    {
        $finger = Fingerprint::where('siswa_id', $id)->first();

        if ($finger) {

            // update status siswa
            Siswa::where('id', $id)
                ->update([
                    'status' => 'terdaftar'
                ]);

            return response()->json([
                'success' => true,
                'finger_id' => $finger->finger_id
            ]);
        }

        return response()->json([
            'success' => false
        ]);
    }
}