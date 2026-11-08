<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dispositivo;

class DispositivosController extends Controller
{

    public function index()
    {
        return view('dispositivos');
    }
    public function show($id)
    {
    
    $grafanaUrl = env('GRAFANA_URL');
    $dispositivo = Dispositivo::find($id); 
    
    return view('dispositivo', compact('grafanaUrl', 'dispositivo'));
    }
    public function formCreate()
    {
        return view('dispositivo-formulario');
    }
}
