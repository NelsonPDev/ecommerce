<?php

namespace App\Http\Requests;

use App\Models\Venta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Venta::class);
    }

    public function rules(): array
    {
        return [
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'fecha' => 'nullable|date',
            'ticket' => 'required|image|max:4096',
            'cliente_id' => [
                Rule::requiredIf($this->user()->esAdministrador()),
                'nullable',
                Rule::exists('usuarios', 'id')->where('rol', 'cliente'),
            ],
        ];
    }
}
