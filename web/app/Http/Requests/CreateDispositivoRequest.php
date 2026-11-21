<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Dispositivo;

class CreateDispositivoRequest extends FormRequest
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
        $conexion = (new Dispositivo())->getConnectionName();
        return [
            'nombre' => ['required', 'string', 'max:100', "unique:{$conexion}.dispositivos,nombre"],
            'ip' => ['required', 'string', 'max:45'],
            'dashboard_id' => ["required", "exists:{$conexion}.dashboards,id"],
        ];
    }
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del dispositivo es obligatorio.',
            'nombre.string' => 'El nombre del dispositivo debe ser una cadena de texto.',
            'nombre.max' => 'El nombre del dispositivo no debe exceder los 100 caracteres.',
            'nombre.unique' => 'El nombre del dispositivo ya está en uso.',
            'ip.required' => 'La dirección IP es obligatoria.',
            'ip.string' => 'La dirección IP debe ser una cadena de texto.',
            'ip.max' => 'La dirección IP no debe exceder los 45 caracteres.',
            'dashboard_id.required' => 'El ID del dashboard es obligatorio.',
            'dashboard_id.exists' => 'El ID del dashboard proporcionado no existe.',
        ];
    }
}
