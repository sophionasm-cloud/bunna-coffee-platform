<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ListingController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\InvestmentController;
use App\Http\Controllers\Api\UserController;

// ─── PUBLIC ROUTES ──────────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::get('/listings', [ListingController::class, 'index']);
Route::get('/listings/{id}', [ListingController::class, 'show']);

Route::get('/opportunities', [InvestmentController::class, 'index']);
Route::get('/opportunities/{id}', [InvestmentController::class, 'show']);

Route::post('/contact', [ContactController::class, 'store']);
Route::get('/test', function () {
    return ['message' => 'API is working!'];
});

// ─── AUTHENTICATED ROUTES ───────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [UserController::class, 'update']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markRead']);
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead']);

    Route::middleware('role:farmer|admin')->group(function () {
        Route::post('/listings', [ListingController::class, 'store']);
        Route::put('/listings/{id}', [ListingController::class, 'update']);
        Route::delete('/listings/{id}', [ListingController::class, 'destroy']);
    });

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/stats', [AdminController::class, 'stats']);
        Route::get('/users', [AdminController::class, 'users']);
        Route::patch('/users/{id}/role', [AdminController::class, 'updateRole']);
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
        Route::patch('/users/{id}/toggle', [AdminController::class, 'toggleUser']);
        Route::get('/products', [ProductController::class, 'adminIndex']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        Route::get('/orders', [OrderController::class, 'adminIndex']);
        Route::patch('/orders/{id}/status', [OrderController::class, 'adminUpdateStatus']);
        Route::patch('/listings/{id}/approve', [ListingController::class, 'approve']);
        Route::patch('/listings/{id}/reject', [ListingController::class, 'reject']);
        Route::post('/opportunities', [InvestmentController::class, 'store']);
        Route::put('/opportunities/{id}', [InvestmentController::class, 'update']);
        Route::delete('/opportunities/{id}', [InvestmentController::class, 'destroy']);
    });
});