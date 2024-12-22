<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DisasterController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;

// Rute autentikasi (untuk guest/belum login)
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

// Rute yang memerlukan autentikasi
Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    Route::get('/dashboard', [ReportController::class, 'index'])->name('dashboard');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    // CRUD Reports
    Route::resource('reports', ReportController::class);
    Route::resource('locations', LocationController::class);
});

// Route untuk GeoJSON
Route::get('/geojson/{filename}', function ($filename) {
    $path = public_path("geojson/{$filename}");
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
});