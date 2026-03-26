<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Rutas públicas (sin autenticación)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Rutas autenticadas
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard dinámico según rol
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gestión de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas CRUD de usuarios (solo para gerentes)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
