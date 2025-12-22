<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// PENTING: Kita panggil Controller yang ada di dalam folder Api
use App\Http\Controllers\Api\ProductController as ApiProductController; 

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// --- TUGAS API TOKO AYAM ---

// 1. GET Semua Produk
Route::get('/products', [ApiProductController::class, 'index']);

// 2. POST Tambah Produk
Route::post('/products', [ApiProductController::class, 'store']);

// 3. DELETE Hapus Produk
Route::delete('/products/{id}', [ApiProductController::class, 'destroy']);