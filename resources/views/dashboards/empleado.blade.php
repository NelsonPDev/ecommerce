@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                <h2 class="text-3xl font-bold mb-6">Dashboard de Empleado</h2>
                <p class="text-lg text-gray-700">Usuario: <strong>{{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</strong></p>
                <p class="text-lg text-gray-700">Rol: <strong>{{ Auth::user()->rol }}</strong></p>

                <div class="mt-8">
                    <h3 class="text-2xl font-semibold mb-4">Acciones Disponibles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <a href="{{ route('productos.index') }}" class="bg-blue-600 text-white p-6 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
                            <h4 class="text-xl font-bold mb-2">Ver Productos</h4>
                            <p>Explora el catálogo de productos disponibles.</p>
                        </a>
                        <a href="{{ route('productos.create') }}" class="bg-green-600 text-white p-6 rounded-lg shadow-md hover:bg-green-700 transition duration-300">
                            <h4 class="text-xl font-bold mb-2">Crear Producto</h4>
                            <p>Agrega nuevos productos al catálogo.</p>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="bg-purple-600 text-white p-6 rounded-lg shadow-md hover:bg-purple-700 transition duration-300">
                            <h4 class="text-xl font-bold mb-2">Mi Perfil</h4>
                            <p>Actualiza tu información personal.</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
