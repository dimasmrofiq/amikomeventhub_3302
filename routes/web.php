<?php

use Illuminate\Support\Facades\Route;

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
// RUTE ADMIN
// ==========================================
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
    Route::get('/events', function () { return view('admin.events'); })->name('events.index');
    Route::get('/transactions', function () { return view('admin.transactions'); })->name('transactions');
    
    // Rute Kategori (Mengarah ke folder 'categoris' sesuai di gambar Anda)
    Route::get('/categories', function () { return view('admin.categoris.index'); })->name('categories.index');
});