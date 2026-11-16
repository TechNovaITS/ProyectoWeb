<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dispositivo;
use App\Models\Dashboard;
use App\Http\Requests\UpdateDispositivoRequest;
use App\Http\Requests\CreateDispositivoRequest;

class DispositivosController extends Controller
{

    public function index()
    {
        return view('dispositivos');
    }
    public function show(Dispositivo $dispositivo)
    {  
        $dashboard = Dashboard::find($dispositivo->dashboard_id);
        return view('dispositivo', compact('dashboard', 'dispositivo'));
    }
    public function formCreate()
    {
        $dashboards = Dashboard::orderby('nombre', 'asc')->get();
        return view('dispositivo-formulario', compact('dashboards'));
    }
    public function store(CreateDispositivoRequest $request)
    {
        $datosValidados = $request->validated();

        Dispositivo::create($datosValidados);

        return redirect()->route('dispositivos.index')->with('success', 'Dispositivo creado exitosamente.');
    }
    public function destroy(Dispositivo $dispositivo)
    {
        $dispositivo->delete();
        return redirect()->route('dispositivos.index')->with('success', 'Dispositivo eliminado exitosamente.');
    }
    public function formUpdate(Dispositivo $dispositivo)
    {
        $dashboards = Dashboard::orderby('nombre', 'asc')->get();
        return view('dispositivo-formulario', compact('dispositivo', 'dashboards'));
    }
    public function update(UpdateDispositivoRequest $request, Dispositivo $dispositivo)
    {
        $datosValidados = $request->validated();
        $dispositivo->update($datosValidados);
        return redirect()->route('dispositivos.index')->with('success', 'Dispositivo actualizado exitosamente.');
    }
}