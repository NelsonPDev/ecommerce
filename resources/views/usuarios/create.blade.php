@extends('layouts.app')

@section('content')
    <section class="rounded-3xl bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-bold">Crear usuario</h1>
        <form method="POST" action="{{ route('usuarios.store') }}" class="mt-8 grid gap-5 md:grid-cols-2">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-semibold">Nombre</label>
                <input name="nombre" type="text" value="{{ old('nombre') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Apellidos</label>
                <input name="apellidos" type="text" value="{{ old('apellidos') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
            </div>
            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold">Correo</label>
                <input name="correo" type="email" value="{{ old('correo') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Clave</label>
                <input name="clave" type="password" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Confirmar clave</label>
                <input name="clave_confirmation" type="password" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
            </div>
            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold">Rol</label>
                <p class="mb-3 text-sm text-slate-500">Como administrador puedes asignar cualquier rol al usuario.</p>
                <select name="rol" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
                    <option value="">Selecciona un rol</option>
                    <option value="administrador" @selected(old('rol') === 'administrador')>Administrador</option>
                    <option value="gerente" @selected(old('rol') === 'gerente')>Gerente</option>
                    <option value="cliente" @selected(old('rol') === 'cliente')>Cliente</option>
                </select>
            </div>
            <div class="md:col-span-2 flex gap-3">
                <button type="submit" class="rounded-lg px-5 py-3 text-sm font-semibold text-white" style="background-color: #0f172a; border: 1px solid #0f172a;">
                    Guardar
                </button>
                <a href="{{ route('usuarios.index') }}" class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
