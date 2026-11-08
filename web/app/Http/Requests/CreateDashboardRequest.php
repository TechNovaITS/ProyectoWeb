<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateDashboardRequest extends FormRequest
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
            'nombre' => 'required|string|max:100',
            'url' => 'required|string|max:255|url',
        ];
    }
    public function messages()
    {
        return [
            'nombre.required' => 'El nombre del dashboard es obligatorio.',
            'nombre.string' => 'El nombre del dashboard debe ser una cadena de texto.',
            'nombre.max' => 'El nombre del dashboard no puede tener más de 100 caracteres.',
            'url.required' => 'La URL del dashboard es obligatoria.',
            'url.string' => 'La URL del dashboard debe ser una cadena de texto.',
            'url.max' => 'La URL del dashboard no puede tener más de 255 caracteres.',
            'url.url' => 'La URL del dashboard debe ser una URL válida.',
        ];
    }
}
