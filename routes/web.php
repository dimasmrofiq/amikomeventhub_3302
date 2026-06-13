<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\PartnerController as PartnerAdminController; 
use App\Http\Controllers\Admin\CategoryController as CategoryAdminController; 
use App\Http\Controllers\Admin\AuthController; 
use App\Http\Controllers\HomeController; 

// ==========================================
// RUTE PUBLIK (HALAMAN DEPAN)
// ==========================================


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/profil', function () { return view('profil'); })->name('profil');
Route::get('/katalog', function () { return view('katalog'); })->name('katalog');
Route::get('/bantuan', function () { return view('bantuan'); })->name('bantuan');
Route::get('/kontak', function () { return view('kontak'); })->name('kontak');

Route::get('/event/detail', function () { return view('event-detail'); })->name('event.show');
Route::get('/checkout', function () { return view('checkout'); })->name('checkout');
Route::get('/ticket', function () { return view('ticket'); })->name('ticket');


// ==========================================
// RUTE AUTHENTICATION (LOGIN ADMIN)
// ==========================================
// PERUBAHAN: Dikembalikan menjadi 'admin.login' agar cocok dengan bootstrap/app.php Anda
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');


// ==========================================
// RUTE ADMIN (DILINDUNGI MIDDLEWARE)
// ==========================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Rute Logout (hanya bisa diakses jika sudah login)
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

    // Transactions
    Route::get('/transactions', function () { 
        return view('admin.transactions'); 
    })->name('transactions');

});