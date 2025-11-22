<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    // Auth
    Route::post('auth/register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('auth/login', [\App\Http\Controllers\AuthController::class, 'login']);

    // Guest (public read) endpoints
    Route::prefix('guest')->group(function () {
        Route::get('products', [\App\Http\Controllers\ProductController::class, 'index']);
        Route::get('products/{id}', [\App\Http\Controllers\ProductController::class, 'show']);

        Route::get('projects', [\App\Http\Controllers\ProjectController::class, 'index']);
        Route::get('projects/{id}', [\App\Http\Controllers\ProjectController::class, 'show']);

        Route::get('articles', [\App\Http\Controllers\ArticleController::class, 'index']);
        Route::get('articles/{id}', [\App\Http\Controllers\ArticleController::class, 'show']);
    });

    // Protected admin endpoints (require auth and permissions for create/delete)
    Route::prefix('admin')->middleware('auth:api')->group(function () {
        // Users - admin only for destructive or role changes
        Route::get('users', [\App\Http\Controllers\UserController::class, 'index']);
        Route::get('users/{id}', [\App\Http\Controllers\UserController::class, 'show']);
        Route::delete('users/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':manage users,api');
        Route::patch('users/role/{id}', [\App\Http\Controllers\UserController::class, 'updateRole'])->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':manage users,api');

        // Products - creation/deletion require permissions; updates require auth
        Route::post('products', [\App\Http\Controllers\ProductController::class, 'store'])->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':create products,api');
        Route::put('products/{id}', [\App\Http\Controllers\ProductController::class, 'update']);
        Route::delete('products/{id}', [\App\Http\Controllers\ProductController::class, 'destroy'])->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':delete products,api');

        // Projects
        Route::post('projects', [\App\Http\Controllers\ProjectController::class, 'store'])->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':create projects,api');
        Route::put('projects/{id}', [\App\Http\Controllers\ProjectController::class, 'update']);
        Route::delete('projects/{id}', [\App\Http\Controllers\ProjectController::class, 'destroy'])->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':delete projects,api');

        // Articles
        Route::post('articles', [\App\Http\Controllers\ArticleController::class, 'store'])->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':create articles,api');
        Route::put('articles/{id}', [\App\Http\Controllers\ArticleController::class, 'update']);
        Route::delete('articles/{id}', [\App\Http\Controllers\ArticleController::class, 'destroy'])->middleware(\Spatie\Permission\Middleware\PermissionMiddleware::class . ':delete articles,api');

        Route::get('chat/recent', [\App\Http\Controllers\ChatController::class, 'recent']);
    });
});
