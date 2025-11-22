<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    // Auth
    Route::post('auth/register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('auth/login', [\App\Http\Controllers\AuthController::class, 'login']);

    // Users (protected)
    Route::middleware('auth:api')->group(function () {
        Route::get('users', [\App\Http\Controllers\UserController::class, 'index']);
        Route::get('users/{id}', [\App\Http\Controllers\UserController::class, 'show']);
        Route::delete('users/{id}', [\App\Http\Controllers\UserController::class, 'destroy']);
        Route::patch('users/role/{id}', [\App\Http\Controllers\UserController::class, 'updateRole']);

        // Products, projects, articles endpoints can be accessed here (stubs exist)
        Route::post('products', [\App\Http\Controllers\ProductController::class, 'store']);
        Route::get('products', [\App\Http\Controllers\ProductController::class, 'index']);
        Route::get('products/{id}', [\App\Http\Controllers\ProductController::class, 'show']);
        Route::put('products/{id}', [\App\Http\Controllers\ProductController::class, 'update']);
        Route::delete('products/{id}', [\App\Http\Controllers\ProductController::class, 'destroy']);

        Route::post('projects', [\App\Http\Controllers\ProjectController::class, 'store']);
        Route::get('projects', [\App\Http\Controllers\ProjectController::class, 'index']);
        Route::get('projects/{id}', [\App\Http\Controllers\ProjectController::class, 'show']);
        Route::put('projects/{id}', [\App\Http\Controllers\ProjectController::class, 'update']);
        Route::delete('projects/{id}', [\App\Http\Controllers\ProjectController::class, 'destroy']);

        Route::post('articles', [\App\Http\Controllers\ArticleController::class, 'store']);
        Route::get('articles', [\App\Http\Controllers\ArticleController::class, 'index']);
        Route::get('articles/{id}', [\App\Http\Controllers\ArticleController::class, 'show']);
        Route::put('articles/{id}', [\App\Http\Controllers\ArticleController::class, 'update']);
        Route::delete('articles/{id}', [\App\Http\Controllers\ArticleController::class, 'destroy']);

        Route::get('chat/recent', [\App\Http\Controllers\ChatController::class, 'recent']);
    });
});
