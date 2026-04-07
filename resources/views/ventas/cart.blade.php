@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Carrito de compras</h1>
            <p class="mt-2 text-sm text-slate-500">Revisa los productos seleccionados antes de proceder al pago.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Seguir comprando</a>
    </div>

    @if (count($items) > 0)
        <div class="mt-8 grid gap-6 lg:grid-cols-[1.4fr_0.6fr]">
            <section class="space-y-4">
                @foreach ($items as $item)
                    <article class="rounded-2xl bg-white p-6 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-bold">{{ $item['producto']->nombre }}</h2>
                                <p class="mt-1 text-sm text-slate-500">Vendedor: {{ $item['producto']->usuario->nombre }} {{ $item['producto']->usuario->apellidos }}</p>
                                <p class="mt-2 text-sm text-slate-600">{{ $item['producto']->descripcion }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500">Subtotal</p>
                                <p class="text-xl font-bold text-emerald-700">${{ number_format($item['subtotal'], 2) }}</p>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-wrap items-center gap-3">
                            <form method="POST" action="{{ route('carrito.update', $item['producto']) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <label class="text-sm font-semibold text-slate-700">Cantidad</label>
                                <input type="number" name="cantidad" min="1" max="{{ $item['producto']->existencia }}" value="{{ $item['cantidad'] }}" class="w-24 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <button type="submit" class="rounded-lg px-4 py-2 text-sm font-semibold" style="background-color: #0f172a; color: #ffffff; border: 1px solid #0f172a;">
                                    Actualizar
                                </button>
                            </form>

                            <form method="POST" action="{{ route('carrito.remove', $item['producto']) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg px-4 py-2 text-sm font-semibold" style="background-color: #dc2626; color: #ffffff; border: 1px solid #b91c1c;">
                                    Quitar
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </section>

            <aside class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold">Resumen</h2>
                <p class="mt-4 text-sm text-slate-500">Total de la compra</p>
                <p class="mt-2 text-3xl font-bold text-emerald-700">${{ number_format($total, 2) }}</p>
                <a href="{{ route('carrito.checkout') }}" class="mt-6 inline-block w-full rounded-lg px-4 py-3 text-center text-sm font-semibold" style="background-color: #0891b2; color: #ffffff; border: 1px solid #0e7490;">
                    Proceder al pago
                </a>
            </aside>
        </div>
    @else
        <div class="mt-8 rounded-2xl bg-white p-8 text-center shadow-sm">
            <h2 class="text-2xl font-bold">Tu carrito esta vacio</h2>
            <p class="mt-2 text-slate-500">Agrega productos desde el catalogo para continuar con la compra.</p>
            <a href="{{ route('dashboard') }}" class="mt-6 inline-block rounded-lg px-5 py-3 text-sm font-semibold" style="background-color: #0f172a; color: #ffffff; border: 1px solid #0f172a;">
                Volver al dashboard
            </a>
        </div>
    @endif
@endsection
