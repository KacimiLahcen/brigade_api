<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PlatController;
use App\Http\Controllers\Api\IngredientController;
use App\Http\Controllers\Api\RecommendationsController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AdminController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Dietary profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    // Categories
    Route::get('/categories',              [CategoryController::class, 'index']);
    Route::get('/categories/{id}',         [CategoryController::class, 'show']);
    Route::get('/categories/{id}/plates',  [CategoryController::class, 'getPlates']);

    // Plates
    Route::get('/plates',      [PlatController::class, 'index']);
    Route::get('/plates/{id}', [PlatController::class, 'show']);

    // Recommendations
    Route::post('/recommendations/analyze/{plate_id}', [RecommendationsController::class, 'analyze']);
    Route::get('/recommendations',                     [RecommendationsController::class, 'index']);
    Route::get('/recommendations/{plate_id}',          [RecommendationsController::class, 'show']);

    // Admin only
    Route::middleware('admin')->group(function () {

        Route::post('/categories',        [CategoryController::class, 'store']);
        Route::put('/categories/{id}',    [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        Route::post('/plates',        [PlatController::class, 'store']);
        Route::post('/plates/{id}',   [PlatController::class, 'update']);
        Route::delete('/plates/{id}', [PlatController::class, 'destroy']);

        Route::apiResource('ingredients', IngredientController::class);

        Route::get('/admin/stats', [AdminController::class, 'stats']);
    });
});
