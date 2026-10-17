@extends('layouts.app') {{-- Reemplaza 'layouts.app' por el nombre de tu layout principal --}}

@section('title', 'Crear nuevo usuario')

@section('content')
<div class="user-create-container">
    {{-- Encabezado superior --}}
    <div class="page-header">
        <div class="breadcrumbs">
            <span>Administración</span> / <span>Usuarios</span> / <span class="current">Nuevo Usuario</span>
            <h1 class="page-title">Gestión de usuarios</h1>
        </div>
        <a href="{{ route('usuarios.index') }}" class="btn-volver">
            <i class="fas fa-arrow-left"></i> Volver Atras
        </a>
    </div>

    {{-- Título de la acción --}}
    <div class="section-title-wrapper">
        <h2>Crear nuevo usuario</h2>
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

        <form action="{{ route('usuarios.store') }}" method="POST" class="user-form">
            @csrf

            <div class="form-row">
                {{-- Email --}}
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email"
                        placeholder="tu@cutcsa.com" 
                        pattern=".+@cutcsa\.com"
                        title="Por favor, introduce un correo válido de cutcsa.com" 
                        value="{{ old('email') }}" 
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
                    value="{{ old('ci') }}" 
                    placeholder="51234321" 
                    required 
                    min="0"
                    minlength="7"
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
                        <option value="" disabled selected>Selecciona un departamento</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id }}" {{ old('id_departamento') == $departamento->id ? 'selected' : '' }}>
                                {{ $departamento->nom_departamento }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_departamento') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                {{-- Rol --}}
                <div class="form-group">
                    <label for="rol">Rol *</label>
                    <select id="rol" name="rol" required class="form-control-custom">
                        <option value="" disabled selected>Selecciona un rol</option>
                        <option value="Operador" {{ old('rol') == 'Operador' ? 'selected' : '' }}>Operador</option>
                        <option value="Auditor" {{ old('rol') == 'Auditor' ? 'selected' : '' }}>Auditor</option>
                    </select>
                    @error('rol') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
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
                            required
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
                    <i class="fas fa-user-plus"></i> Crear usuario
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('usuario-formulario.css') }}">
@endpush