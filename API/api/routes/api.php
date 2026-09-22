<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\DispositivoController;
use App\Http\Controllers\UsuarioController;

// Las rutas son api/ruta y el puerto del docker de desarrollo es 8000
// Por ejemplo localhost:8000/api/departamentos

Route::apiResource('/departamentos', DepartamentoController::class);
Route::apiResource('/usuarios', UsuarioController::class);
Route::apiResource('/dispositivos', DispositivoController::class);