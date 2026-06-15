<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaRegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required',
            'password' => 'required|min:6',
        ]);

        $siswa = Siswa::where('nis', $request->nis)->first();

        if (!$siswa) {
            return back()->with('error', 'NIS tidak ditemukan');
        }

        if ($siswa->user_id) {
            return back()->with('error', 'Akun sudah terdaftar');
        }

        $user = User::create([
            'name'     => $siswa->nama,
            'username' => $siswa->nis,
            'password' => Hash::make($request->password),
            'level'    => 0,
            'status'   => 1,
        ]);

        $siswa->update([
            'user_id' => $user->id
        ]);

        return redirect('/login')
            ->with('success', 'Registrasi berhasil, silahkan login');
    }
}