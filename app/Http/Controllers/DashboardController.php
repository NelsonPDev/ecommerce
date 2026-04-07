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
        
        switch ($user->rol) {
            case 'administrador':
                return view('dashboards.administrador');
            case 'gerente':
                return view('dashboards.gerente');
            case 'cliente':
            default:
                return view('dashboards.cliente');
        }
    }
}
