<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShippingMethodController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\MetricsController;
use App\Http\Controllers\ProfileController;

Route::get('/health', function () {
    return ['status' => 'ok'];
});

// Metrics endpoint for Prometheus (public, no auth required)
Route::get('/metrics', [MetricsController::class, 'metrics']);

// Public auth routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Public product routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/shipping-methods', [ShippingMethodController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    Route::apiResource('cart', CartController::class);
    Route::apiResource('orders', OrderController::class);
    Route::get('/achievements', [AchievementController::class, 'index']);
});
