<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use App\Services\AdminDashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(AdminDashboardService $adminDashboardService): View
    {
        $usuario = auth()->user();
        $esAdministrador = $usuario->esAdministrador();
        $esGerente = $usuario->esGerente();
        $esCliente = $usuario->esCliente();
        $esVendedor = $usuario->esVendedor();
        $puedeVerEstadisticas = $usuario->can('viewStatistics', Usuario::class);

        $productos = Producto::with(['usuario', 'categorias', 'ventas'])->latest()->paginate(9);
        $ventasPendientes = Venta::with(['producto', 'cliente', 'vendedor'])
            ->where('estado', 'pendiente')
            ->latest()
            ->take(8)
            ->get();

        $estadisticasAdministrador = $puedeVerEstadisticas
            ? $adminDashboardService->build()
            : [];

        return view('dashboard', [
            'esAdministrador' => $esAdministrador,
            'esGerente' => $esGerente,
            'esCliente' => $esCliente,
            'esVendedor' => $esVendedor,
            'usuario' => $usuario,
            'ventasPendientes' => $ventasPendientes,
            'productos' => $productos,
            'misProductos' => $esVendedor ? $usuario->productos()->with('categorias')->latest()->take(6)->get() : collect(),
            'misCompras' => $usuario->ventasComoCliente()->count(),
            'misVentas' => $esVendedor ? $usuario->ventasDeSusProductos()->count() : 0,
        ] + $estadisticasAdministrador);
    }
}
