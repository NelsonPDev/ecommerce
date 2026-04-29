<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $usuario = auth()->user();
        $esAdministrador = $usuario->esAdministrador();
        $esGerente = $usuario->esGerente();
        $esCliente = $usuario->esCliente();
        $esVendedor = $usuario->esVendedor();

        $categorias = Categoria::with(['productos.ventas.cliente'])->withCount('productos')->get();
        $productos = Producto::with(['usuario', 'categorias', 'ventas'])->latest()->paginate(9);
        $ventasPendientes = Venta::with(['producto', 'cliente', 'vendedor'])
            ->where('estado', 'pendiente')
            ->latest()
            ->take(8)
            ->get();

        $productoMasVendido = Producto::with('ventas')
            ->get()
            ->map(function (Producto $producto) {
                $producto->unidades_vendidas = $producto->ventas->sum('cantidad');

                return $producto;
            })
            ->sortByDesc('unidades_vendidas')
            ->first();

        $compradoresFrecuentesPorCategoria = $categorias->map(function (Categoria $categoria) {
            $ventas = $categoria->productos->flatMap->ventas;

            $grupoCompradores = $ventas->groupBy('cliente_id')
                ->sortByDesc(fn ($ventasCliente) => $ventasCliente->count());

            $ventasDelComprador = $grupoCompradores->first();
            $primerRegistro = $ventasDelComprador?->first();

            return [
                'categoria' => $categoria,
                'comprador' => $primerRegistro?->cliente,
                'compras' => $ventasDelComprador?->count() ?? 0,
            ];
        });

        $vendedorConMasCategorias = Usuario::where('es_vendedor', true)
            ->withCount('categoriaProductos')
            ->orderByDesc('categoria_productos_count')
            ->first();

        return view('dashboard', [
            'esAdministrador' => $esAdministrador,
            'esGerente' => $esGerente,
            'esCliente' => $esCliente,
            'esVendedor' => $esVendedor,
            'usuario' => $usuario,
            'totalUsuarios' => Usuario::count(),
            'totalVendedores' => Usuario::where('es_vendedor', true)->count(),
            'totalCompradores' => Usuario::where('es_vendedor', false)->count(),
            'totalClientes' => Usuario::where('rol', 'cliente')->count(),
            'productosPorCategoria' => $categorias,
            'productoMasVendido' => $productoMasVendido,
            'compradoresFrecuentesPorCategoria' => $compradoresFrecuentesPorCategoria,
            'ventasPendientes' => $ventasPendientes,
            'productos' => $productos,
            'misProductos' => $esVendedor ? $usuario->productos()->with('categorias')->latest()->take(6)->get() : collect(),
            'misCompras' => $usuario->ventasComoCliente()->count(),
            'misVentas' => $esVendedor ? $usuario->ventasComoVendedor()->count() : 0,
            'vendedorConMasCategorias' => $vendedorConMasCategorias,
        ]);
    }
}
