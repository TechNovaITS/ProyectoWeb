<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function index()
    {
        // Lógica para obtener todos los usuatios
        return response()->json(Usuario::all());
    }
    public function show(Usuario $usuario)
    {
        // Lógica para obtener un usuario específico
        return response()->json($usuario);
    }
    public function store(CreateUsuarioRequest $request)
    {
        // Lógica para crear un nuevo usuario
        $validacionDatos = $request->validated();
        $usuario = Usuario::create($validacionDatos);
        return response()->json(['mensaje' => 'Usuario creado correctamente'], 201);
    }
    public function update(UpdateUsuarioRequest $request, Usuario $usuario)
    {
        // Lógica para actualizar un usuario existente
        try {
        $validacionDatos = $request->validated();
        $usuario->update($validacionDatos);
        return response()->json(['mensaje' => 'Usuario actualizado'], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => 'Error al actualizar el usuario'], 422);
        }
    }
    public function destroy(Usuario $usuario)
    {
        // Lógica para eliminar un usuario existente
        $usuario->delete();
        return response()->json(['mensaje' => 'Usuario eliminado'], 204);
    }
}
