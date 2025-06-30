<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\AdminController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Test auth debug
Route::middleware(['auth:sanctum'])->get('/debug-auth', function () {
    return response()->json([
        'user' => auth()->user(),
        'session' => session()->all(),
    ]);
});

// Authenticated user routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/tasks/stats', [TaskController::class, 'stats']);
    Route::patch('/tasks/reorder', [TaskController::class, 'reorder']);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
    Route::apiResource('tasks', TaskController::class);
});

// Admin routes
Route::middleware(['auth:sanctum', 'check.admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});
