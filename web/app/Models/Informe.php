<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Informe extends BaseModel
{
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
}
