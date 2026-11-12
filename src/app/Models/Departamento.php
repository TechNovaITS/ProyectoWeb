<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Departamento extends Model
{
    protected $table = 'departamentos';
    protected $fillable = ['nom_departamento'];
    public function usuarios()
    {
        // El segundo parámetro es la clave foránea en la tabla 'usuarios'
        // El tercer parámetro es la clave primaria en la tabla 'departamentos'
        return $this->hasMany(Usuario::class, 'id_departamento', 'id');
    }
}
