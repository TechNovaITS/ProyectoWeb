<?php

namespace App\Http\Requests;

use Dotenv\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateDepartamentoRequest extends FormRequest
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
            'nom_departamento' => 'required|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'nom_departamento.required' => 'El nombre del departamento es obligatorio.',
            'nom_departamento.string' => 'El nombre del departamento debe ser una cadena de texto.',
            'nom_departamento.max' => 'El nombre del departamento no puede tener más de 100 caracteres.',
        ];
    }
    
}
