@extends('layouts.app')

@section('content')
    <section class="rounded-3xl bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-bold">Crear categoria</h1>
        <form method="POST" action="{{ route('categorias.store') }}" class="mt-8 grid gap-5">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-semibold">Nombre</label>
                <input name="nombre" type="text" value="{{ old('nombre') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Descripcion</label>
                <textarea name="descripcion" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>{{ old('descripcion') }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Guardar</button>
                <a href="{{ route('categorias.index') }}" class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
