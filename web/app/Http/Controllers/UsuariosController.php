<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
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
    public function formUpdate(Usuario $usuario) {
        if (($usuario->rol === 'Administrador' || $usuario->rol ==='Recovery') && auth()->user()->rol === 'Administrador') {
            return redirect()->route('usuarios.index')->withErrors('Solo puedes editar usuarios con el rol esperado.');
        }
        else if (($usuario->rol === 'Operador' || $usuario->rol ==='Auditor' || $usuario->rol ==='Recovery') && auth()->user()->rol === 'Recovery') {
            return redirect()->route('usuarios.index')->withErrors('Solo puedes editar usuarios Administradores.');
        }
        $departamentos = Departamento::orderBy('nom_departamento', 'asc')->get();
        return view('usuario-formulario', compact('usuario', 'departamentos'));
    }
    public function update(UpdateUsuarioRequest $request, Usuario $usuario)
    {
        if (($usuario->rol === 'Administrador' || $usuario->rol ==='Recovery') && auth()->user()->rol === 'Administrador') {
            return redirect()->route('usuarios.index')->withErrors('Solo puedes editar usuarios con el rol esperado.');
        }
        else if (($usuario->rol === 'Operador' || $usuario->rol ==='Auditor' || $usuario->rol ==='Recovery') && auth()->user()->rol === 'Recovery') {
            return redirect()->route('usuarios.index')->withErrors('Solo puedes editar usuarios Administradores.');
        }
        // Lógica para actualizar un usuario existente
        $validacionDatos = $request->validated();
        if (empty($validacionDatos['password'])) {
        unset($validacionDatos['password']);
        }
        $usuario->update($validacionDatos);
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }
    public function destroy(Usuario $usuario)
    {
        // Lógica para eliminar un usuario
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
