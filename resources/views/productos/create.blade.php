@extends('layouts.app')

@section('content')
    <section class="rounded-3xl bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-bold">Crear producto</h1>
        <form method="POST" action="{{ route('productos.store') }}" class="mt-8 grid gap-5">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-semibold">Nombre</label>
                <input name="nombre" type="text" value="{{ old('nombre') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Descripcion</label>
                <textarea name="descripcion" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>{{ old('descripcion') }}</textarea>
            </div>
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold">Precio</label>
                    <input name="precio" type="number" min="0" step="0.01" value="{{ old('precio') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold">Existencia</label>
                    <input name="existencia" type="number" min="0" value="{{ old('existencia') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
                </div>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Categorias</label>
                <div class="grid gap-3 rounded-2xl border border-slate-200 p-4 md:grid-cols-2">
                    @foreach ($categorias as $categoria)
                        <label class="flex items-center gap-3 text-sm">
                            <input type="checkbox" name="categorias[]" value="{{ $categoria->id }}" @checked(collect(old('categorias'))->contains($categoria->id))>
                            <span>{{ $categoria->nombre }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Guardar</button>
                <a href="{{ route('productos.index') }}" class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
