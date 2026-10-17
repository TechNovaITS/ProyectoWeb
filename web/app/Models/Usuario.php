<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';
    protected $fillable = ['ci', 'email', 'password', 'estado', 'rol', 'id_departamento'];
    protected $hidden = ['password', 'remember_token'];
    protected $primaryKey = 'ci';
    protected $keyType = 'integer';

    public function getAuthPassword()
    {
        return $this->password;
    } 
    public function belongsToDepartamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }
}
