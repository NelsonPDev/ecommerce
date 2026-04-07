@extends('layouts.app')

@section('content')
    <section class="rounded-3xl bg-white p-8 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">{{ $categoria->nombre }}</h1>
                <p class="mt-3 text-slate-600">{{ $categoria->descripcion }}</p>
            </div>
            @auth
                @can('update', $categoria)
                    <a href="{{ route('categorias.edit', $categoria) }}" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white">Editar categoria</a>
                @endcan
            @endauth
        </div>

        <div class="mt-8">
            <h2 class="text-xl font-bold">Productos en esta categoria</h2>
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                @forelse ($categoria->productos as $producto)
                    <div class="rounded-2xl border border-slate-200 p-5">
                        <h3 class="text-lg font-bold">{{ $producto->nombre }}</h3>
                        <p class="mt-2 text-sm text-slate-500">Vendedor: {{ $producto->usuario->nombre }} {{ $producto->usuario->apellidos }}</p>
                        <a href="{{ route('productos.show', $producto) }}" class="mt-4 inline-block text-sm font-semibold text-cyan-700">Ver producto</a>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No hay productos asociados.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
