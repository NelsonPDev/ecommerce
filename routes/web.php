<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/{producto}', [ProductoController::class, 'show'])->name('productos.show');
Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
Route::get('/categorias/{categoria}', [CategoriaController::class, 'show'])->name('categorias.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/test-auth', function () {
        return 'Usuario autenticado: ' . Auth::user()->correo;
    })->name('test.auth');

    Route::get('/carrito', [VentaController::class, 'cart'])->name('carrito.index');
    Route::post('/carrito', [VentaController::class, 'addToCart'])->name('carrito.add');
    Route::patch('/carrito/{producto}', [VentaController::class, 'updateCart'])->name('carrito.update');
    Route::delete('/carrito/{producto}', [VentaController::class, 'removeFromCart'])->name('carrito.remove');
    Route::get('/carrito/checkout', [VentaController::class, 'checkout'])->name('carrito.checkout');
    Route::post('/carrito/checkout', [VentaController::class, 'processCheckout'])->name('carrito.process');

    Route::resource('usuarios', UsuarioController::class);
    Route::resource('categorias', CategoriaController::class)->except(['index', 'show']);
    Route::resource('productos', ProductoController::class)->except(['index', 'show']);
    Route::resource('ventas', VentaController::class);
});

Route::get('/mis-compras', [VentaController::class, 'index'])->middleware('auth')->name('ventas.mis-compras');
