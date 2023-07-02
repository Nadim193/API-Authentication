<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Protected API routes
    // ...
});

// Public API routes

Route::get('/login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'loginsubmit'])->name('login');
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'registersubmit'])->name('register');