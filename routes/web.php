<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SiswaAuth;
use App\Http\Controllers\GuruAuth;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');



Route::get('siswa/dashboard', function () {
    return Inertia::render('Siswa Dashboard');
})->middleware(['auth:siswa'])->name('siswa.dashboard');

Route::get('guru/dashboard', function () {
    return Inertia::render('Guru Dashboard');
})->middleware(['auth:guru'])->name('guru.dashboard');



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



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
