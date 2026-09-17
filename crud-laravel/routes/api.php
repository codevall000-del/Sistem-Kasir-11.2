<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DataController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;

// Route Bawaan Laravel
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route CRUD Data (Tambahkan di bawah ini)
Route::apiResource('data', DataController::class); // Mengambil Data


Route::post('/login', [AuthController::class, 'login']); // Login


Route::get('/barang', [BarangController::class, 'index']); 
Route::post('/barang', [BarangController::class, 'store']);
Route::delete('/barang/{id}', [BarangController::class, 'destroy']);
Route::post('/transaksi', [TransaksiController::class, 'store']);
Route::put('/barang/{id}', [BarangController::class, 'update']); 
Route::get('/transaksi', [TransaksiController::class, 'index']);
 