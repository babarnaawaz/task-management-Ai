<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SubtaskController;
use App\Http\Controllers\Api\TaskBreakdownController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Tasks
    Route::apiResource('tasks', TaskController::class);
    
    // Subtasks
    Route::apiResource('tasks.subtasks', SubtaskController::class)
        ->shallow()
        ->except(['show']);

    // Task Breakdown
    Route::post('/tasks/{task}/breakdown', [TaskBreakdownController::class, 'store']);
});