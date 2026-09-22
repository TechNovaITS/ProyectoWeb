<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model
{
    use SoftDeletes;
    protected $fillable = ['ci', 'email', 'password', 'rol', 'id_departamento'];
    protected $hidden = ['password'];
    protected $primaryKey = 'ci';
    protected $keyType = 'integer';
}
