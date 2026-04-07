@extends('layouts.app')

@section('content')
    <section class="rounded-3xl bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-bold">Editar venta</h1>
        <div class="mt-4 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
            Producto: <strong>{{ $venta->producto->nombre }}</strong><br>
            Cliente: <strong>{{ $venta->cliente->nombre }} {{ $venta->cliente->apellidos }}</strong><br>
            Vendedor: <strong>{{ $venta->vendedor->nombre }} {{ $venta->vendedor->apellidos }}</strong>
        </div>

        <form method="POST" action="{{ route('ventas.update', $venta) }}" class="mt-8 grid gap-5 md:grid-cols-2">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-2 block text-sm font-semibold">Fecha</label>
                <input name="fecha" type="date" value="{{ old('fecha', $venta->fecha->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Total</label>
                <input name="total" type="number" min="0" step="0.01" value="{{ old('total', $venta->total) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
            </div>
            <div class="md:col-span-2 flex flex-wrap gap-3">
                <button type="submit" class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Actualizar</button>
                <a href="{{ route('ventas.show', $venta) }}" class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700">Cancelar</a>
            </div>
        </form>

        @can('delete', $venta)
            <form method="POST" action="{{ route('ventas.destroy', $venta) }}" class="mt-4">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg bg-red-600 px-5 py-3 text-sm font-semibold text-white" onclick="return confirm('Deseas eliminar esta venta?')">
                    Eliminar venta
                </button>
            </form>
        @endcan
    </section>
@endsection
