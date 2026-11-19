<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    /**
     * Obtiene el nombre de la conexión de base de datos para el modelo.
     *
     * @return string|null
     */
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