<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();

            // Cek apakah user memiliki role superadmin atau organizer
            // Jika ya, izinkan masuk ke dashboard
            if ($user->role === 'superadmin' || $user->role === 'organizer') {
                return redirect()->intended('/admin/dashboard'); 
            }

            // Jika user biasa (peserta) mencoba login lewat halaman admin, tendang keluar!
            Auth::logout();
            return back()->with('error', 'Anda tidak memiliki hak akses untuk masuk ke panel Admin.');
        }

        return back()->with('error', 'Email atau Password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout(); 
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 
        
        return redirect('/'); 
    }
}