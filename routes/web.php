<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('deployment')->group(function(){
    Route::get('/migrate', function(){
        Artisan::call('migrate:fresh --seed');
    });
    Route::get('/link', function(){
        Artisan::call('storage:link');
    });
    Route::get('/swagger', function(){
        Artisan::call('l5-swagger:generate');
    });
});