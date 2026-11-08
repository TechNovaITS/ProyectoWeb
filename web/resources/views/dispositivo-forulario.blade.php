@extends('layouts.app')

@section('title', 'Formulario de Dispositivo')

@section('content')
<div class="user-create-container">
    {{-- Encabezado superior --}}
    <div class="page-header">
        <div class="breadcrumbs">
            <span>Administración</span> / <span>Dispositivos</span> / <span class="current">{{ isset($dispositivo) ? 'Editar dispositivo' : 'nuevo dispositivo' }}</span>
            <h1 class="page-title">Gestión de dispositivos</h1>
        </div>
        <a href="{{ route('dispositivos.index') }}" class="btn-volver">
            <i class="fas fa-arrow-left"></i> Volver Atras
        </a>
    </div>

    {{-- Título de la acción --}}
    <div class="section-title-wrapper">
        <h2> {{ isset($dispositivo) ? 'Editar dispositivo' : 'Crear nuevo dispositivo' }}</h2>
    </div>

    {{-- Tarjeta del Formulario --}}
    <div class="card-user-form">
        <div class="card-header-custom">
            <div class="user-icon-box">
                <i class="fas fa-user-plus"></i>
            </div>
            <div>
                <h3>Datos del dispositivo</h3>
                <p>Los campos marcados con * son obligatorios.</p>
            </div>
        </div>

        <form action="{{ isset($dispositivo) ? route('dispositivos.updated', $dispositivo) : route('dispositivos.store') }}" method="POST" class="user-form">
            @csrf
            @if(isset($dispositivo))
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
                        placeholder="tu@cutcsa.com" 
                        pattern=".+@cutcsa\.com"
                        title="Por favor, introduce un correo válido de cutcsa.com" 
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
                    minlength="7"
                    class="form-control-custom">
                    <small class="form-text">Documento de identidad del usuario</small>
                    @error('ci') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
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
                    <i class="fas fa-user-plus"></i> {{ isset($dispositivo) ? 'Actualizar dispositivo' : 'Crear dispositivo' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('usuario-formulario.css') }}">
@endpush