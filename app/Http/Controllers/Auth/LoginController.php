<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function showUserLoginForm()
    {
        return view('auth.user-login');
    }

    /**
     * Memproses Login Manual (Email & Password)
     */
    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Berhasil masuk ke akun!');
        }

        return back()->with('error', 'Email atau kata sandi yang Anda masukkan salah.');
    }

    /**
     * Redirect User ke Halaman Login Google (SSO)
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Memproses Data Callback Kembali dari Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari atau Buat User Baru Berdasarkan Email Google
            $user = User::updateOrCreate([
                'email' => $googleUser->getEmail(),
            ], [
                'name'      => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'password'  => bcrypt(Str::random(16)), // Password acak aman
            ]);

            // Auto Login User
            Auth::login($user);

            return redirect()->route('home')->with('success', 'Berhasil login menggunakan Google!');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login melalui Google. Silakan coba lagi.');
        }
    }

    /**
     * Logout User
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Berhasil keluar akun.');
    }
}
