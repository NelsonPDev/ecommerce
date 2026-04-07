<?php

namespace App\Http\Requests;

use App\Models\Categoria;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('categoria'));
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('categorias', 'nombre')->ignore($this->route('categoria')),
            ],
            'descripcion' => 'required|string|min:5|max:1000',
        ];
    }
}
