@extends('layouts.app')

@section('content')
    <section class="rounded-3xl bg-white p-8 shadow-sm">
        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-cyan-700">{{ auth()->user()->rol }}</p>
        <h1 class="mt-3 text-3xl font-bold">Bienvenido, {{ auth()->user()->nombre }} {{ auth()->user()->apellidos }}</h1>
        <p class="mt-2 text-slate-600">Desde aqui puedes administrar el sistema segun el rol autenticado.</p>
        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                Cerrar sesion
            </button>
        </form>
    </section>

    <section class="mt-8 grid gap-5 md:grid-cols-4">
        <div class="rounded-2xl bg-slate-900 p-6 text-white">
            <p class="text-sm text-slate-300">Usuarios</p>
            <p class="mt-2 text-3xl font-bold">{{ $totalUsuarios }}</p>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Productos</p>
            <p class="mt-2 text-3xl font-bold">{{ $totalProductos }}</p>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Categorias</p>
            <p class="mt-2 text-3xl font-bold">{{ $totalCategorias }}</p>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Ventas</p>
            <p class="mt-2 text-3xl font-bold">{{ $totalVentas }}</p>
        </div>
    </section>

    <section class="mt-8 grid gap-6 md:grid-cols-2">
        @if ($esAdministrador)
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold">Panel de administrador</h2>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('usuarios.index') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Gestionar usuarios</a>
                    <a href="{{ route('productos.create') }}" class="rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white">Crear producto</a>
                    <a href="{{ route('categorias.create') }}" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white">Crear categoria</a>
                </div>
            </div>
        @endif

        @if ($esGerente)
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold">Panel de gerente</h2>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('usuarios.index') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Editar clientes</a>
                    <a href="{{ route('productos.create') }}" class="rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white">Crear producto</a>
                    <a href="{{ route('categorias.create') }}" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white">Crear categoria</a>
                </div>
            </div>
        @endif

        @if ($esCliente)
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold">Panel de cliente</h2>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('productos.index') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Ver productos</a>
                    <a href="{{ route('ventas.create') }}" class="rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white">Registrar compra</a>
                    <a href="{{ route('ventas.index') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Mis compras</a>
                </div>
            </div>
        @endif
    </section>
@endsection
