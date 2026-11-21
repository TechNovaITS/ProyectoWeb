<?php

namespace App\Models;

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Dashboard extends BaseModel
{
    use LogsActivity;
    protected $table = 'dashboards';
    protected $fillable = ['nombre', 'url'];
    public function dispositivos()
    {
        return $this->hasMany(Dispositivo::class, 'id_dashboard', 'id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('dashboards')
            ->logOnly(['nombre', 'url']);
    }
}
