<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUsuarioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('login');
    }

    public function login(LoginUsuarioRequest $request)
    {
        $credentials = [
            'correo' => $request->validated('correo'),
            'password' => $request->validated('clave'),
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            Log::channel('autenticacion')->info('Login exitoso', [
                'usuario_id' => Auth::user()?->id,
                'correo' => $request->validated('correo'),
                'ip' => $request->ip(),
            ]);

            return redirect()->route('dashboard')->with('success', 'Bienvenido al sistema.');
        }

        Log::channel('autenticacion')->warning('Login fallido', [
            'correo' => $request->validated('correo'),
            'ip' => $request->ip(),
        ]);

        return back()->withErrors([
            'correo' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('correo');
    }

    public function logout(Request $request)
    {
        Log::channel('autenticacion')->info('Logout', [
            'usuario_id' => Auth::user()?->id,
            'correo' => Auth::user()?->correo,
            'ip' => $request->ip(),
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Sesion cerrada correctamente.');
    }
}
