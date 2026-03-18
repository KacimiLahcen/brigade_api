<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PlatController;
use App\Http\Controllers\Api\IngredientController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//general routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//routes need token 
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Route::apiResource('categories', CategoryController::class);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/categories/{id}/plates', [CategoryController::class, 'getPlates']);

    Route::middleware('admin')->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
    });
    
    Route::get('/plates', [PlatController::class, 'index']);
    Route::get('/plates/{id}', [PlatController::class, 'show']);

    Route::middleware('admin')->group(function () {
        Route::post('/plates', [PlatController::class, 'store']);
        Route::post('/plates/{id}', [PlatController::class, 'update']); 
        Route::delete('/plates/{id}', [PlatController::class, 'destroy']);

            Route::apiResource('ingredients', IngredientController::class);

    });

});