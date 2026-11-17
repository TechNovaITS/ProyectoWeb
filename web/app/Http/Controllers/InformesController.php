<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alerta;
use App\Models\Informe;
use App\Models\Asigna;
use App\Http\Requests\InformesRequest;


class InformesController extends Controller
{
    public function formCreate(Alerta $alerta) {
        return view('informe', compact('alerta'));
    }
    public function store(Asigna $asigna, Request $request) {
        $validatedData = $request->validate([
            'causa' => 'required|string|max:16383',
            'resolucion' => 'required|string|max:16383',
        ]);

        if ($asigna->ci_usuario !== auth()->user()->ci) {
            return redirect()->back()->withErrors('No tienes permiso para crear un informe para esta alerta.');
        }
        Informe::create([
            'id_alerta' => $asigna->id_alerta,
            'causa' => $validatedData['causa'],
            'resolucion' => $validatedData['resolucion'],
        ]);

        return redirect()->route('alertas.progreso')->with('success', 'Informe creado exitosamente.');
    }
    public function index() {
        return view('informes');
    }
    public function formUpdate(Informe $informe) {
        $alerta = Alerta::find($informe->id_alerta);
        return view('informe', compact('informe', 'alerta'));
    }
    public function update(Informe $informe, InformesRequest $request) {
        if ($informe->asigna->ci_usuario !== auth()->user()->ci) {
            return redirect()->back()->withErrors('No tienes permiso para actualizar este informe.');
        }
        $validatedData = $request->validated();
        $informe->update($validatedData);

        return redirect()->route('alertas.informesindex')->with('success', 'Informe actualizado exitosamente.');
    }

    public function destroy(Informe $informe) {
        if ($informe->asigna->ci_usuario !== auth()->user()->ci) {
            return redirect()->back()->withErrors('No tienes permiso para eliminar este informe.');
        }
        $informe->delete();
        return redirect()->route('alertas.informesindex')->with('success', 'Informe eliminado exitosamente.');
    }
}
