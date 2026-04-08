@extends('layouts.app')

@section('content')
    <section class="rounded-3xl bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-bold">Editar usuario</h1>
        <form method="POST" action="{{ route('usuarios.update', $usuario) }}" class="mt-8 grid gap-5 md:grid-cols-2">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-2 block text-sm font-semibold">Nombre</label>
                <input name="nombre" type="text" value="{{ old('nombre', $usuario->nombre) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Apellidos</label>
                <input name="apellidos" type="text" value="{{ old('apellidos', $usuario->apellidos) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
            </div>
            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold">Correo</label>
                <input name="correo" type="email" value="{{ old('correo', $usuario->correo) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Nueva clave</label>
                <input name="clave" type="password" class="w-full rounded-xl border border-slate-300 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Confirmar nueva clave</label>
                <input name="clave_confirmation" type="password" class="w-full rounded-xl border border-slate-300 px-4 py-3">
            </div>
            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold">Rol</label>
                <select name="rol" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
                    @foreach ($rolesDisponibles as $valor => $etiqueta)
                        <option value="{{ $valor }}" @selected(old('rol', $usuario->rol) === $valor)>{{ $etiqueta }}</option>
                    @endforeach
                </select>
                @if (auth()->user()->esGerente())
                    <p class="mt-2 text-sm text-slate-500">Como gerente solo puedes editar usuarios con rol cliente.</p>
                @endif
            </div>
            <div class="md:col-span-2 mt-2 flex flex-wrap items-center gap-3">
                <button type="submit" class="rounded-lg px-5 py-3 text-sm font-semibold text-white" style="background-color: #0f172a; border: 1px solid #0f172a;">
                    Actualizar
                </button>
                <a href="{{ route('usuarios.index') }}" class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700">Cancelar</a>
                @can('delete', $usuario)
                    <button
                        type="submit"
                        form="delete-user-form"
                        class="rounded-lg px-5 py-3 text-sm font-semibold text-white"
                        style="background-color: #dc2626; border: 1px solid #b91c1c;"
                        onclick="return confirm('Deseas eliminar este usuario?')"
                    >
                        Eliminar usuario
                    </button>
                @endcan
            </div>
        </form>

        @can('delete', $usuario)
            <form id="delete-user-form" method="POST" action="{{ route('usuarios.destroy', $usuario) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endcan
    </section>
@endsection
