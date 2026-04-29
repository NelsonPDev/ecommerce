@extends('layouts.app')

@section('content')
    @if ($esAdministrador)
        <section class="rounded-3xl bg-white p-8 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-cyan-700">Administrador</p>
            <h1 class="mt-3 text-3xl font-bold text-slate-900">Dashboard general</h1>
            <p class="mt-3 text-slate-600">Consultas construidas con relaciones Eloquent para usuarios, categorias, productos y ventas.</p>
        </section>

        <section class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Total de usuarios</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalUsuarios }}</p>
            </article>
            <article class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Total de vendedores</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalVendedores }}</p>
            </article>
            <article class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Total de compradores</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalCompradores }}</p>
            </article>
            <article class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Vendedor con mas categorias</p>
                <p class="mt-2 text-lg font-bold text-slate-900">
                    {{ $vendedorConMasCategorias?->nombre }} {{ $vendedorConMasCategorias?->apellidos }}
                </p>
                <p class="mt-1 text-sm text-slate-500">{{ $vendedorConMasCategorias?->categoria_productos_count ?? 0 }} relaciones categoria-producto</p>
            </article>
        </section>

        <section class="mt-8 grid gap-6 xl:grid-cols-2">
            <article class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900">Productos por categoria</h2>
                <div class="mt-6 space-y-3">
                    @foreach ($productosPorCategoria as $categoria)
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 p-4">
                            <span class="font-semibold text-slate-900">{{ $categoria->nombre }}</span>
                            <span class="text-sm text-slate-600">{{ $categoria->productos_count }} productos</span>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900">Producto mas vendido</h2>
                @if ($productoMasVendido && $productoMasVendido->unidades_vendidas > 0)
                    <div class="mt-6 rounded-2xl bg-slate-50 p-5">
                        <p class="text-2xl font-bold text-slate-900">{{ $productoMasVendido->nombre }}</p>
                        <p class="mt-2 text-slate-600">Vendedor: {{ $productoMasVendido->usuario->nombre }} {{ $productoMasVendido->usuario->apellidos }}</p>
                        <p class="mt-4 text-lg font-semibold text-emerald-700">{{ $productoMasVendido->unidades_vendidas }} unidades vendidas</p>
                    </div>
                @else
                    <div class="mt-6 rounded-2xl bg-slate-50 p-5 text-slate-500">
                        Aun no hay ventas suficientes para determinar el producto mas vendido.
                    </div>
                @endif
            </article>
        </section>

        <section class="mt-8 rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold text-slate-900">Comprador mas frecuente por categoria</h2>
            <div class="mt-6 grid gap-4 lg:grid-cols-2">
                @foreach ($compradoresFrecuentesPorCategoria as $item)
                    <div class="rounded-2xl bg-slate-50 p-5">
                        <p class="font-semibold text-slate-900">{{ $item['categoria']->nombre }}</p>
                        @if ($item['comprador'])
                            <p class="mt-3 text-slate-700">{{ $item['comprador']->nombre }} {{ $item['comprador']->apellidos }}</p>
                            <p class="text-sm text-slate-500">{{ $item['compras'] }} compras registradas en esta categoria</p>
                        @else
                            <p class="mt-3 text-sm text-slate-500">Todavia no hay compras en esta categoria.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @elseif ($esGerente)
        <section class="rounded-3xl bg-white p-8 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-cyan-700">Gerente</p>
            <h1 class="mt-3 text-3xl font-bold text-slate-900">Validacion de ventas</h1>
            <p class="mt-3 text-slate-600">Desde aqui puedes revisar tickets y validar ventas pendientes.</p>
        </section>

        <section class="mt-8 rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold text-slate-900">Ventas pendientes</h2>
            <div class="mt-6 space-y-4">
                @forelse ($ventasPendientes as $venta)
                    <div class="rounded-2xl bg-slate-50 p-5">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="font-semibold text-slate-900">Venta #{{ $venta->id }} - {{ $venta->producto->nombre }}</p>
                                <p class="mt-1 text-sm text-slate-500">Cliente: {{ $venta->cliente->nombre }} {{ $venta->cliente->apellidos }}</p>
                                <p class="text-sm text-slate-500">Vendedor: {{ $venta->vendedor->nombre }} {{ $venta->vendedor->apellidos }}</p>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('ventas.ticket', $venta) }}" target="_blank" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Ver ticket</a>
                                <form method="POST" action="{{ route('ventas.validate', $venta) }}">
                                    @csrf
                                    <button type="submit" class="rounded-lg px-4 py-2 text-sm font-semibold text-white" style="background-color: #16a34a; border: 1px solid #15803d;">
                                        Validar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl bg-slate-50 p-5 text-slate-500">
                        No hay ventas pendientes por validar.
                    </div>
                @endforelse
            </div>
        </section>
    @else
        <section class="rounded-3xl bg-white p-8 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-cyan-700">{{ $usuario->rol }}</p>
            <h1 class="mt-3 text-3xl font-bold">Bienvenido, {{ $usuario->nombre }} {{ $usuario->apellidos }}</h1>
            <p class="mt-3 text-slate-600">Aqui puedes revisar tus movimientos y explorar productos disponibles.</p>
        </section>

        <section class="mt-8 grid gap-5 md:grid-cols-3">
            <article class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Mis compras</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $misCompras }}</p>
            </article>
            @if ($esVendedor)
                <article class="rounded-2xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-slate-500">Mis ventas</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $misVentas }}</p>
                </article>
                <article class="rounded-2xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-slate-500">Mis productos</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $misProductos->count() }}</p>
                </article>
            @endif
        </section>

        @if ($esVendedor)
            <section class="mt-8 rounded-2xl bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-900">Mis productos recientes</h2>
                    <a href="{{ route('productos.index') }}" class="text-sm font-semibold text-cyan-700">Ver todos</a>
                </div>
                <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($misProductos as $producto)
                        <article class="rounded-2xl bg-slate-50 p-4">
                            <p class="font-semibold text-slate-900">{{ $producto->nombre }}</p>
                            <p class="mt-2 text-sm text-slate-500">{{ $producto->categorias->pluck('nombre')->join(', ') }}</p>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="mt-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <h2 class="text-2xl font-bold">Productos disponibles</h2>
                    @if ($esCliente)
                        <a href="{{ route('carrito.index') }}" class="rounded-lg px-4 py-2 text-sm font-semibold text-white" style="background-color: #16a34a; border: 1px solid #15803d;">
                            Carrito
                        </a>
                    @endif
                </div>

                <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($productos as $producto)
                        <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
                            @if ($producto->primeraFotoUrl())
                                <img src="{{ $producto->primeraFotoUrl() }}" alt="Foto de {{ $producto->nombre }}" class="mb-5 h-44 w-full rounded-2xl object-cover">
                            @endif
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
                                @if ($esCliente)
                                    <form method="POST" action="{{ route('carrito.add') }}" class="flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                        <input type="number" name="cantidad" min="1" max="{{ $producto->existencia }}" value="1" class="w-20 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                        <button type="submit" class="rounded-lg px-4 py-2 text-sm font-semibold" style="background-color: #16a34a; color: #ffffff; border: 1px solid #15803d;">
                                            Agregar
                                        </button>
                                    </form>
                                @endif
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
