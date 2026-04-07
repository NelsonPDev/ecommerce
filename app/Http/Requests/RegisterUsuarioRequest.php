<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|min:2|max:255',
            'apellidos' => 'required|string|min:2|max:255',
            'correo' => 'required|email|unique:usuarios,correo',
            'clave' => 'required|string|min:3|confirmed',
        ];
    }
}
