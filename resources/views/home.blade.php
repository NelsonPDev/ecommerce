@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header Hero -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-4xl font-bold text-gray-900">Bienvenido a TechStore</h1>
            <p class="mt-4 text-xl text-gray-600">Tu tienda online de tecnología de confianza</p>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if(Auth::check())
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-8">
                <p>Bienvenido, <strong>{{ Auth::user()->name }}</strong>. Rol: <strong>{{ Auth::user()->role }}</strong></p>
            </div>
        @else
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-8">
                <p>Por favor, <a href="{{ route('login') }}" class="font-bold underline">inicia sesión</a> para disfrutar de nuestras ofertas.</p>
            </div>
        @endif

        <!-- Información simple integrada de About y Contact -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Sobre TechStore</h2>
            <p class="text-gray-600 mb-4">TechStore es tu aliado tecnológico. Ofrecemos dispositivos modernos, servicio local y entrega rápida. Queremos que comprar tecnología sea fácil y confiable.</p>

            <h2 class="text-2xl font-bold text-gray-900 mb-3">Contacto</h2>
            <p class="text-gray-600">Correo: <strong>soporte@techstore.com</strong></p>
            <p class="text-gray-600">Teléfono: <strong>+53 1234 5678</strong></p>
            <p class="text-gray-600">Dirección: Calle Principal 123, Ciudad</p>
            <p class="text-gray-600">Horario: Lun-Vie 9:00-18:00</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Tarjeta Productos -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold text-gray-900">Nuestros Productos</h3>
                <p class="text-gray-600 mt-2">Contamos con una amplia variedad de productos tecnológicos de calidad.</p>
            </div>

            <!-- Tarjeta Envíos -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold text-gray-900">Envíos Rápidos</h3>
                <p class="text-gray-600 mt-2">Realizamos envíos rápidos y seguros a todo el país.</p>
            </div>

            <!-- Tarjeta Soporte -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold text-gray-900">Soporte 24/7</h3>
                <p class="text-gray-600 mt-2">Estamos disponibles para resolver tus dudas en todo momento.</p>
            </div>
        </div>
    </div>
</div>
@endsection
