<?php

namespace App\Http\Requests;

use App\Models\Producto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('buy', Producto::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'cantidad' => 'required|integer|min:1',
        ];
    }
}
