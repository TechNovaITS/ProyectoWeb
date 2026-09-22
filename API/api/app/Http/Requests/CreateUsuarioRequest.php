<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserRole;
use Illuminate\Validation\Rule;

class CreateUsuarioRequest extends ApiRequest
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
            'ci' => 'required|integer|unique:usuarios',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|string|min:8',
            'rol' => ['required', Rule::enum(UserRole::class)],
            'id_departamento' => 'required|integer|exists:departamentos,id',
        ];
    }
    public function messages()
    {
        return [
            'ci.required' => 'El campo CI es obligatorio.',
            'ci.integer' => 'El campo CI debe ser un número entero.',
            'ci.unique' => 'El CI ya está registrado.',
            'email.required' => 'El campo email es obligatorio.',
            'email.email' => 'El campo email debe ser una dirección de correo válida.',
            'email.unique' => 'El email ya está registrado.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.string' => 'El campo contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'rol.required' => 'El campo rol es obligatorio.',
            'rol.enum' => 'El rol seleccionado no es válido.',
            'id_departamento.required' => 'El campo departamento es obligatorio.',
            'id_departamento.integer' => 'El campo departamento debe ser un número entero.',
            'id_departamento.exists' => 'El departamento seleccionado no existe.',
        ];
    }
}
