@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-3xl font-bold mb-6">Dashboard de Gerente</h2>
                <p class="text-lg text-gray-700">Usuario: <strong>{{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</strong></p>
                <p class="text-lg text-gray-700">Rol: <strong>{{ Auth::user()->rol }}</strong></p>

                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        Cerrar sesion
                    </button>
                </form>

                <div class="mt-8">
                    <h3 class="text-2xl font-semibold mb-4">Acciones Disponibles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <a href="{{ route('usuarios.index') }}" class="bg-blue-600 text-white p-6 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
                            <h4 class="text-xl font-bold mb-2">Editar Clientes</h4>
                            <p>Accede al modulo de usuarios para editar solamente clientes.</p>
                        </a>
                        <a href="{{ route('productos.index') }}" class="bg-green-600 text-white p-6 rounded-lg shadow-md hover:bg-green-700 transition duration-300">
                            <h4 class="text-xl font-bold mb-2">Gestion de Productos</h4>
                            <p>Ver y administrar productos propios.</p>
                        </a>
                        <a href="{{ route('categorias.index') }}" class="bg-amber-500 text-white p-6 rounded-lg shadow-md hover:bg-amber-600 transition duration-300">
                            <h4 class="text-xl font-bold mb-2">Gestion de Categorias</h4>
                            <p>Crear y editar categorias disponibles.</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
