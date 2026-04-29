@extends('layouts.app')

@section('content')
    <section class="rounded-3xl bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-bold">Editar venta</h1>
        <div class="mt-4 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
            Producto: <strong>{{ $venta->producto->nombre }}</strong><br>
            Cliente: <strong>{{ $venta->cliente->nombre }} {{ $venta->cliente->apellidos }}</strong>
        </div>

        <form method="POST" action="{{ route('ventas.update', $venta) }}" enctype="multipart/form-data" class="mt-8 grid gap-5 md:grid-cols-2">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-2 block text-sm font-semibold">Cantidad</label>
                <input id="cantidadInput" name="cantidad" type="number" min="1" value="{{ old('cantidad', $venta->cantidad) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
                @error('cantidad')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Vendedor</label>
                <select name="vendedor_id" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
                    <option value="">Selecciona un vendedor</option>
                    @foreach ($vendedores as $vendedor)
                        <option value="{{ $vendedor->id }}" @selected(old('vendedor_id', $venta->vendedor_id) == $vendedor->id)>
                            {{ $vendedor->nombre }} {{ $vendedor->apellidos }}
                        </option>
                    @endforeach
                </select>
                @error('vendedor_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Fecha</label>
                <input name="fecha" type="date" value="{{ old('fecha', $venta->fecha->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
                @error('fecha')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Total</label>
                <input id="totalInput" name="total" type="number" min="0" step="0.01" value="{{ old('total', $venta->total) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
                @error('total')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold">Reemplazar ticket</label>
                <input name="ticket" type="file" accept="image/*" class="w-full rounded-xl border border-slate-300 px-4 py-3">
            </div>
            <div class="md:col-span-2 mt-2 flex flex-wrap items-center gap-3">
                <button type="submit" class="rounded-lg px-5 py-3 text-sm font-semibold text-white" style="background-color: #0f172a; border: 1px solid #0f172a;">
                    Actualizar
                </button>
                <a href="{{ route('ventas.show', $venta) }}" class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700">Cancelar</a>
                @can('delete', $venta)
                    <button type="submit" form="delete-sale-form" class="rounded-lg px-5 py-3 text-sm font-semibold text-white" style="background-color: #dc2626; border: 1px solid #b91c1c;" onclick="return confirm('Deseas eliminar esta venta?')">
                        Eliminar venta
                    </button>
                @endcan
            </div>
        </form>

        @can('delete', $venta)
            <form id="delete-sale-form" method="POST" action="{{ route('ventas.destroy', $venta) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endcan
    </section>

    <script>
        const precioUnitario = {{ $venta->producto->precio }};
        const cantidadInput = document.getElementById('cantidadInput');
        const totalInput = document.getElementById('totalInput');

        function actualizarTotal() {
            const cantidad = parseInt(cantidadInput.value) || 0;
            const total = cantidad * precioUnitario;
            totalInput.value = total.toFixed(2);
        }

        cantidadInput.addEventListener('input', actualizarTotal);
    </script>
@endsection
