<?php

namespace App\Http\Requests;

use App\Models\Categoria;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Categoria::class);
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|min:3|max:255|unique:categorias,nombre',
            'descripcion' => 'required|string|min:5|max:1000',
        ];
    }
}
