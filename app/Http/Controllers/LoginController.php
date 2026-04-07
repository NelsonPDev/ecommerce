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
        $credentials = $request->validate([
            'correo' => 'required|email',
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

        // Intentar autenticar usando el campo correo en la tabla usuarios
        $user = Usuario::where('correo', $credentials['correo'])->first();
        
        if ($user && Hash::check($credentials['password'], $user->clave)) {
            Log::info('Login exitoso para usuario:', ['correo' => $credentials['correo'], 'user_id' => $user->id]);
            Auth::login($user);
            $request->session()->regenerate();
            
            Log::info('Después de Auth::login, Auth::check():', ['check' => Auth::check()]);
            Log::info('Usuario en Auth::user():', ['user' => Auth::user() ? Auth::user()->correo : 'null']);
            
            // Verificar que el usuario esté autenticado
            if (Auth::check()) {
                Log::info('Usuario autenticado correctamente, redirigiendo al dashboard');
                
                // Debug: verificar que la ruta existe
                try {
                    $url = route('dashboard');
                    Log::info('URL del dashboard: ' . $url);
                    
                    return redirect()->route('dashboard', ['t' => time()])->with('success', 'Bienvenido!')
                        ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', '0')
                        ->header('X-Frame-Options', 'DENY')
                        ->header('X-Content-Type-Options', 'nosniff');
                } catch (\Exception $e) {
                    Log::error('Error al generar ruta dashboard: ' . $e->getMessage());
                    return response()->json(['error' => 'Ruta dashboard no encontrada'], 500);
                }
            } else {
                Log::error('Usuario no está autenticado después del login manual');
                return back()->withErrors(['correo' => 'Error interno de autenticación']);
            }
        }

        Log::warning('Login fallido para usuario:', ['correo' => $credentials['correo']]);

        // Si falla la autenticación
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
