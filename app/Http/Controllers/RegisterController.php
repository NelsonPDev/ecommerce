<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class RegisterController extends Controller
{
    /**
     * Mostrar formulario de registro
     */
    public function showRegisterForm()
    {
        return view('register');
    }

    /**
     * Procesar registro
     */
    public function register(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,correo',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'El nombre es obligatorio',
            'apellidos.max' => 'Los apellidos no pueden exceder 255 caracteres',
            'email.required' => 'El email es obligatorio',
            'email.unique' => 'Este email ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        // Crear usuario
        Usuario::create([
            'nombre' => $validated['name'],
            'apellidos' => $validated['apellidos'] ?? '',
            'correo' => $validated['email'],
            'clave' => Hash::make($validated['password']),
            'rol' => 'cliente', // Rol por defecto
        ]);

        return redirect()->route('login')->with('success', 'Cuenta creada exitosamente. Inicia sesión.');
    }
}
