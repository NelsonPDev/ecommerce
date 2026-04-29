<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('validate', $this->route('venta')) ?? false;
    }

    public function rules(): array
    {
        return [];
    }
}
