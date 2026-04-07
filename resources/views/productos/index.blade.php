@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Catalogo de productos</h1>
            <p class="mt-2 text-sm text-slate-500">Cada producto muestra a su vendedor y sus categorias asociadas.</p>
        </div>
        @auth
            @can('create', App\Models\Producto::class)
                <a href="{{ route('productos.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Nuevo producto</a>
            @endcan
        @endauth
    </div>

    <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($productos as $producto)
            <article class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold">{{ $producto->nombre }}</h2>
                        <p class="mt-1 text-sm text-slate-500">Vendedor: {{ $producto->usuario->nombre }} {{ $producto->usuario->apellidos }}</p>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-700">
                        ${{ number_format($producto->precio, 2) }}
                    </span>
                </div>
                <p class="mt-4 text-sm text-slate-600">{{ $producto->descripcion }}</p>
                <p class="mt-3 text-sm font-medium text-slate-700">Existencia: {{ $producto->existencia }}</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($producto->categorias as $categoria)
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $categoria->nombre }}</span>
                    @endforeach
                </div>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('productos.show', $producto) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Ver detalle</a>
                    @auth
                        @can('update', $producto)
                            <a href="{{ route('productos.edit', $producto) }}" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white">Editar</a>
                        @endcan
                    @endauth
                </div>
            </article>
        @empty
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                No hay productos registrados.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $productos->links() }}
    </div>
@endsection
