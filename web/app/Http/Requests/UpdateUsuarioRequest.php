<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends FormRequest
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
        $usuario = $this->route('usuario'); // Obtiene el usuario de la ruta
        return [
                'ci' => ['required', 'integer', Rule::unique('usuarios', 'ci')->ignore($usuario->ci, 'ci')],
                'email' => ['required', 'email:rfc,dns', 'ends_with:@cutcsa.com', Rule::unique('usuarios', 'email')->ignore($usuario->ci, 'ci')],
                'password' => ['sometimes', 'nullable', 'string', 'regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/'],
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
            'password.string' => 'El campo contraseña debe ser una cadena de texto.',
            'password.regex' => 'La contraseña debe contener al menos 8 caracteres, incluyendo una letra mayúscula, una minúscula y un número.',
            'id_departamento.required' => 'El campo departamento es obligatorio.',
            'id_departamento.integer' => 'El campo departamento debe ser un número entero.',
            'id_departamento.exists' => 'El departamento seleccionado no existe.',
        ];
    }
}