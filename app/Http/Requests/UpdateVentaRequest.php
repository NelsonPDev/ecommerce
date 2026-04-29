<?php

namespace App\Http\Requests;

use App\Models\Venta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('venta'));
    }

    public function rules(): array
    {
        return [
            'cantidad' => 'required|integer|min:1',
            'vendedor_id' => [
                'required',
                Rule::exists('usuarios', 'id')->where('es_vendedor', true),
            ],
            'fecha' => 'required|date',
            'total' => 'required|numeric|min:0',
            'ticket' => 'nullable|image|max:4096',
        ];
    }
}
