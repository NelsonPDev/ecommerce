<?php

namespace App\Services;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Support\Collection;

class AdminDashboardService
{
    public function build(): array
    {
        $usuarios = Usuario::with(['productos.categorias', 'ventasComoCliente'])->get();
        $categorias = Categoria::with(['productos.ventas.cliente', 'productos.usuario'])->get();
        $productos = Producto::with(['usuario', 'categorias', 'ventas.cliente'])->get();

        $categorias->each(function (Categoria $categoria): void {
            $categoria->setAttribute('productos_count', $categoria->productos->count());
        });

        $productoMasVendido = $productos
            ->map(function (Producto $producto): Producto {
                $producto->setAttribute('unidades_vendidas', $producto->ventas->sum('cantidad'));

                return $producto;
            })
            ->sortByDesc('unidades_vendidas')
            ->first();

        $compradoresFrecuentesPorCategoria = $categorias->map(function (Categoria $categoria): array {
            $ventasDelComprador = $categoria->productos
                ->flatMap->ventas
                ->groupBy('cliente_id')
                ->sortByDesc(fn (Collection $ventasCliente) => $ventasCliente->count())
                ->first();

            $primerRegistro = $ventasDelComprador?->first();

            return [
                'categoria' => $categoria,
                'comprador' => $primerRegistro?->cliente,
                'compras' => $ventasDelComprador?->count() ?? 0,
            ];
        });

        return [
            'totalUsuarios' => $usuarios->count(),
            'totalVendedores' => $usuarios->where('es_vendedor', true)->count(),
            'totalCompradores' => $usuarios->where('es_vendedor', false)->count(),
            'productosPorCategoria' => $categorias,
            'productoMasVendido' => $productoMasVendido,
            'compradoresFrecuentesPorCategoria' => $compradoresFrecuentesPorCategoria,
        ];
    }
}
