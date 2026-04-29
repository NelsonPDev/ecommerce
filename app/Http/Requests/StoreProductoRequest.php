<?php

namespace App\Http\Requests;

use App\Models\Producto;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Producto::class);
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|min:3|max:255',
            'descripcion' => 'required|string|min:5|max:2000',
            'precio' => 'required|numeric|min:0',
            'existencia' => 'required|integer|min:0',
            'vendedor_nombre' => 'required|string|min:2|max:255',
            'vendedor_apellidos' => 'required|string|min:2|max:255',
            'fotos' => 'required|array|min:1|max:5',
            'fotos.*' => 'image|max:4096',
            'categorias' => 'required|array|min:1',
            'categorias.*' => 'exists:categorias,id',
        ];
    }
}
