@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Pago con tarjeta</h1>
            <p class="mt-2 text-sm text-slate-500">Simula tu compra ingresando los datos de una tarjeta.</p>
        </div>
        <a href="{{ route('carrito.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Volver al carrito</a>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_0.9fr]">
        <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold">Resumen de la compra</h2>
            <div class="mt-4 space-y-4">
                @foreach ($items as $item)
                    <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 p-4">
                        <div>
                            <p class="font-semibold">{{ $item['producto']->nombre }}</p>
                            <p class="text-sm text-slate-500">Cantidad: {{ $item['cantidad'] }}</p>
                        </div>
                        <p class="font-bold text-emerald-700">${{ number_format($item['subtotal'], 2) }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 rounded-xl bg-slate-900 p-4 text-white">
                <p class="text-sm text-slate-300">Total a pagar</p>
                <p class="mt-2 text-3xl font-bold">${{ number_format($total, 2) }}</p>
            </div>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold">Datos de la tarjeta</h2>
            <form method="POST" action="{{ route('carrito.process') }}" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-semibold">Titular de la tarjeta</label>
                    <input type="text" name="titular_tarjeta" value="{{ old('titular_tarjeta') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" placeholder="Nombre completo" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold">Numero de tarjeta</label>
                    <input type="text" name="numero_tarjeta" value="{{ old('numero_tarjeta') }}" maxlength="16" class="w-full rounded-xl border border-slate-300 px-4 py-3" placeholder="1234123412341234" required>
                </div>
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold">Expiracion</label>
                        <input type="text" name="expiracion" value="{{ old('expiracion') }}" maxlength="5" class="w-full rounded-xl border border-slate-300 px-4 py-3" placeholder="MM/AA" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold">CVV</label>
                        <input type="password" name="cvv" maxlength="4" class="w-full rounded-xl border border-slate-300 px-4 py-3" placeholder="123" required>
                    </div>
                </div>
                <button type="submit" class="w-full rounded-xl px-4 py-3 text-sm font-semibold" style="background-color: #0891b2; color: #ffffff; border: 1px solid #0e7490;">
                    Confirmar compra
                </button>
            </form>
        </section>
    </div>
@endsection
