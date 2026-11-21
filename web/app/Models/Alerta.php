<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Alerta extends BaseModel
{
    use SoftDeletes;
    use LogsActivity;
    // Solo se registrará automáticamente el evento "deleted"
    protected static $recordEvents = ['deleted'];
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
        'sumario',
        'descripcion',
        'manual',
    ];
    public function belongstoDispositivo()
    {
        return $this->belongsTo(Dispositivo::class, 'dispositivo_id');
    }
    public function asigna()
    {
        // El segundo parámetro es la clave foránea en la tabla 'usuarios'
        // El tercer parámetro es la clave primaria en la tabla 'departamentos'
        return $this->hasMany(Asigna::class, 'id_alerta', 'id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('alertas')
            ->logOnly(['id', 'alerta','estado_seguimiento', 'titulo']);
    }
}
