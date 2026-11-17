<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asigna extends Model
{
    protected $table = "asigna";
    protected $fillable = ['ci_usuario', 'id_aleta'];
    protected $primaryKey = 'id_alerta';

    public function belongstoAlerta()
    {
        return $this->belongsTo(Alerta::class, 'id_alerta');
    }
    public function belongstoUsuario()
    {
        return $this->belongsTo(Usuario::class, 'ci_usuario');
    }
}
