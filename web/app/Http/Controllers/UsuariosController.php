<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUsuarioRequest;
use App\Models\Departamento;
use App\Models\Usuario;

class UsuariosController extends Controller
{
    public function index() {
        return view('usuarios');
    }
    public function formCreate() {
        $departamentos = Departamento::orderBy('nom_departamento', 'asc')->get();
        return view('usuario-formulario', compact('departamentos'));
    }
    public function store(CreateUsuarioRequest $request)
    {
        // Lógica para crear un nuevo usuario
        $validacionDatos = $request->validated();
        Usuario::create($validacionDatos);
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }
}
