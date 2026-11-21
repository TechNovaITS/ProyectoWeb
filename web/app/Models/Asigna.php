<?php

namespace App\Models;

use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Asigna extends BaseModel
{
    use LogsActivity;

    protected $table = 'asigna';
    protected $fillable = ['ci_usuario', 'id_alerta'];
    protected $primaryKey = 'id_alerta';

    // Descomenta si id_alerta no es autoincremental en esta tabla:
    // public $incrementing = false;

    public function belongstoAlerta()
    {
        return $this->belongsTo(Alerta::class, 'id_alerta');
    }

    public function belongstoUsuario()
    {
        return $this->belongsTo(Usuario::class, 'ci_usuario');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('asignaciones')
            ->logOnly(['ci_usuario', 'id_alerta']);
    }
}
