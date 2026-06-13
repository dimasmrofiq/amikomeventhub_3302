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
       
        if (Auth::check() && Auth::user()->role === 'admin') {
           
            return $next($request);
        }

        // Jika belum login atau bukan admin, tendang/redirect kembali ke halaman login
        return redirect()->route('admin.login')->with('error', 'Anda harus login sebagai Admin untuk mengakses halaman ini.');
    }
}