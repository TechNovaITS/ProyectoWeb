<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispositivo extends BaseModel
{
    protected $table = 'dispositivos';

    protected $fillable = [
        'nombre',
        'ip',
        'dashboard_id'
    ];
    public function belongsToDashboard()
    {
        return $this->belongsTo(Dashboard::class, 'dashboard_id');
    }

}
