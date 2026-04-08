@extends('layouts.app')

@section('content')
    <section class="rounded-3xl bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-bold">Editar categoria</h1>
        <form method="POST" action="{{ route('categorias.update', $categoria) }}" class="mt-8 grid gap-5">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-2 block text-sm font-semibold">Nombre</label>
                <input name="nombre" type="text" value="{{ old('nombre', $categoria->nombre) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Descripcion</label>
                <textarea name="descripcion" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>{{ old('descripcion', $categoria->descripcion) }}</textarea>
            </div>
            <div class="mt-2 flex flex-wrap items-center gap-3">
                <button type="submit" class="rounded-lg px-5 py-3 text-sm font-semibold text-white" style="background-color: #0f172a; border: 1px solid #0f172a;">
                    Actualizar
                </button>
                <a href="{{ route('categorias.index') }}" class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700">Cancelar</a>
                <button type="submit" form="delete-category-form" class="rounded-lg px-5 py-3 text-sm font-semibold text-white" style="background-color: #dc2626; border: 1px solid #b91c1c;" onclick="return confirm('Deseas eliminar esta categoria?')">
                    Eliminar categoria
                </button>
            </div>
        </form>

        <form id="delete-category-form" method="POST" action="{{ route('categorias.destroy', $categoria) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </section>
@endsection
