@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-3xl font-bold mb-6">Dashboard de Gerente</h2>
                <p class="text-lg text-gray-700">Usuario: <strong>{{ Auth::user()->name }}</strong></p>
                <p class="text-lg text-gray-700">Rol: <strong>{{ Auth::user()->role }}</strong></p>

                <div class="mt-8">
                    <h3 class="text-2xl font-semibold mb-4">Acciones Disponibles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <a href="{{ route('users.index') }}" class="bg-blue-600 text-white p-6 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
                            <h4 class="text-xl font-bold mb-2">Gestión de Usuarios</h4>
                            <p>Ver, crear, editar y eliminar usuarios del sistema.</p>
                        </a>
                        <!-- Aquí puedes agregar más tarjetas para otras funcionalidades -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
