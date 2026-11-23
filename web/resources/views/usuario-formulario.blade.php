@extends('layouts.app')

@section('title', 'Formulario de Usuario')

@section('content')
<div class="user-create-container">
    {{-- Encabezado superior --}}
    <div class="page-header">
        <div class="breadcrumbs">
            <span> {{ (auth()->user()->rol === 'Administrador') ? 'Administración' : 'Recuperación' }}</span> / <span>Usuarios</span> / <span class="current">{{ isset($usuario) ? 'Editar Usuario' : 'Nuevo Usuario' }}</span>
            <h1 class="page-title">Gestión de Usuarios</h1>
        </div>
        <a href="{{ route('usuarios.index') }}" class="btn-volver">
            <i class="fas fa-arrow-left"></i> Volver Atras
        </a>
    </div>

    {{-- Título de la acción --}}
    <div class="section-title-wrapper">
        <h2> {{ isset($usuario) ? 'Editar usuario' : 'Crear nuevo usuario' }}</h2>
    </div>

    {{-- Tarjeta del Formulario --}}
    <div class="card-user-form">
        <div class="card-header-custom">
            <div class="user-icon-box">
                <i class="fas fa-user-plus"></i>
            </div>
            <div>
                <h3>Datos del usuario</h3>
                <p>Los campos marcados con * son obligatorios.</p>
            </div>
        </div>

        <form action="{{ isset($usuario) ? route('usuarios.updated', $usuario) : route('usuarios.store') }}" method="POST" class="user-form">
            @csrf
            @if(isset($usuario))
                @method('PUT')
            @endif
            <div class="form-row">
                {{-- Email --}}
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email"
                        placeholder="Introduce un correo"
                        title="Por favor, introduce un correo válido" 
                        value="{{ old('email', isset($usuario) ? $usuario->email : '') }}" 
                        required 
                        autofocus
                        class="form-control-custom">
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                {{-- Cédula --}}
                <div class="form-group">
                    <label for="cedula">Cédula *</label>
                    <input 
                    type="number" 
                    id="cedula" 
                    name="ci" 
                    value="{{ old('ci', isset($usuario) ? $usuario->ci : '') }}" 
                    placeholder="51234321" 
                    required 
                    min="0"
                    max="99999999"
                    class="form-control-custom">
                    <small class="form-text">Documento de identidad del usuario</small>
                    @error('ci') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                {{-- Departamento --}}
                <div class="form-group">
                    <label for="departamento_id">Departamento *</label>
                    <select id="departamento_id" name="id_departamento" required class="form-control-custom">
                        <option value="" disabled {{ old('id_departamento', isset($usuario) ? $usuario->id_departamento : '') == '' ? 'selected' : '' }}>Selecciona un departamento</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id }}" {{ old('id_departamento', isset($usuario) ? $usuario->id_departamento : '') == $departamento->id ? 'selected' : '' }}>
                                {{ $departamento->nom_departamento }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_departamento') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                @if(!isset($usuario))
                {{-- Rol --}}
                    <div class="form-group">
                        <label for="rol">Rol *</label>
                        <select id="rol" name="rol" required class="form-control-custom">
                            @if(auth()->user()->rol === 'Recovery')
                                <option value="Administrador" {{ old('rol', isset($usuario) ? $usuario->rol : '') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                            @else
                                <option value="" disabled {{ old('rol', isset($usuario) ? $usuario->rol : '') == '' ? 'selected' : '' }}>Selecciona un rol</option>
                                <option value="Operador" {{ old('rol', isset($usuario) ? $usuario->rol : '') == 'Operador' ? 'selected' : '' }}>Operador</option>
                                <option value="Auditor" {{ old('rol', isset($usuario) ? $usuario->rol : '') == 'Auditor' ? 'selected' : '' }}>Auditor</option>
                            @endif
                        </select>
                        @error('rol') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                
            @endif
            </div>
            <div class="form-row-single">
                {{-- Contraseña --}}
                <div class="form-group full-width">
                    <label for="password">Contraseña *</label>
                    <div class="password-input-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            minlength="8"
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                            title="Debe contener al menos 8 caracteres, incluyendo una letra mayúscula, una minúscula y un número."
                            placeholder="••••••••" 
                            {{ isset($usuario) ? '' : 'required' }}
                            class="form-control-custom">
                        <i class="fas fa-eye-slash toggle-password"></i>
                    </div>
                    <small class="form-text">Mínimo 8 caracteres, una mayúscula y un número.</small>
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Botones de Acción inferiores --}}
            <div class="form-actions">
                <button type='reset' class="btn-reiniciar">Reiniciar</button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-user-plus"></i> {{ isset($usuario) ? 'Actualizar usuario' : 'Crear usuario' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('usuario-formulario.css') }}">
@endpush