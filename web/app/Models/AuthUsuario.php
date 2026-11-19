<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class AuthUsuario extends Authenticatable
{
    protected $table = 'usuarios';
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
