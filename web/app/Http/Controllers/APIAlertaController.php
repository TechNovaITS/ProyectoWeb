<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alerta;
use Carbon\Carbon;
use App\Models\Dispositivo;
class APIAlertaController extends Controller
{
    public function recibirAlerta(Request $request)
    {
        $data = $request->json()->all();
        // Extraemos el nombre de la instancia del JSON anidado
        $nombreInstancia = $data['alerts'][0]['labels']['instance'] ?? 'Desconocida';

        // 1. Buscamos la instancia por su nombre. Si no existe, la crea y nos devuelve el objeto.
         $instance = Dispositivo::firstOrCreate(['nombre' => $nombreInstancia]);

        // Extraes solo lo importante navegando por el JSON anidado
        $alertaData = [
            'estado'     => $data['status'] ?? null,
            'titulo'     => $data['title'] ?? null,
            'alerta'     => $data['alerts'][0]['labels']['alertname'] ?? null,
            'dispositivo_id'  => $instance->id, // Guardamos el ID de la instancia en lugar del nombre
            'job'        => $data['alerts'][0]['labels']['job'] ?? null,
            'departamento' => $data['alerts'][0]['labels']['Departamento'] ?? null,
            'valor_actual' => $data['alerts'][0]['values']['A'] ?? null,
            'iniciado_en'  => Carbon::parse($data['alerts'][0]['startsAt'])->format('Y-m-d H:i:s') ?? null,
            'finalizado_en'  => Carbon::parse($data['alerts'][0]['endsAt'])->format('Y-m-d H:i:s') ?? null,
            'url_grafana'  => $data['alerts'][0]['generatorURL'] ?? null,
        ];

        // Guardas solo la información limpia y estructurada
        Alerta::create($alertaData);

        return response()->json(['message' => 'Alerta guardada correctamente']);
        }
}

