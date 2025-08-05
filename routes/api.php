<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/refreshToken', [AuthController::class, 'refreshToken']);

Route::middleware('jwt.verify')->group(function () {
    Route::get('/auth/logout', [AuthController::class, 'logout']);
});





