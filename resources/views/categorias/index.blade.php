@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Categorias</h1>
            <p class="mt-2 text-sm text-slate-500">Relacion muchos a muchos entre productos y categorias.</p>
        </div>
        @auth
            @can('create', App\Models\Categoria::class)
                <a href="{{ route('categorias.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Nueva categoria</a>
            @endcan
        @endauth
    </div>

    <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($categorias as $categoria)
            <article class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold">{{ $categoria->nombre }}</h2>
                <p class="mt-3 text-sm text-slate-600">{{ $categoria->descripcion }}</p>
                <p class="mt-4 text-sm font-semibold text-slate-700">Productos asociados: {{ $categoria->productos_count }}</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('categorias.show', $categoria) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Ver detalle</a>
                    @auth
                        @can('update', $categoria)
                            <a href="{{ route('categorias.edit', $categoria) }}" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white">Editar</a>
                        @endcan
                    @endauth
                </div>
            </article>
        @empty
            <div class="rounded-2xl bg-white p-6 shadow-sm">No hay categorias registradas.</div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $categorias->links() }}
    </div>
@endsection
