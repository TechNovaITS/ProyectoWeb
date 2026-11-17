<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alerta;
use Illuminate\Support\Facades\DB;
use App\Models\Departamento;
use App\Models\Usuario;
use App\Models\Asigna;
use App\Http\Requests\AsignaRequest;

class AlertasController extends Controller
{
    public function index()
    {
        $conteoEstados = Alerta::select('estado_seguimiento', DB::raw('count(*) as total'))
        ->groupBy('estado_seguimiento')
        ->pluck('total', 'estado_seguimiento');
        return view('alertas', compact('conteoEstados'));
    }
    public function destroy(Alerta $alerta)
    {
        $alerta->delete();
        return redirect()->route('alertas.index')->with('success', 'Alerta eliminada correctamente.');
    }
    public function asignar(Alerta $alerta)
    {
        $idDepartamento = Departamento::where('nom_departamento', $alerta->departamento)->value('id');
        $usuarios = Usuario::where('rol', 'Operador')
                        ->where('id_departamento', '' . $idDepartamento)
                        ->get();
        return view('alerta-asignar', compact('alerta', 'usuarios'));
    }
    public function store(Alerta $alerta, AsignaRequest $request)
    {
        try {
            $alerta->asigna()->create([
                'ci_usuario' => $request->input('ci_usuario'),
            ]);
            $alerta->estado_seguimiento = 'En Progreso';
            $alerta->save();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error al asignar la alerta.');
        }
            return redirect()->route('alertas.index')->with('success', 'Alerta asignada correctamente.');
    }
    public function progreso()
    {
        return view('alertas-progreso');
    }
    public function update(Alerta $alerta)
    {
        if (Asigna::where('id_alerta', $alerta->id)->where('ci_usuario', auth()->user()->ci)->doesntExist()) {
            return redirect()->back()->withErrors('No tienes permiso para cerrar esta alerta.');
        }
        $alerta->estado_seguimiento = 'Cerrado';
        $alerta->save();
        return redirect()->route('alertas.progreso')->with('success', 'Alerta cerrada correctamente.');
    }
}
