<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController; 
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;


// 1. Rute Publik (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// 2. Rute untuk SEMUA yang sudah login (Admin & User)
// Saya gabungkan rute dashboard dan profile Anda ke satu grup agar lebih rapi
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])
     ->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // (Nanti rute KASIR dan ANTRIAN PESANAN bisa ditaruh di sini,
    // karena Admin dan User sama-sama bisa akses)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index'); // Antrian Pesanan
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create'); // Halaman Kasir
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store'); // Simpan Pesanan
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show'); // Detail Pesanan
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/pay', [OrderController::class, 'markAsPaid'])->name('orders.pay'); // Tandai Lunas
});


// 3. Rute KHUSUS ADMIN (Ini adalah bagian BARU yang Anda tambahkan)
// Rute di dalam grup ini HANYA bisa diakses oleh user dengan role == 'admin'
Route::middleware(['auth', 'admin'])->group(function () {
    
    // Ini adalah rute untuk CRUD Kategori
    Route::resource('/categories', CategoryController::class);
    Route::resource('/materials', MaterialController::class);
    Route::resource('/products', ProductController::class);

    Route::get('/production', [ProductionController::class, 'index'])->name('production.index'); // Menampilkan form
    Route::post('/production', [ProductionController::class, 'store'])->name('production.store'); // Memproses form

    Route::resource('/users', UserController::class);

    Route::get('/orders/export/excel', function () {
        // Perintah ini akan memicu download file
        return Excel::download(new OrderExport, 'laporan-pesanan.xlsx');
    })->name('orders.export.excel');

    Route::get('/orders/export/csv', [App\Http\Controllers\OrderController::class, 'exportCsv'])->name('orders.export.csv');
});


// Ini file bawaan Breeze, biarkan di paling bawah
require __DIR__.'/auth.php';