<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\OrderController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', [ProductController::class, 'index']);
Route::get('/warehouse', [WarehouseController::class, 'index']);

Route::get('/orders', [OrderController::class, 'index']);
Route::get('/stock-movements', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
Route::patch('/orders/{id}', [OrderController::class, 'update']);
Route::patch('/orders/cancel/{id}', [OrderController::class, 'cancel']);
Route::patch('/orders/complete/{id}', [OrderController::class, 'complete']);
Route::patch('/orders/restore/{id}', [OrderController::class, 'restore']);





