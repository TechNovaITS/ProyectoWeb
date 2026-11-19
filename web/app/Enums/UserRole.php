<?php

namespace App\Enums;

enum UserRole: string
{
    case Auditor = 'Auditor';
    case Operador = 'Operador';
    case Administrador = 'Administrador';
    
}