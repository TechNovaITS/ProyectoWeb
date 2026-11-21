<?php

namespace App\Models;

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispositivo extends BaseModel
{
    use LogsActivity;
    use SoftDeletes;
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
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('dispositivos')
            ->logOnly(['nombre', 'ip', 'dashboard_id']);
    }

}
