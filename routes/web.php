<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\PartnerController as PartnerAdminController;
use App\Http\Controllers\Admin\CategoryController as CategoryAdminController;
use App\Http\Controllers\Admin\TransactionController as TransactionAdminController;
use App\Http\Controllers\HomeController;

// ==========================================
// RUTE PUBLIK (HALAMAN DEPAN)
// ==========================================

// PERUBAHAN: Rute home sekarang diarahkan ke HomeController, bukan closure lagi
Route::get('/', [HomeController::class, 'index'])->name('home');

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
    
    // Dashboard Admin
    Route::get('/dashboard', function () { 
        return view('admin.dashboard'); 
    })->name('dashboard');

    
    Route::resource('events', EventAdminController::class);

    // Partners (Resource Controller untuk Modul Partner Admin)
    Route::resource('partners', PartnerAdminController::class);

    // Categories (PERUBAHAN: Menggunakan Resource Controller agar seluruh route CRUD Kategori aktif otomatis)
    Route::resource('categories', CategoryAdminController::class);

    // Transactions (Resource Controller untuk CRUD Transaksi)
    Route::resource('transactions', TransactionAdminController::class);
    
    // Bulk action untuk transaksi
    Route::post('/transactions/bulk-update', [TransactionAdminController::class, 'bulkUpdate'])->name('transactions.bulk-update');

});