<?php

use App\Http\Controllers\DecisionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::apiResource('decisions', DecisionController::class);

Route::post('decisions/{decision}/review', [ReviewController::class, 'store']);
Route::get('calibration', [ReviewController::class, 'calibration']);

Route::get('tags', [TagController::class, 'index']);