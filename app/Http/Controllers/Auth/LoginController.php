<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'nullable|email',
            'email' => 'required_without:correo|email',
            'clave' => 'nullable',
            'password' => 'required_without:clave',
        ], [], [
            'correo' => 'correo',
            'email' => 'correo',
            'clave' => 'contraseña',
            'password' => 'contraseña',
        ]);

        $correo = $request->input('correo', $request->input('email'));
        $clave = $request->input('clave', $request->input('password'));

        if (Auth::attempt(['correo' => $correo, 'password' => $clave])) {
            $usuario = Auth::user();
            Log::channel('autenticacion')->info('Login exitoso', [
                'usuario_id' => $usuario->id,
                'correo' => $usuario->correo,
                'ip' => $request->ip(),
            ]);
            return redirect()->intended('dashboard');
        }

        Log::channel('autenticacion')->warning('Login fallido', [
            'correo' => $correo,
            'ip' => $request->ip(),
        ]);

        return back()->withErrors(['correo' => 'Credenciales incorrectas']);
    }

    public function logout(Request $request)
    {
        $usuario = Auth::user();
        Log::channel('autenticacion')->info('Logout', [
            'usuario_id' => $usuario->id,
            'correo' => $usuario->correo,
            'ip' => $request->ip(),
        ]);

        Auth::logout();
        return redirect('/');
    }
}
