@extends('layouts.app')

@section('content')
    <section class="rounded-3xl bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-bold">Detalle de venta #{{ $venta->id }}</h1>
        <div class="mt-8 grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm font-semibold text-slate-500">Producto</p>
                <p class="mt-2 text-lg font-bold">{{ $venta->producto->nombre }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm font-semibold text-slate-500">Cliente</p>
                <p class="mt-2 text-lg font-bold">{{ $venta->cliente->nombre }} {{ $venta->cliente->apellidos }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm font-semibold text-slate-500">Vendedor</p>
                <p class="mt-2 text-lg font-bold">{{ $venta->vendedor->nombre }} {{ $venta->vendedor->apellidos }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm font-semibold text-slate-500">Fecha</p>
                <p class="mt-2 text-lg font-bold">{{ $venta->fecha->format('Y-m-d') }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm font-semibold text-slate-500">Cantidad</p>
                <p class="mt-2 text-lg font-bold">{{ $venta->cantidad }}</p>
            </div>
            <div class="rounded-2xl bg-emerald-50 p-4">
                <p class="text-sm font-semibold text-emerald-700">Total</p>
                <p class="mt-2 text-2xl font-bold text-emerald-800">${{ number_format($venta->total, 2) }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm font-semibold text-slate-500">Estado</p>
                <p class="mt-2 text-lg font-bold">{{ ucfirst($venta->estado) }}</p>
            </div>
            @if ($venta->validada_at)
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Validada por</p>
                    <p class="mt-2 text-lg font-bold">{{ $venta->validador?->nombre }} {{ $venta->validador?->apellidos }}</p>
                </div>
            @endif
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('ventas.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Volver</a>
            @can('viewTicket', $venta)
                <a href="{{ route('ventas.ticket', $venta) }}" target="_blank" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">
                    Ver ticket protegido
                </a>
            @endcan
            @can('update', $venta)
                <a href="{{ route('ventas.edit', $venta) }}" class="rounded-lg px-4 py-2 text-sm font-semibold text-white" style="background-color: #f59e0b; border: 1px solid #d97706;">
                    Editar venta
                </a>
            @endcan
            @can('validate', $venta)
                <form method="POST" action="{{ route('ventas.validate', $venta) }}">
                    @csrf
                    <button type="submit" class="rounded-lg px-4 py-2 text-sm font-semibold text-white" style="background-color: #16a34a; border: 1px solid #15803d;">
                        Validar venta
                    </button>
                </form>
            @endcan
        </div>
    </section>
@endsection
