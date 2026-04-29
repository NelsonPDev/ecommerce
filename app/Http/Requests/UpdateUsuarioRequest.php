<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('usuario'));
    }

    public function rules(): array
    {
        $rolesPermitidos = $this->user()->esGerente()
            ? ['cliente']
            : ['administrador', 'gerente', 'cliente'];

        return [
            'nombre' => 'required|string|min:2|max:255',
            'apellidos' => 'required|string|min:2|max:255',
            'correo' => ['required', 'email', Rule::unique('usuarios', 'correo')->ignore($this->route('usuario'))],
            'clave' => 'nullable|string|min:3|confirmed',
            'rol' => ['required', Rule::in($rolesPermitidos)],
            'es_vendedor' => 'nullable|boolean',
        ];
    }
}
