<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PklController;

// User route
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// PKL API Routes
Route::apiResource('pkl', PklController::class);

// Optional: Protected PKL routes if you want to require authentication
// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('pkl', PklController::class);
// });
