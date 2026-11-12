<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    protected $table = 'dashboards';
    protected $fillable = ['nombre', 'url'];
    public function dispositivos()
    {
        return $this->hasMany(Dispositivo::class, 'id_dashboard', 'id');
    }
}
