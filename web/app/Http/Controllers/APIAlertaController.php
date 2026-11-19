<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alerta;
use Carbon\Carbon;
use App\Models\Dispositivo;
class APIAlertaController extends Controller
{
    /**
     * Recibe una alerta, guarda el JSON original en un archivo de texto
     * y registra sus datos estructurados.
     */
    public function recibirAlerta(Request $request)
    {
        
        file_put_contents(
            storage_path('app/alerta_recibida.txt'),
            $request->getContent() . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );

        $data = $request->json()->all();
        // Extraemos el nombre de la instancia del JSON anidado
        $nombreInstancia = $data['alerts'][0]['labels']['instance'] ?? 'Desconocida';

        // 1. Buscamos la instancia por su nombre. Si no existe, la crea y nos devuelve el objeto.
         $instance = Dispositivo::on('api_web')->where('nombre', $nombreInstancia)->first();

        // Extraes solo lo importante navegando por el JSON anidado
        $alertaData = [
            'estado'     => $data['status'] ?? null,
            'titulo'     => $data['title'] ?? null,
            'alerta'     => $data['alerts'][0]['labels']['alertname'] ?? null,
            'dispositivo_id'  => $instance ? $instance->id : null, // Guardamos el ID de la instancia en lugar del nombre
            'job'        => $data['alerts'][0]['labels']['job'] ?? null,
            'departamento' => $data['alerts'][0]['labels']['Departamento'] ?? null,
            'valor_actual' => $data['alerts'][0]['values']['A'] ?? null,
            'sumario' => $data['alerts'][0]['annotations']['summary'] ?? null,
            'descripcion' => $data['alerts'][0]['annotations']['description'] ?? null,
            'manual' => $data['alerts'][0]['annotations']['runbook_url'] ?? null,
            'iniciado_en'  => Carbon::parse($data['alerts'][0]['startsAt'])->format('Y-m-d H:i:s') ?? null,
            'finalizado_en'  => Carbon::parse($data['alerts'][0]['endsAt'])->format('Y-m-d H:i:s') ?? null,
            'url_grafana'  => $data['alerts'][0]['generatorURL'] ?? null,
        ];

        // Guardas solo la información limpia y estructurada
        Alerta::create($alertaData);

        return response()->json(['message' => 'Alerta guardada correctamente']);
        }
}

