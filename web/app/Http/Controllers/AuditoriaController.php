<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Alerta;
use App\Models\Informe;
use App\Models\Asigna;

class AuditoriaController extends Controller
{
    public function actividad(){
        return view('auditoria-actividad');
    }
    public function showActividad(Activity $actividad){
        return view('actividad', compact('actividad'));
    }
    public function alertas(){
        return view('auditoria-alertas');
    }
    public function showAlerta(Alerta $alerta){
        return view('alerta-asignar', compact('alerta'));
    }
    public function informes(){
        return view('auditoria-informes');
    }
    public function showInforme(Informe $informe){
        $asigna = Asigna::find($informe->id_alerta)->first();
        return view('informe', compact('informe', 'asigna'));
    }
}
