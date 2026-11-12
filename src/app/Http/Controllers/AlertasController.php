<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alerta;
use Illuminate\Support\Facades\DB;

class AlertasController extends Controller
{
    public function index()
    {
        $conteoEstados = Alerta::select('estado_seguimiento', DB::raw('count(*) as total'))
        ->groupBy('estado_seguimiento')
        ->pluck('total', 'estado_seguimiento');
        return view('alertas', compact('conteoEstados'));
    }
}
