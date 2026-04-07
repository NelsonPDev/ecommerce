@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-3xl font-bold mb-6">Catálogo de Productos</h2>

                @if($productos->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($productos as $producto)
                            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-bold mb-2">{{ $producto->nombre }}</h3>
                                <p class="text-gray-700 mb-2">{{ $producto->descripcion }}</p>
                                <p class="text-lg font-semibold text-green-600">${{ number_format($producto->precio, 2) }}</p>
                                <p class="text-sm text-gray-500">Stock: {{ $producto->stock }}</p>
                                <p class="text-sm text-gray-500">Categoría: {{ $producto->categoria->nombre ?? 'Sin categoría' }}</p>
                                <a href="{{ route('productos.show', $producto) }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Ver Detalles
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $productos->links() }}
                    </div>
                @else
                    <p class="text-gray-700">No hay productos disponibles.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection