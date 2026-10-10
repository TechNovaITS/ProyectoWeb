<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDepartamentoRequest;
use App\Models\Departamento;
use Illuminate\Database\QueryException;

class DepartamentosController extends Controller
{
    public function index()
    {
        return view('departamentos');
    }
    public function store(CreateDepartamentoRequest $request)
    {
    
    $validacionDatos = $request->validated();
    // Crear el departamento en la base de datos con Eloquent
    Departamento::create($validacionDatos);
    return redirect()->route('departamentos.index')
                    ->with('success', 'Departamento creado exitosamente.');
    
    }
    public function update(CreateDepartamentoRequest $request, Departamento $departamento)
    {
        $validacionDatos = $request->validated();
        // Actualizar el departamento en la base de datos con Eloquent
        $departamento->update($validacionDatos);
        return redirect()->route('departamentos.index')
                         ->with('success', 'Departamento actualizado exitosamente.');
    }
    public function destroy(Departamento $departamento)
    {   
        try {
            $departamento->delete();
            return redirect()->route('departamentos.index')
                         ->with('success', 'Departamento eliminado exitosamente.');
        } catch (QueryException $e) {
            // Manejar la excepción si hay una restricción de clave foránea
            return redirect()->route('departamentos.index')
                             ->with('errors', 'No se puede eliminar el departamento porque está asociado a usuarios.');
        }
    }
}
