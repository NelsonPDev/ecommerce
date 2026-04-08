<?php

namespace App\Http\Requests;

use App\Models\Usuario;
use Illuminate\Foundation\Http\FormRequest;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Usuario::class);
    }

    public function rules(): array
    {
        $rolesPermitidos = $this->user()->esGerente()
            ? ['cliente']
            : ['administrador', 'gerente', 'cliente'];

        return [
            'nombre' => 'required|string|min:2|max:255',
            'apellidos' => 'required|string|min:2|max:255',
            'correo' => 'required|email|unique:usuarios,correo',
            'clave' => 'required|string|min:3|confirmed',
            'rol' => ['required', 'in:' . implode(',', $rolesPermitidos)],
        ];
    }
}
