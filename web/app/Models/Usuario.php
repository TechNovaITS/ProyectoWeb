<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends BaseModel
{
    use SoftDeletes;
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
}
