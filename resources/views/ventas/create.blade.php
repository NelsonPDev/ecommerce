@extends('layouts.app')

@section('content')
    <section class="rounded-3xl bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-bold">Registrar venta</h1>
        <form method="POST" action="{{ route('ventas.store') }}" class="mt-8 grid gap-5">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-semibold">Producto</label>
                <select name="producto_id" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
                    <option value="">Selecciona un producto</option>
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->id }}" @selected(old('producto_id') == $producto->id)>
                            {{ $producto->nombre }} - {{ $producto->usuario->nombre }} {{ $producto->usuario->apellidos }} - ${{ number_format($producto->precio, 2) }} - existencia {{ $producto->existencia }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold">Cantidad</label>
                    <input name="cantidad" type="number" min="1" value="{{ old('cantidad', 1) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold">Fecha</label>
                    <input name="fecha" type="date" value="{{ old('fecha', now()->toDateString()) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Registrar compra</button>
                <a href="{{ route('ventas.index') }}" class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
