@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-3xl font-bold mb-6">Editar Usuario</h2>

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <strong>Por favor, corrige los siguientes errores:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" class="w-full px-4 py-2 border border-gray-300 rounded @error('nombre') border-red-500 @enderror" required>
                        @error('nombre')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Apellidos</label>
                        <input type="text" name="apellidos" value="{{ old('apellidos', $usuario->apellidos) }}" class="w-full px-4 py-2 border border-gray-300 rounded @error('apellidos') border-red-500 @enderror">
                        @error('apellidos')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Correo Electrónico</label>
                        <input type="email" name="correo" value="{{ old('correo', $usuario->correo) }}" class="w-full px-4 py-2 border border-gray-300 rounded @error('correo') border-red-500 @enderror" required>
                        @error('correo')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Nueva Clave (Opcional)</label>
                        <input type="password" name="clave" class="w-full px-4 py-2 border border-gray-300 rounded @error('clave') border-red-500 @enderror" placeholder="Dejar en blanco para no cambiar">
                        @error('clave')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Rol</label>
                        <select name="rol" class="w-full px-4 py-2 border border-gray-300 rounded @error('rol') border-red-500 @enderror" required>
                            <option value="">-- Selecciona un rol --</option>
                            <option value="cliente" {{ old('rol', $usuario->rol) == 'cliente' ? 'selected' : '' }}>Cliente</option>
                            <option value="gerente" {{ old('rol', $usuario->rol) == 'gerente' ? 'selected' : '' }}>Gerente</option>
                            <option value="administrador" {{ old('rol', $usuario->rol) == 'administrador' ? 'selected' : '' }}>Administrador</option>
                        </select>
                        @error('rol')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Actualizar Usuario
                        </button>
                        <a href="{{ route('usuarios.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
