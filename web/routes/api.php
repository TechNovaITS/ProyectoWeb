<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIAlertaController;

// URL completa: http://127.0.0.1:8000/api/alerta
Route::post('/alerta', [APIAlertaController::class, 'recibirAlerta']);
