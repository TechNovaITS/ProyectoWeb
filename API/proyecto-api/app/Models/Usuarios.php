<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $fillable = ['ci', 'email', 'password', 'rol', 'departamento_id'];
    protected $hidden = ['password'];
}
