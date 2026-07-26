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

use App\Http\Controllers\ReviewController; 
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;


/*
|--------------------------------------------------------------------------
| 1. RUTE PUBLIK (HALAMAN DEPAN & CHECKOUT)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/profil', function () { return view('profil'); })->name('profil');
Route::get('/katalog', function () { return view('katalog'); })->name('katalog');
Route::get('/bantuan', function () { return view('bantuan'); })->name('bantuan');
Route::get('/kontak', function () { return view('kontak'); })->name('kontak');

Route::get('/event/detail', function () { return view('event-detail'); })->name('event.show');

// Rute Checkout & Integrasi Midtrans

Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');

Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');


Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');

Route::get('/payment/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');

Route::post('/midtrans/callback', [CheckoutController::class, 'callback']);

// Rute Tiket
Route::get('/ticket', [TicketController::class, 'index'])->name('ticket.index');


/*
|--------------------------------------------------------------------------
| 2. RUTE AUTHENTICATION USER (PESERTA & GOOGLE SSO)
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showUserLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'loginUser']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Google SSO User
Route::get('/auth/google', [LoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback']);


/*
|--------------------------------------------------------------------------
| 3. RUTE USER TERAUTENTIKASI (FITUR ULASAN & RATING)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/event/{event}/review', [ReviewController::class, 'store'])->name('review.store');
});


/*
|--------------------------------------------------------------------------
| 4. RUTE AUTHENTICATION ADMIN & ORGANIZER
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');

// Route Pendaftaran User
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');


/*
|--------------------------------------------------------------------------
| 5. RUTE BERSAMA (SUPERADMIN & ORGANIZER) - FITUR 3
|--------------------------------------------------------------------------
| Menggunakan middleware 'organizer' (yang juga mengizinkan superadmin).
| Organizer bisa mengakses Dashboard, Event, dan Logout.
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'organizer'])->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard 
    Route::get('/dashboard', function () { 
        return view('admin.dashboard'); 
    })->name('dashboard');

    // Master Data Event (Controller sudah mengatur agar HIMA hanya melihat event-nya sendiri)
    Route::resource('events', EventAdminController::class);

    // Transaksi Admin & Organizer
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions');

});


/*
|--------------------------------------------------------------------------
| 6. RUTE KHUSUS SUPERADMIN
|--------------------------------------------------------------------------
| Menggunakan middleware 'admin' murni. Organizer tidak akan bisa mengakses ini.
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Master Data Khusus Superadmin
    Route::resource('partners', PartnerAdminController::class);
    
    Route::resource('categories', CategoryAdminController::class);



});
