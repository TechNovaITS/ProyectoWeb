<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartamentosController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\DispositivosController;
use App\Http\Controllers\AlertasController;
use App\Http\Controllers\InformesController;

Route::get('/', function () {
   if (!Auth::check()) {
        return redirect()->route('login');
    } else {
        
        return match (auth()->user()->rol) {
        'Administrador'   => redirect()->route('alertas.index'),
        'Operador'  => redirect()->route('editor.dashboard'),
        'Auditor' => redirect()->route('cliente.home'),
        'Recovery'   => redirect()->route('home'),
    };
    }
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest'); // Solo accesible para usuarios no autenticados
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


// Departamentos
Route::get('/administracion/departamentos', [DepartamentosController::class, 'index'])->name('departamentos.index')->middleware('auth', 'rol:Administrador');
Route::post('/administracion/departamentos', [DepartamentosController::class, 'store'])->name('departamentos.store')->middleware('auth', 'rol:Administrador');
Route::put('/administracion/departamentos/{departamento}', [DepartamentosController::class, 'update'])->name('departamentos.update')->middleware('auth', 'rol:Administrador');
Route::delete('/administracion/departamentos/{departamento}', [DepartamentosController::class, 'destroy'])->name('departamentos.destroy')->middleware('auth', 'rol:Administrador');
// Usuarios
Route::get('/administracion/usuarios', [UsuariosController::class, 'index'])->name('usuarios.index')->middleware('auth', 'rol:Administrador');
Route::get('/administracion/usuarios/nuevo-usuario', [UsuariosController::class, 'formCreate'])->name('usuarios.create')->middleware('auth', 'rol:Administrador');
Route::post('/administracion/usuarios', [UsuariosController::class, 'store'])->name('usuarios.store')->middleware('auth', 'rol:Administrador');
Route::get('/administracion/usuarios/{usuario}/editar', [UsuariosController::class, 'formUpdate'])->name('usuarios.update')->middleware('auth', 'rol:Administrador');
Route::put('/administracion/usuarios/{usuario}', [UsuariosController::class, 'update'])->name('usuarios.updated')->middleware('auth', 'rol:Administrador');
Route::delete('/administracion/usuarios/{usuario}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy')->middleware('auth', 'rol:Administrador');
// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboards.index')->middleware('auth', 'rol:Administrador');
Route::post('/dashboard', [DashboardController::class, 'store'])->name('dashboards.store')->middleware('auth', 'rol:Administrador');
Route::put('/dashboard/{dashboard}', [DashboardController::class, 'update'])->name('dashboards.update')->middleware('auth', 'rol:Administrador');
Route::delete('/dashboard/{dashboard}', [DashboardController::class, 'destroy'])->name('dashboards.destroy')->middleware('auth', 'rol:Administrador');
// Dispositivos
Route::get('/administracion/dispositivos', [DispositivosController::class, 'index'])->name('dispositivos.index')->middleware('auth', 'rol:Administrador');
Route::get('/administracion/dispositivos/nuevo-dispositivo', [DispositivosController::class, 'formCreate'])->name('dispositivos.create')->middleware('auth', 'rol:Administrador');
Route::post('/administracion/dispositivos', [DispositivosController::class, 'store'])->name('dispositivos.store')->middleware('auth', 'rol:Administrador');
Route::get('/administracion/dispositivos/{dispositivo}/editar', [DispositivosController::class, 'formUpdate'])->name('dispositivos.update')->middleware('auth', 'rol:Administrador');
Route::put('/administracion/dispositivos/{dispositivo}', [DispositivosController::class, 'update'])->name('dispositivos.updated')->middleware('auth', 'rol:Administrador');
Route::delete('/administracion/dispositivos/{dispositivo}', [DispositivosController::class, 'destroy'])->name('dispositivos.destroy')->middleware('auth', 'rol:Administrador');
Route::get('/administracion/dispositivos/{dispositivo}', [DispositivosController::class, 'show'])->name('dispositivos.show')->middleware('auth', 'rol:Administrador');
// Alertas
Route::get('/administracion/alertas', [AlertasController::class, 'index'])->name('alertas.index')->middleware('auth', 'rol:Administrador');
Route::delete('/administracion/alertas/{alerta}', [AlertasController::class, 'destroy'])->name('alertas.destroy')->middleware('auth', 'rol:Administrador');
Route::get('/administracion/alertas/{alerta}/asignar', [AlertasController::class, 'asignar'])->name('alertas.asignar')->middleware('auth', 'rol:Administrador');
Route::post('/administracion/alertas/{alerta}', [AlertasController::class, 'store'])->name('alertas.store')->middleware('auth', 'rol:Administrador');
Route::get('/administracion/alertas/progreso', [AlertasController::class, 'progreso'])->name('alertas.progreso')->middleware('auth', 'rol:Administrador');
Route::put('/administracion/alertas/{alerta}', [AlertasController::class, 'update'])->name('alertas.update')->middleware('auth', 'rol:Administrador');
// Informes
Route::get('/administracion/alertas/informes/{alerta}/crear', [InformesController::class, 'formCreate'])->name('alertas.create')->middleware('auth', 'rol:Administrador');
Route::post('/administracion/informes/{asigna}', [InformesController::class, 'store'])->name('informe.store')->middleware('auth', 'rol:Administrador');
Route::get('/administracion/informes', [InformesController::class, 'index'])->name('alertas.informesindex')->middleware('auth', 'rol:Administrador');
Route::get('/administracion/informes/{informe}/editar', [InformesController::class, 'formUpdate'])->name('alertas.informeupdate')->middleware('auth', 'rol:Administrador');
Route::delete('/administracion/informes/{informe}', [InformesController::class, 'destroy'])->name('informes.destroy')->middleware('auth', 'rol:Administrador');
Route::put('/administracion/informes/{informe}', [InformesController::class, 'update'])->name('informe.updated')->middleware('auth', 'rol:Administrador');

// Operador Routes
Route::get('/operador/alertas', [AlertasController::class, 'operadorDashboard'])->name('alertas.operador.index')->middleware('auth', 'rol:Operador');