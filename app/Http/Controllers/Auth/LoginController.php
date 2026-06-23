<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Halaman Login
     */
    public function index()
    {
        if (Auth::guard('web')->check()) {

            $user = Auth::guard('web')->user();

            if ($user->level == 1) {
                return redirect()->route('admin.home');
            }

            if ($user->level == 0) {
                return redirect()->route('siswa.home');
            }

            Auth::guard('web')->logout();
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

        // 🔥 PENTING: pakai guard web konsisten
        if (!Auth::guard('web')->attempt($request->only('username', 'password'))) {
            return redirect()->route('login')->with('error', 'Login gagal');
        }

        $request->session()->regenerate();

        $user = Auth::guard('web')->user();

        if ($user->level == 1) {
            return redirect()->route('admin.home');
        }

        if ($user->level == 0) {
            return redirect()->route('siswa.home');
        }

        // fallback kalau level tidak valid
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('error', 'Level user tidak valid!');
    }

    /**
     * Fingerprint login
     */
    public function checkFingerprintLogin(Request $request)
    {
        $userId = cache()->pull('fingerprint_login');

        if (!$userId) {
            return response()->json(['success' => false]);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['success' => false]);
        }

        Auth::guard('web')->login($user);

        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'redirect' => $user->level == 1
                ? route('admin.home')
                : route('siswa.home')
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda Berhasil Logout!');
    }
}