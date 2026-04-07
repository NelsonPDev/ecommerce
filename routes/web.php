<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

// Rutas públicas (sin autenticación)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Productos públicos (puede ver cualquiera)
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/{producto}', [ProductoController::class, 'show'])->name('productos.show');

// Rutas de autenticación manual
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('test.auth')->name('logout');

// Rutas autenticadas
Route::middleware('test.auth')->group(function () {
    // Dashboard dinámico según rol
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Ruta de prueba para verificar middleware
    Route::get('/test-auth', function() {
        return 'Usuario autenticado: ' . Auth::user()->correo;
    })->middleware('test.auth')->name('test.auth');
    
    // Gestión de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestión de usuarios (protegido por Policy en el controlador)
    Route::resource('usuarios', UsuarioController::class);

    // Gestión de productos (protegido por Policy en el controlador)
    Route::resource('productos', ProductoController::class)->except(['index', 'show']);

    // Gestión de ventas/compras (solo clientes)
    Route::get('/mis-compras', [VentaController::class, 'index'])->name('ventas.index');
    Route::post('/comprar', [VentaController::class, 'comprar'])->name('ventas.comprar');
    Route::get('/compras/{venta}', [VentaController::class, 'show'])->name('ventas.show');
    Route::post('/compras/{venta}/cancelar', [VentaController::class, 'cancelar'])->name('ventas.cancelar');
});
