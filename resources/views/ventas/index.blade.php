@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">{{ auth()->user()->esCliente() ? 'Historial de compras' : 'Ventas registradas' }}</h1>
            <p class="mt-2 text-sm text-slate-500">Cada venta muestra producto, cliente, vendedor, cantidad, fecha y total.</p>
        </div>
        @if (auth()->user()->esCliente())
            <a href="{{ route('carrito.index') }}" class="rounded-lg px-4 py-2 text-sm font-semibold text-white" style="background-color: #0f172a; border: 1px solid #0f172a;">
                Ver carrito
            </a>
        @endif
    </div>

    <div class="mt-8 overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Producto</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Cliente</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Vendedor</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Cantidad</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Fecha</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Total</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($ventas as $venta)
                    <tr>
                        <td class="px-4 py-4">{{ $venta->producto->nombre }}</td>
                        <td class="px-4 py-4">{{ $venta->cliente->nombre }} {{ $venta->cliente->apellidos }}</td>
                        <td class="px-4 py-4">{{ $venta->vendedor->nombre }} {{ $venta->vendedor->apellidos }}</td>
                        <td class="px-4 py-4">{{ $venta->cantidad }}</td>
                        <td class="px-4 py-4">{{ $venta->fecha->format('Y-m-d') }}</td>
                        <td class="px-4 py-4">${{ number_format($venta->total, 2) }}</td>
                        <td class="px-4 py-4">
                            <div class="flex flex-wrap gap-3 text-sm font-semibold">
                                <a href="{{ route('ventas.show', $venta) }}" class="text-cyan-700">Ver</a>
                                @can('update', $venta)
                                    <a href="{{ route('ventas.edit', $venta) }}" class="text-amber-600">Editar</a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $ventas->links() }}
    </div>
@endsection
