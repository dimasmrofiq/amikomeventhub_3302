<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\PartnerController as PartnerAdminController; 
use App\Http\Controllers\Admin\CategoryController as CategoryAdminController; 
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController; 
use App\Http\Controllers\Admin\AuthController; 
use App\Http\Controllers\HomeController; 
use App\Http\Controllers\CheckoutController; 
use App\Http\Controllers\TicketController;   

// ==========================================
// RUTE PUBLIK (HALAMAN DEPAN)
// ==========================================

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/profil', function () { return view('profil'); })->name('profil');
Route::get('/katalog', function () { return view('katalog'); })->name('katalog');
Route::get('/bantuan', function () { return view('bantuan'); })->name('bantuan');
Route::get('/kontak', function () { return view('kontak'); })->name('kontak');

Route::get('/event/detail', function () { return view('event-detail'); })->name('event.show');

// Rute checkout dinamis menggunakan CheckoutController
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');

// --- RUTE INTEGRASI MIDTRANS ---
// Menampilkan halaman tombol snap pembayaran midtrans
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
// Menangani redirect jika pembayaran berhasil
Route::get('/payment/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');

// Rute sukses tiket ke TicketController (bawaan modul lama)
Route::get('/ticket', [TicketController::class, 'index'])->name('ticket.index');


// ==========================================
// RUTE AUTHENTICATION (LOGIN ADMIN)
// ==========================================

Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');


// ==========================================
// RUTE ADMIN (DILINDUNGI MIDDLEWARE)
// ==========================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Rute Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Admin
    Route::get('/dashboard', function () { 
        return view('admin.dashboard'); 
    })->name('dashboard');

    // Events (Resource Controller)
    Route::resource('events', EventAdminController::class);

    // Partners (Resource Controller)
    Route::resource('partners', PartnerAdminController::class);

    // Categories (Resource Controller)
    Route::resource('categories', CategoryAdminController::class);

    // FIX DI SINI: Nama rute dikembalikan menjadi 'transactions' agar pas 
    // dengan pemanggilan route('admin.transactions') di dashboard/sidebar Anda.
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions');

    Route::get('/success/{order_id}', [
    \App\Http\Controllers\CheckoutController::class, 
    'success'
])->name('checkout.success');

});