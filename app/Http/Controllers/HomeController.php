<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Mostrar la página principal
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Mostrar la página "Quiénes Somos"
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Mostrar la página de contacto
     */
    public function contact()
    {
        return view('contact');
    }
}
