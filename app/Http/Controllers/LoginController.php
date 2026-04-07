<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Usuario;

class LoginController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        // Validar datos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser válido',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        $credentials = [
            'correo' => $request->email,
            'password' => $request->password,
        ];


        // Intentar autenticar
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            Log::channel('autenticacion')->info('Login exitoso', [
                'usuario_id' => Auth::id(),
                'correo' => $request->email,
                'ip' => $request->ip(),
            ]);
            return redirect('/productos')->with('success', 'Bienvenido!');
        }

        // Si falla la autenticación
        Log::channel('autenticacion')->warning('Login incorrecto', [
            'correo' => $request->email,
            'ip' => $request->ip(),
        ]);
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Log::channel('autenticacion')->info('Logout', [
            'usuario_id' => Auth::id(),
            'correo' => Auth::user()->correo ?? 'unknown',
            'ip' => $request->ip(),
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Sesión cerrada correctamente');
    }
}
