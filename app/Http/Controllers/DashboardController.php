<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'esAdministrador' => Gate::allows('es-administrador'),
            'esGerente' => Gate::allows('es-gerente'),
            'esCliente' => Gate::allows('es-cliente'),
            'totalUsuarios' => Usuario::count(),
            'totalProductos' => Producto::count(),
            'totalCategorias' => Categoria::count(),
            'totalVentas' => Venta::count(),
        ]);
    }
}
