@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold">Gestión de Usuarios</h2>
                    <a href="{{ route('usuarios.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Crear Nuevo Usuario
                    </a>
                </div>

                @if($message = Session::get('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ $message }}
                    </div>
                @endif

                @if(count($usuarios) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border border-gray-300 p-3 text-left">ID</th>
                                    <th class="border border-gray-300 p-3 text-left">Nombre</th>
                                    <th class="border border-gray-300 p-3 text-left">Apellidos</th>
                                    <th class="border border-gray-300 p-3 text-left">Correo</th>
                                    <th class="border border-gray-300 p-3 text-left">Rol</th>
                                    <th class="border border-gray-300 p-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuarios as $usuario)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border border-gray-300 p-3">{{ $usuario->id }}</td>
                                        <td class="border border-gray-300 p-3">{{ $usuario->nombre }}</td>
                                        <td class="border border-gray-300 p-3">{{ $usuario->apellidos }}</td>
                                        <td class="border border-gray-300 p-3">{{ $usuario->correo }}</td>
                                        <td class="border border-gray-300 p-3">
                                            <span class="px-3 py-1 rounded text-white text-sm font-bold
                                                @if($usuario->rol == 'administrador') bg-red-600
                                                @elseif($usuario->rol == 'gerente') bg-orange-600
                                                @else bg-green-600
                                                @endif">
                                                {{ ucfirst($usuario->rol) }}
                                            </span>
                                        </td>
                                        <td class="border border-gray-300 p-3 text-center">
                                            <a href="{{ route('usuarios.show', $usuario->id) }}" class="text-blue-600 hover:underline mr-3">Ver</a>
                                            <a href="{{ route('usuarios.edit', $usuario->id) }}" class="text-yellow-600 hover:underline mr-3">Editar</a>
                                            <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                        No hay usuarios registrados aún.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
