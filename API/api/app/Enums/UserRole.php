<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'Administrador';
    case Operador = 'Operador';
    case Auditor = 'Auditor';
    case Recovery = 'Recovery';
}