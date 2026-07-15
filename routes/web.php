<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - Campus 'D Mart
|--------------------------------------------------------------------------
*/

// 1. RUTE PUBLIK UTAMA (Membuka Katalog Toko Tanpa Blokade Login)
Route::get('/', [DashboardController::class, 'index'])->name('homepage');

Route::get('/katalog', function() {
    return redirect('/');
});

// 2. RUTE TAMU (Diakses Khusus Sebelum Otentikasi Sesi)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// 3. RUTE PROTEKSI INTERNAL SELLER, PEMBELI, & ADMIN OPERASIONAL
Route::middleware('auth')->group(function () {
    
    // Alur Pengarah Dasbor Dinamis
    Route::get('/home', [DashboardController::class, 'sellerDashboard'])->name('seller.dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- RUTE MODUL BARANG UTAMA ---
    Route::get('/product/create', [ProductController::class, 'create']);
    Route::post('/product/store', [App\Http\Controllers\ProductController::class, 'store']);    
    Route::delete('/product/delete-legacy/{id}', [ProductController::class, 'destroy']); // Cadangan fungsi bawaan jika diperlukan

    // --- RUTE UPDATE TRANSAKSI ---
    Route::post('/order/store', [OrderController::class, 'store']);
    Route::post('/order/update-status/{id}', [OrderController::class, 'updateStatus']);

    // --- RUTE CRUD UTAMA MANAJEMEN TENANT/SELLER DENGAN FOTO ---
    Route::get('/product/edit/{id}', [DashboardController::class, 'editProduct']);
    Route::put('/product/update/{id}', [DashboardController::class, 'updateProduct']);
    Route::delete('/product/delete/{id}', [DashboardController::class, 'deleteProduct']);

    Route::get('/api/sales-data', [App\Http\Controllers\DashboardController::class, 'getSalesChartData']);
    Route::post('/ad/store', [App\Http\Controllers\AdController::class, 'storeAd']);
Route::patch('/ad/stop/{id}', [App\Http\Controllers\AdController::class, 'stopAd']);

});