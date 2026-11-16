<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AsignaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ci_usuario' => 'required|exists:usuarios,ci'
        ];
    }
    public function messages(): array
    {
        return [
            'ci_usuario.required' => 'El campo ci_usuario es obligatorio.',
            'ci_usuario.exists' => 'El ci_usuario proporcionado no existe en la tabla usuarios.',
        ];
    }
}
