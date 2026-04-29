@extends('layouts.app')

@section('content')
    <section class="rounded-3xl bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-bold">Registrar venta</h1>
        <form method="POST" action="{{ route('ventas.store') }}" enctype="multipart/form-data" class="mt-8 grid gap-5">
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
            @if (auth()->user()->esAdministrador())
                <div>
                    <label class="mb-2 block text-sm font-semibold">Cliente</label>
                    <select name="cliente_id" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
                        <option value="">Selecciona un cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}" @selected(old('cliente_id') == $cliente->id)>
                                {{ $cliente->nombre }} {{ $cliente->apellidos }} ({{ $cliente->correo }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
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
            <div>
                <label class="mb-2 block text-sm font-semibold">Ticket</label>
                <input name="ticket" type="file" accept="image/*" class="w-full rounded-xl border border-slate-300 px-4 py-3" required>
                <p class="mt-2 text-sm text-slate-500">El ticket se almacena en disco privado y solo se sirve por controlador autorizado.</p>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg px-5 py-3 text-sm font-semibold text-white" style="background-color: #0f172a; border: 1px solid #0f172a;">
                    Registrar compra
                </button>
                <a href="{{ route('ventas.index') }}" class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
