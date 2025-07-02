<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController; // <-- Impor controller kita
use App\Http\Controllers\Api\CustomerController; // <-- Impor controller customer

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Daftarkan API resource route untuk products
Route::apiResource('products', ProductController::class);
// Daftarkan API resource route untuk customers
Route::apiResource('customers', CustomerController::class)->except(['update', 'destroy', 'show']);