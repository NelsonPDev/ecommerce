@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-4xl font-bold text-gray-900">Bienvenido a TechStore</h1>
            <p class="mt-4 text-xl text-gray-600">Tu tienda online de tecnologia de confianza</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @auth
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-8">
                <p>
                    Bienvenido, <strong>{{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</strong>.
                    Rol: <strong>{{ Auth::user()->rol }}</strong>
                </p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('dashboard') }}" class="inline-block rounded bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                        Ir al dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                            Cerrar sesion
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-8">
                <p>
                    Por favor,
                    <a href="{{ route('login') }}" class="font-bold underline">inicia sesion</a>
                    para disfrutar de nuestras ofertas.
                </p>
            </div>
        @endauth

        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Sobre TechStore</h2>
            <p class="text-gray-600 mb-4">
                TechStore es tu aliado tecnologico. Ofrecemos dispositivos modernos, servicio local y entrega rapida.
                Queremos que comprar tecnologia sea facil y confiable.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 mb-3">Contacto</h2>
            <p class="text-gray-600">Correo: <strong>soporte@techstore.com</strong></p>
            <p class="text-gray-600">Telefono: <strong>+53 1234 5678</strong></p>
            <p class="text-gray-600">Direccion: Calle Principal 123, Ciudad</p>
            <p class="text-gray-600">Horario: Lun-Vie 9:00-18:00</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold text-gray-900">Nuestros Productos</h3>
                <p class="text-gray-600 mt-2">Contamos con una amplia variedad de productos tecnologicos de calidad.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold text-gray-900">Envios Rapidos</h3>
                <p class="text-gray-600 mt-2">Realizamos envios rapidos y seguros a todo el pais.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold text-gray-900">Soporte 24/7</h3>
                <p class="text-gray-600 mt-2">Estamos disponibles para resolver tus dudas en todo momento.</p>
            </div>
        </div>
    </div>
</div>
@endsection
