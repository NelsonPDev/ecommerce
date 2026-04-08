@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Categorias</h1>
        </div>
        @auth
            @can('create', App\Models\Categoria::class)
                <a href="{{ route('categorias.create') }}" class="rounded-lg px-4 py-2 text-sm font-semibold text-white" style="background-color: #0f172a; border: 1px solid #0f172a;">
                    Nueva categoria
                </a>
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
                            <a href="{{ route('categorias.edit', $categoria) }}" class="rounded-lg px-4 py-2 text-sm font-semibold text-white" style="background-color: #f59e0b; border: 1px solid #d97706;">
                                Editar
                            </a>
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
