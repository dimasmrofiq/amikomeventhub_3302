<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Mengizinkan jika user terautentikasi dan memiliki role 'superadmin' atau 'admin'
        if (Auth::check() && (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin')) {
            return $next($request);
        }

        // Jika belum login atau bukan admin/superadmin, kembalikan ke halaman login admin
        return redirect()->route('admin.login')->with('error', 'Anda harus login sebagai Admin untuk mengakses halaman ini.');
    }
}