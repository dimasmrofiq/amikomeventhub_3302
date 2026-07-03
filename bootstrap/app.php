<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // Mendaftarkan alias middleware admin
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // Beritahu Laravel ke mana tamu (guest) harus dilempar jika belum login
        $middleware->redirectGuestsTo(fn (Request $request) => route('admin.login'));
        
        // Membebaskan rute webhook Midtrans dari pengecekan CSRF Token
        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback'
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
    