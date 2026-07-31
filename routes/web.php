<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\PartnerController as PartnerAdminController;
use App\Http\Controllers\Admin\CategoryController as CategoryAdminController;
use App\Http\Controllers\Admin\TransactionController as TransactionAdminController;
use App\Http\Controllers\Admin\AuthController as AuthAdminController;
use App\Http\Controllers\Admin\DashboardController as DashboardAdminController;
use App\Http\Controllers\Admin\CheckinController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\MyTicketController;
use App\Http\Controllers\TicketController;

// ==========================================
// RUTE PUBLIK (HALAMAN DEPAN)
// ==========================================

// PERUBAHAN: Rute home sekarang diarahkan ke HomeController, bukan closure lagi
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/profil', function () { return view('profil'); })->name('profil');
Route::get('/katalog', [HomeController::class, 'katalog'])->name('katalog');
Route::get('/bantuan', function () { return view('bantuan'); })->name('bantuan');
Route::get('/kontak', function () { return view('kontak'); })->name('kontak');

Route::get('/event/{event}', [EventController::class, 'show'])->name('event.show');
Route::post('/event/{event}/reviews', [ReviewController::class, 'store'])->middleware('auth')->name('event.reviews.store');
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::get('/checkout-legacy/{event}', [CheckoutController::class, 'create'])->name('checkout');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/ticket/{order_id}', [TicketController::class, 'show'])->name('ticket.show');
Route::get('/ticket', function () { return view('ticket'); })->name('ticket');
Route::get('/my-ticket', [MyTicketController::class, 'index'])->middleware('auth')->name('my-ticket');
Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle']);

// Google Socialite SSO
Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// ==========================================
// RUTE ADMIN (DIGABUNG & DIPROTEKSI)
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Auth Rute (Bebas Akses)
    Route::get('/login', [AuthAdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthAdminController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthAdminController::class, 'logout'])->name('logout');

    // Panel Admin Terproteksi (Middleware auth & admin)
    Route::middleware(['auth', 'admin'])->group(function () {
        
        // Dashboard Admin (Diarahkan ke DashboardAdminController)
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

        // Events CRUD
        Route::resource('events', EventAdminController::class);

        // Partners CRUD
        Route::resource('partners', PartnerAdminController::class);

        // Categories CRUD
        Route::resource('categories', CategoryAdminController::class);

        // Transactions CRUD
        Route::resource('transactions', TransactionAdminController::class);
        
        // Bulk action untuk transaksi
        Route::post('/transactions/bulk-update', [TransactionAdminController::class, 'bulkUpdate'])->name('transactions.bulk-update');

        // Penjaga Pintu (Check-in Scanner)
        Route::get('/checkin', [CheckinController::class, 'index'])->name('checkin');
        Route::post('/checkin/verify', [CheckinController::class, 'verify'])->name('checkin.verify');
    });

});