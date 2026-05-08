<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventController as EventAdminController;

// ==========================================
// RUTE PUBLIK (HALAMAN DEPAN)
// ==========================================
Route::get('/', function () { return view('welcome'); })->name('home');
Route::get('/profil', function () { return view('profil'); })->name('profil');
Route::get('/katalog', function () { return view('katalog'); })->name('katalog');
Route::get('/bantuan', function () { return view('bantuan'); })->name('bantuan');
Route::get('/kontak', function () { return view('kontak'); })->name('kontak');

Route::get('/event/detail', function () { return view('event-detail'); })->name('event.show');
Route::get('/checkout', function () { return view('checkout'); })->name('checkout');
Route::get('/ticket', function () { return view('ticket'); })->name('ticket');

// ==========================================
// RUTE ADMIN (DIGABUNG)
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () { 
        return view('admin.dashboard'); 
    })->name('dashboard');

    // Events (Menggunakan Resource Controller - Otomatis mencakup index, create, edit, dll)
    Route::resource('events', EventAdminController::class);

    // Transactions
    Route::get('/transactions', function () { 
        return view('admin.transactions'); 
    })->name('transactions');
    
    // Categories
    Route::get('/categories', function () { 
        return view('admin.categoris.index'); 
    })->name('categories.index');

});