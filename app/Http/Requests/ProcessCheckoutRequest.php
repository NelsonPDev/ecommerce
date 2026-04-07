<?php

namespace App\Http\Requests;

use App\Models\Producto;
use Illuminate\Foundation\Http\FormRequest;

class ProcessCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('buy', Producto::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'titular_tarjeta' => 'required|string|min:3|max:255',
            'numero_tarjeta' => 'required|string|min:8|max:19',
            'expiracion' => 'required|string|min:4|max:5',
            'cvv' => 'required|string|min:3|max:4',
        ];
    }
}
