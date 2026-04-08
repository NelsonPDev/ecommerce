<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Usuario::class);

        $usuarios = auth()->user()->esAdministrador()
            ? Usuario::latest()->get()
            : Usuario::where('rol', 'cliente')->latest()->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $this->authorize('create', Usuario::class);

        $rolesDisponibles = auth()->user()->esGerente()
            ? ['cliente' => 'Cliente']
            : [
                'administrador' => 'Administrador',
                'gerente' => 'Gerente',
                'cliente' => 'Cliente',
            ];

        return view('usuarios.create', compact('rolesDisponibles'));
    }

    public function store(StoreUsuarioRequest $request)
    {
        $data = $request->validated();

        $usuario = Usuario::create([
            'nombre' => $data['nombre'],
            'apellidos' => $data['apellidos'],
            'correo' => $data['correo'],
            'clave' => Hash::make($data['clave']),
            'rol' => $data['rol'],
        ]);

        Log::info('Usuario creado', [
            'usuario_id' => $usuario->id,
            'correo' => $usuario->correo,
            'rol' => $usuario->rol,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function show(Usuario $usuario)
    {
        $this->authorize('view', $usuario);

        return view('usuarios.show', compact('usuario'));
    }

    public function edit(Usuario $usuario)
    {
        $this->authorize('update', $usuario);

        $rolesDisponibles = auth()->user()->esGerente()
            ? ['cliente' => 'Cliente']
            : [
                'administrador' => 'Administrador',
                'gerente' => 'Gerente',
                'cliente' => 'Cliente',
            ];

        return view('usuarios.edit', compact('usuario', 'rolesDisponibles'));
    }

    public function update(UpdateUsuarioRequest $request, Usuario $usuario)
    {
        $data = $request->validated();

        if (! empty($data['clave'])) {
            $data['clave'] = Hash::make($data['clave']);
        } else {
            unset($data['clave']);
        }

        $usuario->update($data);

        Log::info('Usuario actualizado', [
            'usuario_id' => $usuario->id,
            'correo' => $usuario->correo,
            'rol' => $usuario->rol,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Usuario $usuario)
    {
        $this->authorize('delete', $usuario);

        Log::info('Usuario eliminado', [
            'usuario_id' => $usuario->id,
            'correo' => $usuario->correo,
        ]);

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
