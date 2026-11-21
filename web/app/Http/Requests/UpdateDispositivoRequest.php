<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;   
use Illuminate\Validation\Rule;
use App\Models\Dispositivo;

class UpdateDispositivoRequest extends FormRequest
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
        $dispositivo = $this->route('dispositivo'); // Obtiene el dispositivo de la ruta
        return [
            'nombre' => ['required', 'string', 'max:100', Rule::unique('dispositivos', 'nombre')->ignore($dispositivo->id, 'id')],
            'ip' => ['required', 'string', 'max:45'],
            'dashboard_id' => "required|exists:{$conexion}.dashboards,id",
        ];
    }
}
