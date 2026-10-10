<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartamentosController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;

Route::get('/', function () {
    return Auth::check() 
        ? redirect('/dashboard') // Si esta logeado, redirige al dashboard
        : redirect('/login'); // Si no esta logeado, redirige al login
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest'); // Solo accesible para usuarios no autenticados
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
Route::get('/administracion/departamentos', [DepartamentosController::class, 'index'])->name('departamentos.index')->middleware('auth', 'rol:Administrador');
Route::post('/administracion/departamentos', [DepartamentosController::class, 'store'])->name('departamentos.store')->middleware('auth', 'rol:Administrador');
Route::put('/administracion/departamentos/{departamento}', [DepartamentosController::class, 'update'])->name('departamentos.update')->middleware('auth', 'rol:Administrador');
Route::delete('/administracion/departamentos/{departamento}', [DepartamentosController::class, 'destroy'])->name('departamentos.destroy')->middleware('auth', 'rol:Administrador');

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);