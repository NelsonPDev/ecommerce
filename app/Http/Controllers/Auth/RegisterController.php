<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuarios,correo',
            'clave' => 'required|min:6|confirmed',
            'rol' => 'required|in:cliente,gerente', // Solo clientes y gerentes pueden registrarse
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'correo' => $request->correo,
            'clave' => Hash::make($request->clave),
            'rol' => $request->rol,
        ]);

        Auth::login($usuario);

        Log::channel('autenticacion')->info('Registro exitoso', [
            'usuario_id' => $usuario->id,
            'correo' => $usuario->correo,
        ]);

        return redirect()->intended('dashboard');
    }
}
