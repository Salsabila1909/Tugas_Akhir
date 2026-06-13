<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // login attempt + hanya user aktif
        if (!Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
            'status' => 1
        ])) {
            return redirect('/login')
                ->with('error', 'Username atau Password Salah!');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // =====================
        // ROLE CHECK
        // =====================

        if ($user->level == 1) {
            return redirect('/admin/home');
        }

        if ($user->level == 0) {
            return redirect('/siswa/home');
        }

        // kalau level tidak valid
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('error', 'Level user tidak valid');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'Anda Berhasil Logout!');
    }
}