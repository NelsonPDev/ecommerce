@extends('layouts.app')

@section('content')
    <section class="rounded-3xl bg-white p-8 shadow-sm">
        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-cyan-700">{{ auth()->user()->rol }}</p>
        <h1 class="mt-3 text-3xl font-bold">Bienvenido, {{ auth()->user()->nombre }} {{ auth()->user()->apellidos }}</h1>
    </section>

    @if (! $esCliente)
        <section class="mt-8 grid gap-5 md:grid-cols-4">
            <div class="rounded-2xl bg-slate-900 p-6 text-white">
                <p class="text-sm text-slate-300">Usuarios</p>
                <p class="mt-2 text-3xl font-bold">{{ $totalUsuarios }}</p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Productos</p>
                <p class="mt-2 text-3xl font-bold">{{ $totalProductos }}</p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Categorias</p>
                <p class="mt-2 text-3xl font-bold">{{ $totalCategorias }}</p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Ventas</p>
                <p class="mt-2 text-3xl font-bold">{{ $totalVentas }}</p>
            </div>
        </section>
    @endif

    <section class="mt-8 grid gap-6 {{ $esCliente ? '' : 'md:grid-cols-2' }}">
        @if ($esAdministrador)
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold">Panel de administrador</h2>
                <p class="mt-3 text-sm text-slate-600">Acciones principales: crear usuarios, editar usuarios y eliminar usuarios.</p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('usuarios.index') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Administrar usuarios</a>
                    <a href="{{ route('usuarios.create') }}" class="rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white">Crear usuario</a>
                </div>
            </div>
        @endif

        @if ($esGerente)
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold">Panel de gerente</h2>
                <p class="mt-3 text-sm text-slate-600">Accion principal: editar clientes. No puede editar gerentes ni administradores.</p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('usuarios.index') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Editar clientes</a>
                </div>
            </div>
        @endif

        @if ($esCliente)
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold">Productos disponibles</h2>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('carrito.index') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Carrito</a>
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
        @endif
    </section>
@endsection
