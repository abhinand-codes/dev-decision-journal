<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DecisionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('decisions', DecisionController::class);
    Route::post('decisions/{decision}/review', [ReviewController::class, 'store']);
    Route::get('calibration', [ReviewController::class, 'calibration']);
    Route::get('tags', [TagController::class, 'index']);
    Route::post('logout', [AuthController::class, 'logout']);
});