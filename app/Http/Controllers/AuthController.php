<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Akun dummy (front end saja). Ganti dengan login asli saat backend siap.
    private const USERNAME = 'admin';
    private const PASSWORD = 'admin123';

    public function showLogin(Request $request)
    {
        // Sudah login: langsung ke dashboard
        if ($request->session()->get('admin_login')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if ($data['username'] === self::USERNAME && $data['password'] === self::PASSWORD) {
            $request->session()->regenerate();
            $request->session()->put('admin_login', true);

            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withErrors(['username' => 'Username atau password salah.'])
            ->onlyInput('username');
    }

    public function logout(Request $request)
    {
        // Akhiri sesi: hapus semua data sesi dan buat token baru
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Anda telah keluar. Sesi telah berakhir.');
    }
}