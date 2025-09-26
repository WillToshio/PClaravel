<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// versão 1 do api
Route::prefix('v1')->group(function (){

    // prefixo para a autenticação
    Route::prefix('auth')->group(function (){
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/forgot-password', [AuthController::class, 'forgot-password']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum', 'check.active');
        Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum', 'check.active');
    });


});
