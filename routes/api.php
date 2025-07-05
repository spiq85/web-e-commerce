<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\User;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Autentikasi Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Produk Routes
Route::get('/products', [ProductController::class, 'index']);
// Details Produk
Route::get('/products/{id}', [ProductController::class, 'show']);

// Categories Routes
Route::get('/categories', [CategoryController::class, 'index']);
// Details Categories
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// Autentikasi API Routes
Route::middleware('auth:sanctum')->group(function(){
    // Logout User
    Route::post('/logout', [AuthController::class, 'logout']);

    // User Spesifik Routes
    Route::post('/orders', [OrderController::class, 'store'])->middleware('can:isUser');
    // Melihat Riwayat Pesanan
    Route::get('/orders', [OrderController::class, 'index']);
    // Melihat Detail Pesanan
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    // Admin Spesifik Routes
    Route::middleware('can:isAdmin')->group(function(){
        // CRUD Products
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // Manage Users
        Route::get('/admin/users', [UserController::class,'index']);
        Route::get('/admin/users/pending', [UserController::class, 'getPendingUsers']);
        Route::patch('admin/users/{id}/activate', [UserController::class, 'activateUser']);
        Route::patch('admin/users/{id}/deactivate', [UserController::class, 'deactivateUser']);
        Route::delete('/admin/users/{id}', [UserController::class, 'destroy']);

        // Manage Orders
        Route::patch('admin/orders/{id}/status', [OrderController::class, 'updateStatus']);
    });

});
