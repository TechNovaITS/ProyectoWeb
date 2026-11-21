<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Informe extends BaseModel
{
    use LogsActivity;
    use SoftDeletes;
    protected $table = 'informes';
    protected $fillable = [
        'id_alerta',
        'causa',
        'resolucion',
    ];
    public function asigna()
    {
        return $this->belongsTo(Asigna::class, 'id_alerta');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('informes')
            ->logOnly(['id_alerta', 'causa', 'resolucion']);
    }
}
