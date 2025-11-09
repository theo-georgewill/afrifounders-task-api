<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;

// Login routes that need web guard for session management
Route::middleware(['web'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

//Api routes that need sanctum authentication
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', [AuthController::class, 'user']);
    Route::apiResource('tasks', TaskController::class);
});
