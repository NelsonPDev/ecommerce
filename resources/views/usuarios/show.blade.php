@extends('layouts.app')

@section('content')
    <section class="rounded-3xl bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-bold">{{ $usuario->nombre }} {{ $usuario->apellidos }}</h1>
        <dl class="mt-6 grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl bg-slate-50 p-4">
                <dt class="text-sm font-semibold text-slate-500">Correo</dt>
                <dd class="mt-1 text-lg">{{ $usuario->correo }}</dd>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <dt class="text-sm font-semibold text-slate-500">Rol</dt>
                <dd class="mt-1 text-lg">{{ $usuario->rol }}</dd>
            </div>
        </dl>

        @can('update', $usuario)
            <a href="{{ route('usuarios.edit', $usuario) }}" class="mt-6 inline-block rounded-lg px-4 py-2 text-sm font-semibold text-white" style="background-color: #f59e0b; border: 1px solid #d97706;">
                Editar usuario
            </a>
        @endcan
    </section>
@endsection
