<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateDispositivoRequest extends ApiRequest
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
            'ip_dispositivo' => 'required|string|max:45',
            'codigo_embebido' => 'required|string|max:50'
        ];
    }
    public function messages()
    {
        return [
            'nombre.required' => 'El nombre del dispositivo es obligatorio.',
            'nombre.string' => 'El nombre del dispositivo debe ser una cadena de texto.',
            'nombre.max' => 'El nombre del dispositivo no puede tener más de 100 caracteres.',
            'ip_dispositivo.required' => 'La dirección IP del dispositivo es obligatoria.',
            'ip_dispositivo.string' => 'La dirección IP del dispositivo debe ser una cadena de texto.',
            'ip_dispositivo.max' => 'La dirección IP del dispositivo no puede tener más de 45 caracteres.',
            'codigo_embebido.required' => 'El código embebido del dispositivo es obligatorio.',
            'codigo_embebido.string' => 'El código embebido del dispositivo debe ser una cadena de texto.',
            'codigo_embebido.max' => 'El código embebido del dispositivo no puede tener más de 50 caracteres.',
        ];
    }
}
