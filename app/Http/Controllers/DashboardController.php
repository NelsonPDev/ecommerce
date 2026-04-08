<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function index()
    {
        $esCliente = Gate::allows('es-cliente');
        $esGerente = Gate::allows('es-gerente');
        $esAdministrador = Gate::allows('es-administrador');

        $hoy = Carbon::today();
        $inicioPeriodo = $hoy->copy()->subDays(6);

        $ventasUltimos7Dias = Venta::query()
            ->selectRaw('fecha, SUM(total) as ingresos, SUM(cantidad) as unidades')
            ->whereBetween('fecha', [$inicioPeriodo->toDateString(), $hoy->toDateString()])
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->keyBy(fn (Venta $venta) => $venta->fecha->format('Y-m-d'));

        $serieVentas = collect(range(0, 6))->map(function (int $offset) use ($inicioPeriodo, $ventasUltimos7Dias) {
            $fecha = $inicioPeriodo->copy()->addDays($offset);
            $venta = $ventasUltimos7Dias->get($fecha->format('Y-m-d'));

            return [
                'label' => $fecha->translatedFormat('d M'),
                'ingresos' => (float) ($venta->ingresos ?? 0),
                'unidades' => (int) ($venta->unidades ?? 0),
            ];
        });

        $productosMasVendidos = Venta::query()
            ->join('productos', 'productos.id', '=', 'ventas.producto_id')
            ->select(
                'productos.nombre',
                DB::raw('SUM(ventas.cantidad) as unidades_vendidas'),
                DB::raw('SUM(ventas.total) as ingresos')
            )
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('unidades_vendidas')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'esAdministrador' => $esAdministrador,
            'esGerente' => $esGerente,
            'esCliente' => $esCliente,
            'totalUsuarios' => Usuario::count(),
            'totalProductos' => Producto::count(),
            'totalClientes' => Usuario::where('rol', 'cliente')->count(),
            'totalCategorias' => Categoria::count(),
            'totalVentas' => Venta::count(),
            'serieVentas' => $serieVentas,
            'maxIngresosVentas' => max((float) $serieVentas->max('ingresos'), 1),
            'totalIngresosSemana' => (float) $serieVentas->sum('ingresos'),
            'totalUnidadesSemana' => (int) $serieVentas->sum('unidades'),
            'productosMasVendidos' => $productosMasVendidos,
            'productos' => $esCliente
                ? Producto::with(['usuario', 'categorias'])->latest()->paginate(9)
                : null,
        ]);
    }
}
