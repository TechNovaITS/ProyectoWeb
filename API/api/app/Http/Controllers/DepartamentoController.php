<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateDepartamentosRequest;
use App\Models\Departamento;
use Exception;
use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;

class DepartamentoController extends Controller
{
    public function index()
    {
        // Lógica para obtener todos los departamentos
        return response()->json(Departamento::all());
    }
    public function store(UpdateDepartamentosRequest $request)
    {
        // Lógica para crear un nuevo departamento

        try {
            $validacionDatos = $request->validated();
            // Crear el departamento en la base de datos con Eloquent
            $departamento = Departamento::create($validacionDatos);
            return response()->json($departamento, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e], 422);
        }
    }

    public function show(Departamento $departamento)
    {
        // Lógica para obtener un departamento específico
        try {
            return response()->json($departamento);
        } catch (Exception $e) {
            return response()->json(['mensaje' => 'Error al obtener el departamento'], 422);
        }
    }

    public function update(UpdateDepartamentosRequest $request, Departamento $departamento)
    {
        // Lógica para actualizar un departamento existente
        try{
        // Actualizar el departamento con los datos validados
        $validacionDatos = $request->validated();
        $departamento->update($validacionDatos);
        return response()->json(['mensaje' => 'Departamento actualizado correctamente'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e], 422);
        }
    }
    public function destroy(Departamento $departamento)
    {
        // Lógica para eliminar un departamento
        $departamento->delete();
        return response()->json(['mensaje' => 'Departamento eliminado correctamente'], 200);
    }
}
