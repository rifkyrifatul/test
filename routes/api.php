<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController; // <-- Impor controller kita
use App\Http\Controllers\Api\CustomerController; // <-- Impor controller customer
use App\Http\Controllers\Api\SaleController; // <-- Impor controller sales

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Daftarkan API resource route untuk products
Route::apiResource('products', ProductController::class);
// Daftarkan API resource route untuk customers
Route::apiResource('customers', CustomerController::class)->except(['update', 'destroy', 'show']);

// Tambahkan route untuk Sales
Route::post('/orders', [SaleController::class, 'store']); // Sesuai dokumen: POST /api/orders 
Route::get('/orders', [SaleController::class, 'index']); // Sesuai dokumen: GET /api/orders 