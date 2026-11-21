<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Usuario extends BaseModel
{
    use SoftDeletes;
    use LogsActivity;
    protected $table = 'usuarios';
    protected $fillable = ['ci', 'email', 'password', 'estado', 'rol', 'id_departamento'];
    protected $hidden = ['password', 'remember_token'];
    protected $primaryKey = 'ci';
    protected $keyType = 'integer';

    public function belongsToDepartamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }
    protected function casts(): array
    {
        return [
            'password' => 'hashed', // <-- Esto hashea la contraseña automáticamente
        ];
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('usuarios')
            ->logOnly(['ci', 'email', 'rol', 'id_departamento']);
    }
    
}
