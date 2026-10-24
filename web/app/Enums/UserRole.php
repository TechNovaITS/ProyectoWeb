<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'Administrador';
    case Operador = 'Operador';
    
}