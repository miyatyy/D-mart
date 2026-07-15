<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman formulir login asli
    public function showLogin() {
        return view('auth.login');
    }

    // 1. LOGIN FLEXIBLE: BISA PAKAI NIM ATAU EMAIL
    public function login(Request $request) {
        $request->validate([
            'login_input' => 'required|string',
            'password' => 'required',
        ]);

        // Cek apakah input berupa email atau NIM
        $fieldType = filter_var($request->login_input, FILTER_VALIDATE_EMAIL) ? 'email' : 'nim';

        $credentials = [
            $fieldType => $request->login_input,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/home');
        }

        return back()->withErrors(['login_input' => 'NIM/Email atau password salah!']);
    }

    // Menampilkan halaman formulir registrasi
    public function showRegister() {
        return view('auth.register');
    }

    // 2. REGISTER LANGSUNG MASUK DASHBOARD & ROLE DOSEN TANPA NIM
    public function register(Request $request) {
        $request->validate([
            'nim' => 'nullable|string|unique:users,nim', // Nullable agar dosen tidak wajib mengisi
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|string',
            'lokasi_kampus' => 'required|string',
        ]);

        // Jika dia mendaftar sebagai dosen, kosongkan NIM secara otomatis
        $nim = $request->role === 'dosen' ? null : $request->nim;

        $user = User::create([
            'nim' => $nim,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Di-hash dengan benar untuk sistem Auth Laravel
            'role' => $request->role,
            'lokasi_kampus' => $request->lokasi_kampus,
        ]);

        // Langsung otomatis loginkan user baru tersebut
        Auth::login($user);

        // Langsung pindahkan otomatis ke halaman dashboard utama
        return redirect('/home');
    }

    // Proses keluar akun dan pembersihan token session
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}