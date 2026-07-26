<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Menampilkan Halaman Pendaftaran
     */
    public function showRegistrationForm()
    {
        return view('auth.user-register');
    }

    /**
     * Memproses Data Pendaftaran
     */
    public function register(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 2. Simpan user baru ke database
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Auto login setelah berhasil daftar
        Auth::login($user);

        // 4. Redirect ke halaman utama
        return redirect()->route('home')->with('success', 'Akun berhasil dibuat! Selamat datang.');
    }
}