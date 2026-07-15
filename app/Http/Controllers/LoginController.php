<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    // 2. Proses Validasi & Autentikasi Login
    public function login(Request $request)
    {
        $request->validate([
            'role'           => 'required',
            'login_identity' => 'required',
            'password'       => 'required',
        ]);

        $identity = $request->input('login_identity');
        $password = $request->input('password');
        $role     = $request->input('role');

        // Logika Dinamis: Siswa pakai NIS, yang lain pakai Email
        $fieldType = ($role === 'siswa') ? 'nis' : 'email';

        // Coba lakukan login dengan mencocokkan data identitas, password, dan role
        if (Auth::attempt([$fieldType => $identity, 'password' => $password, 'role' => $role], $request->filled('remember'))) {
            $request->session()->regenerate();

            // Berhasil login, lempar ke dashboard utama
            return redirect()->route('dashboard');
        }

        // Gagal login, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'login_identity' => 'Kredensial tidak cocok, atau Anda salah memilih opsi "Masuk Sebagai".',
        ])->withInput($request->only('login_identity', 'role'));
    }

    // 3. Proses Keluar Aplikasi (Logout)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}