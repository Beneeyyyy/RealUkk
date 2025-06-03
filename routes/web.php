<?php

use App\Http\Controllers\GuruDataController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\siswaAuth;
use App\Http\Controllers\guruAuth;
use App\Http\Controllers\SiswaDataController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');



// Siswa Dashboard & Data Management Routes
Route::middleware(['auth:siswa'])->group(function () {
    Route::get('siswa/dashboard', [SiswaDataController::class, 'dashboard'])->name('siswa.dashboard');
    
    // Industri routes
    Route::post('/industri', [SiswaDataController::class, 'storeIndustri']);
    Route::put('/industri/{industri}', [SiswaDataController::class, 'updateIndustri']);
    Route::delete('/industri/{industri}', [SiswaDataController::class, 'deleteIndustri']);
    
    // PKL routes
    Route::post('/pkl', [SiswaDataController::class, 'storePkl']);
    Route::put('/pkl/{pkl}', [SiswaDataController::class, 'updatePkl'])
        ->middleware(['auth', 'verified'])
        ->name('pkl.update');
    Route::delete('/pkl/{pkl}', [SiswaDataController::class, 'deletePkl']);
    
    Route::get('/industris', [SiswaDataController::class, 'getIndustris'])
        ->middleware(['auth', 'verified'])
        ->name('industris.index');
});



Route::middleware(['auth:guru'])->group(function () {
    Route::get('guru/dashboard', [GuruDataController::class, 'dashboard'])->name('guru.dashboard');
});



//Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('siswa/register', [SiswaAuth::class, 'create'])
        ->name('siswa.register');
    
    Route::post('siswa/register', [SiswaAuth::class, 'store']);
    
    Route::get('siswa/login', [SiswaAuth::class, 'login'])
        ->name('siswa.login');
    
    Route::post('siswa/login', [SiswaAuth::class, 'authenticate']);

    Route::get('guru/login', [GuruAuth::class, 'login'])
        ->name('guru.login');

    Route::post('guru/login', [GuruAuth::class, 'authenticate']);
});

Route::middleware('auth:siswa')->group(function () {
    Route::post('siswa/logout', [SiswaAuth::class, 'destroy'])
        ->name('siswa.logout');
});

Route::middleware('auth:guru')->group(function () {
    Route::post('/guru/logout', [GuruAuth::class, 'destroy'])
        ->name('guru.logout');
});




require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
