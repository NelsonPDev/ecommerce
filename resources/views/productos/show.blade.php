@extends('layouts.app')

@section('content')
    <article class="rounded-3xl bg-white p-8 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">{{ $producto->nombre }}</h1>
                <p class="mt-2 text-sm text-slate-500">Vendedor: {{ $producto->usuario->nombre }} {{ $producto->usuario->apellidos }}</p>
            </div>
            <div class="rounded-2xl bg-emerald-50 px-5 py-3 text-right">
                <p class="text-sm text-emerald-700">Precio</p>
                <p class="text-2xl font-bold text-emerald-800">${{ number_format($producto->precio, 2) }}</p>
            </div>
        </div>

        <p class="mt-6 text-slate-700">{{ $producto->descripcion }}</p>
        <p class="mt-4 text-sm font-semibold text-slate-700">Existencia disponible: {{ $producto->existencia }}</p>

        <div class="mt-5 flex flex-wrap gap-2">
            @foreach ($producto->categorias as $categoria)
                <a href="{{ route('categorias.show', $categoria) }}" class="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">
                    {{ $categoria->nombre }}
                </a>
            @endforeach
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('productos.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Volver</a>
            @auth
                @can('update', $producto)
                    <a href="{{ route('productos.edit', $producto) }}" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white">Editar producto</a>
                @endcan
                @if (auth()->user()->esCliente())
                    <a href="{{ route('ventas.create') }}" class="rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white">Comprar producto</a>
                @endif
            @endauth
        </div>
    </article>
@endsection
