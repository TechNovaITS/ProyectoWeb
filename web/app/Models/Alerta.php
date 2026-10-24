<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    protected $table = 'alertas';

    protected $fillable = [
        'estado',
        'titulo',
        'alerta',
        'dispositivo_id',
        'job',
        'departamento',
        'valor_actual',
        'iniciado_en',
        'finalizado_en',
        'url_grafana',
    ];
    public function belongstoDispositivo()
    {
        return $this->belongsTo(Dispositivo::class);
    }
}
