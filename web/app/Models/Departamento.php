<?php

namespace App\Models;

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Departamento extends BaseModel
{
    use LogsActivity;
    protected $table = 'departamentos';
    protected $fillable = ['nom_departamento'];

    public function usuarios()
    {
        // El segundo parámetro es la clave foránea en la tabla 'usuarios'
        // El tercer parámetro es la clave primaria en la tabla 'departamentos'
        return $this->hasMany(Usuario::class, 'id_departamento', 'id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('departamentos')
            ->logOnly(['nom_departamento']);
    }
}
