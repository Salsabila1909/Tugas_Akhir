<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Halaman Login
     */
    public function index()
    {
        if (Auth::check()) {

            if (Auth::user()->level == 1) {
                return redirect()->route('admin.home');
            }

            if (Auth::user()->level == 0) {
                return redirect()->route('siswa.home');
            }
        }

        return view('auth.login');
    }

    /**
     * Proses Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'status'   => 1,
        ];

        if (!Auth::attempt($credentials)) {
            return redirect()->route('login')
                ->with('error', 'Username atau Password Salah!');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Admin
        if ($user->level == 1) {
            return redirect()->route('admin.home');
        }

        // Siswa
        if ($user->level == 0) {
            return redirect()->route('siswa.home');
        }

        // Jika level tidak valid
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('error', 'Level user tidak valid!');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda Berhasil Logout!');
    }
}