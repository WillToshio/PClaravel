<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});

// versão 1 do api
Route::prefix('v1')->group(function (){

    // prefixo para a autenticação
    Route::prefix('auth')->group(function (){
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/forgot-password', [AuthController::class, 'forgot-password']);

        Route::middleware(['auth:sanctum', 'check.active'])->group(function (){

            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
        });
    });


});

