@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-3xl font-bold mb-6">Dashboard de Empleado</h2>
                <p class="text-lg text-gray-700">Usuario: <strong>{{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</strong></p>
                <p class="text-lg text-gray-700">Rol: <strong>{{ Auth::user()->rol }}</strong></p>
                <p class="text-lg text-gray-700">Correo: <strong>{{ Auth::user()->correo }}</strong></p>

                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4">Acciones disponibles:</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('productos.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Ver Productos
                        </a>
                        <a href="{{ route('productos.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Crear Producto
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
