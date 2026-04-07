<?php

namespace App\Http\Requests;

use App\Models\Venta;
use Illuminate\Foundation\Http\FormRequest;

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
            'vendedor_id' => 'required|exists:usuarios,id',
            'fecha' => 'required|date',
            'total' => 'required|numeric|min:0',
        ];
    }
}
