<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard según el rol del usuario
     */
    public function index()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'gerente':
                return view('dashboards.gerente');
            case 'empleado':
                return view('dashboards.empleado');
            case 'cliente':
            default:
                return view('dashboards.cliente');
        }
    }
}
