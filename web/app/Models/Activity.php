<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    public function getConnectionName()
    {
        // Si hay un usuario autenticado y tiene un rol, usamos su rol en minúsculas
        if (auth()->check() && auth()->user()->rol) {
        return auth()->user()->rol;
        }

        // De lo contrario, usamos la conexión por defecto del modelo o la global
        return $this->connection ?? parent::getConnectionName();
    }
}