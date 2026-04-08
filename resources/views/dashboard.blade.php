@extends('layouts.app')

@section('content')
    @if ($esAdministrador)
        <section class="rounded-3xl bg-white p-8 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-cyan-700">Administrador</p>
            <h1 class="mt-3 text-3xl font-bold text-slate-900">Bienvenido, {{ auth()->user()->nombre }} {{ auth()->user()->apellidos }}</h1>
        </section>

        <section class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl bg-slate-900 p-6 text-white">
                <p class="text-sm text-slate-300">Ventas totales</p>
                <p class="mt-2 text-3xl font-bold">{{ $totalVentas }}</p>
            </article>
            <article class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Ingresos últimos 7 días</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">${{ number_format($totalIngresosSemana, 2) }}</p>
            </article>
            <article class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Unidades vendidas</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalUnidadesSemana }}</p>
            </article>
            <article class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Productos registrados</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalProductos }}</p>
            </article>
        </section>

        <section class="mt-8 grid gap-6 lg:grid-cols-[1.4fr_1fr]">
            <article class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Ventas por día</h2>
                        <p class="mt-1 text-sm text-slate-500">Últimos 7 días</p>
                    </div>
                </div>

                <div class="mt-8 rounded-2xl bg-slate-50 p-5">
                    <div class="flex h-64 items-end gap-3 border-b border-slate-200 pb-4">
                        @foreach ($serieVentas as $item)
                            @php
                                $barHeight = $item['ingresos'] > 0 && $maxIngresosVentas > 0
                                    ? max(($item['ingresos'] / $maxIngresosVentas) * 180, 24)
                                    : 12;
                            @endphp
                            <div class="flex flex-1 flex-col items-center justify-end gap-3">
                                <span class="text-xs font-semibold text-slate-500">${{ number_format($item['ingresos'], 0) }}</span>
                                <div class="w-full rounded-t-xl" style="height: {{ $barHeight }}px; background: linear-gradient(180deg, #06b6d4 0%, #2563eb 100%); box-shadow: 0 10px 24px rgba(37, 99, 235, 0.22);"></div>
                                <span class="text-xs font-medium text-slate-600">{{ $item['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </article>

            <article class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900">Productos más vendidos</h2>

                <div class="mt-6 space-y-4">
                    @forelse ($productosMasVendidos as $producto)
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="font-semibold text-slate-900">{{ $producto->nombre }}</p>
                            <div class="mt-2 flex items-center justify-between text-sm text-slate-600">
                                <span>{{ $producto->unidades_vendidas }} unidades</span>
                                <span>${{ number_format((float) $producto->ingresos, 2) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl bg-slate-50 p-4 text-sm text-slate-500">
                            Aún no hay ventas registradas.
                        </div>
                    @endforelse
                </div>
            </article>
        </section>
    @elseif ($esGerente)
        <section class="rounded-3xl bg-white p-8 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-cyan-700">{{ auth()->user()->rol }}</p>
            <h1 class="mt-3 text-3xl font-bold text-slate-900">Bienvenido, {{ auth()->user()->nombre }} {{ auth()->user()->apellidos }}</h1>

            <div class="mt-8 grid gap-5 md:grid-cols-2">
                <div class="rounded-2xl bg-slate-900 p-6 text-white">
                    <p class="text-sm text-slate-300">Clientes</p>
                    <p class="mt-2 text-3xl font-bold">{{ $totalClientes }}</p>
                </div>
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-slate-500">Productos</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalProductos }}</p>
                </div>
            </div>
        </section>
    @elseif ($esCliente)
        <section class="rounded-3xl bg-white p-8 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-cyan-700">{{ auth()->user()->rol }}</p>
            <h1 class="mt-3 text-3xl font-bold">Bienvenido, {{ auth()->user()->nombre }} {{ auth()->user()->apellidos }}</h1>
        </section>

        <section class="mt-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold">Productos disponibles</h2>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('carrito.index') }}" class="rounded-lg px-4 py-2 text-sm font-semibold text-white" style="background-color: #16a34a; border: 1px solid #15803d;">
                            Carrito
                        </a>
                    </div>
                </div>

                <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($productos as $producto)
                        <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-xl font-bold">{{ $producto->nombre }}</h3>
                                    <p class="mt-1 text-sm text-slate-500">Vendedor: {{ $producto->usuario->nombre }} {{ $producto->usuario->apellidos }}</p>
                                </div>
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-700">
                                    ${{ number_format($producto->precio, 2) }}
                                </span>
                            </div>

                            <p class="mt-4 text-sm text-slate-600">{{ $producto->descripcion }}</p>
                            <p class="mt-3 text-sm font-medium text-slate-700">Existencia: {{ $producto->existencia }}</p>

                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach ($producto->categorias as $categoria)
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700">{{ $categoria->nombre }}</span>
                                @endforeach
                            </div>

                            <div class="mt-6 flex flex-wrap gap-3">
                                <a href="{{ route('productos.show', $producto) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Ver detalle</a>
                                <form method="POST" action="{{ route('carrito.add') }}" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                    <input type="number" name="cantidad" min="1" max="{{ $producto->existencia }}" value="1" class="w-20 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    <button type="submit" class="rounded-lg px-4 py-2 text-sm font-semibold" style="background-color: #16a34a; color: #ffffff; border: 1px solid #15803d;">
                                        Agregar al carrito
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-slate-600">
                            No hay productos disponibles en este momento.
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $productos->links() }}
                </div>
            </div>
        </section>
    @endif
@endsection
