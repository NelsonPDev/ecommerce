<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUsuarioRequest;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(RegisterUsuarioRequest $request)
    {
        $data = $request->validated();

        Usuario::create([
            'nombre' => $data['nombre'],
            'apellidos' => $data['apellidos'],
            'correo' => $data['correo'],
            'clave' => Hash::make($data['clave']),
            'rol' => 'cliente',
        ]);

        Log::channel('autenticacion')->info('Registro exitoso', [
            'correo' => $data['correo'],
            'ip' => $request->ip(),
        ]);

        return redirect()->route('login')->with('success', 'Cuenta creada correctamente. Ahora puedes iniciar sesion.');
    }
}
