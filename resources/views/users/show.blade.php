@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-3xl font-bold mb-6">Detalles del Usuario</h2>

                <div class="space-y-6">
                    <div class="border-b pb-4">
                        <label class="block text-gray-700 font-bold mb-2">Nombre Completo</label>
                        <p class="text-gray-900">{{ $user->name }}</p>
                    </div>

                    <div class="border-b pb-4">
                        <label class="block text-gray-700 font-bold mb-2">Correo Electrónico</label>
                        <p class="text-gray-900">{{ $user->email }}</p>
                    </div>

                    <div class="border-b pb-4">
                        <label class="block text-gray-700 font-bold mb-2">Rol</label>
                        <p>
                            <span class="px-3 py-1 rounded text-white font-bold
                                @if($user->role == 'gerente') bg-red-600
                                @elseif($user->role == 'empleado') bg-orange-600
                                @else bg-green-600
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </p>
                    </div>

                    <div class="border-b pb-4">
                        <label class="block text-gray-700 font-bold mb-2">Registrado el</label>
                        <p class="text-gray-900">{{ $user->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Última actualización</label>
                        <p class="text-gray-900">{{ $user->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>

                    <div class="flex gap-4 mt-8">
                        <a href="{{ route('users.edit', $user->id) }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Editar
                        </a>
                        <a href="{{ route('users.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500">
                            Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
