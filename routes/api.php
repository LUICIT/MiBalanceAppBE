<?php

use App\Http\Controllers\api\AuthController;
use Illuminate\Support\Facades\Route;

Route::get("/status", function () {
    return Auth::guard('api')->check();
});


Route::group(['prefix' => 'v1/auth'], function () {

    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:30,1');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:20,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);     // revoca token actual
        Route::post('/logoutAll', [AuthController::class, 'logoutAll']); // revoca todos los tokens
    });

});


