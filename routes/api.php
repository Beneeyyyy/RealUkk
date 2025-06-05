<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PklController;
use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\GuruController;
use App\Http\Controllers\Api\IndustriController;

// User route
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// PKL API Routes
Route::apiResource('pkl', PklController::class);
Route::apiResource('siswa', SiswaController::class);
Route::apiResource('guru', GuruController::class);
Route::apiResource('industri', IndustriController::class);
// Optional: Protected PKL routes if you want to require authentication
// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('pkl', PklController::class);
// });
