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
        // Si el usuario ya está autenticado, redirigirlo al dashboard
        if (Auth::check()) {
            Log::info('Usuario ya autenticado, redirigiendo al dashboard');
            return redirect()->route('dashboard', ['t' => time()]);
        }

        return response()->view('login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('X-Frame-Options', 'DENY')
            ->header('X-Content-Type-Options', 'nosniff');
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
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El correo debe ser válido',
            'password.required' => 'La contraseña es obligatoria',
        ]);
Log::info('Intentando login con:', [
            'correo' => $credentials['correo'],
            'password_provided' => !empty($credentials['password'])
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

        Log::warning('Login fallido para usuario:', ['correo' => $credentials['correo']]);

        // Si falla la autenticación
        Log::channel('autenticacion')->warning('Login incorrecto', [
            'correo' => $request->email,
            'ip' => $request->ip(),
        ]);
        return back()->withErrors([
            'correo' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('correo');
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
