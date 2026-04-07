<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Listar todos los usuarios (solo administrador)
     */
    public function index()
    {
        $this->authorize('viewAny', Usuario::class);

        Log::info('Usuario ' . auth()->user()->correo . ' accedió a lista de usuarios');

        $usuarios = Usuario::all();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario de creación (solo administrador)
     */
    public function create()
    {
        $this->authorize('create', Usuario::class);
        return view('usuarios.create');
    }

    /**
     * Guardar nuevo usuario (solo administrador)
     */
    public function store(StoreUsuarioRequest $request)
    {
        Log::info('Intento de crear usuario: ' . $request->correo . ' por ' . auth()->user()->correo);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'correo' => $request->correo,
            'clave' => Hash::make($request->clave),
            'rol' => $request->rol,
        ]);

        Log::info('Usuario creado: ' . $usuario->correo . ' con rol: ' . $usuario->rol);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Usuario $usuario)
    {
        $this->authorize('update', $usuario);
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Actualizar usuario
     */
    public function update(UpdateUsuarioRequest $request, Usuario $usuario)
    {
        Log::info('Intento de editar usuario: ' . $usuario->correo . ' por ' . auth()->user()->correo);

        $data = $request->only('nombre', 'apellidos', 'correo', 'rol');

        if ($request->clave) {
            $data['clave'] = Hash::make($request->clave);
        }

        $usuario->update($data);

        Log::info('Usuario actualizado: ' . $usuario->correo);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Eliminar usuario (solo administrador)
     */
    public function destroy(Usuario $usuario)
    {
        $this->authorize('delete', $usuario);

        Log::info('Usuario ' . $usuario->correo . ' eliminado por ' . auth()->user()->correo);

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado');
    }
}
