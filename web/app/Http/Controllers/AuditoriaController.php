<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class AuditoriaController extends Controller
{
    public function actividad(){
        return view('auditoria-actividad');
    }
    public function showActividad(Activity $actividad){
        return view('actividad', compact('actividad'));
    }
}
