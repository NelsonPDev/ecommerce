@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-3xl font-bold mb-6">Crear Nuevo Usuario</h2>

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

                <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Nombre Completo</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded @error('name') border-red-500 @enderror" required>
                        @error('name')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Correo Electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded @error('email') border-red-500 @enderror" required>
                        @error('email')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Contraseña</label>
                        <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded @error('password') border-red-500 @enderror" required>
                        @error('password')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Rol</label>
                        <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded @error('role') border-red-500 @enderror" required>
                            <option value="">-- Selecciona un rol --</option>
                            <option value="cliente" {{ old('role') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                            <option value="empleado" {{ old('role') == 'empleado' ? 'selected' : '' }}>Empleado</option>
                            <option value="gerente" {{ old('role') == 'gerente' ? 'selected' : '' }}>Gerente</option>
                        </select>
                        @error('role')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Crear Usuario
                        </button>
                        <a href="{{ route('users.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
