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
        return [
            'ci' => 'sometimes|integer',
            'email' => 'sometimes|email',
            'password' => 'sometimes|string|min:8',
            'rol' => ['sometimes', Rule::enum(UserRole::class)],
            'id_departamento' => 'sometimes|integer|exists:departamentos,id',
        ];
    }
}
