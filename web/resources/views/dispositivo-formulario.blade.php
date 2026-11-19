@extends('layouts.app')

@section('title', 'Formulario de Dispositivo')

@section('content')
<div class="user-create-container">
    {{-- Encabezado superior --}}
    <div class="page-header">
        <div class="breadcrumbs">
            <span>Administración</span> / <span>Dispositivos</span> / <span class="current">{{ isset($dispositivo) ? 'Editar dispositivo' : 'Nuevo dispositivo' }}</span>
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
                <i class="fas fa-desktop"></i>
            </div>
            <div>
                <h3>Datos del dispositivo</h3>
                <p>Los campos marcados con * son obligatorios.</p>
            </div>
        </div>

        <form action="{{ isset($dispositivo) ? route('dispositivos.updated', $dispositivo->id) : route('dispositivos.store') }}" method="POST" class="user-form">
            @csrf
            @if(isset($dispositivo))
                @method('PUT')
            @endif
            <div class="form-row">
                {{-- Nombre --}}
                <div class="form-group">
                    <label for="nombre">Nombre *</label>
                    <input 
                        type="text" 
                        id="nombre" 
                        name="nombre"
                        placeholder="Nombre del dispositivo" 
                        value="{{ old('nombre', isset($dispositivo) ? $dispositivo->nombre : '') }}" 
                        required 
                        autofocus
                        class="form-control-custom">
                    @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                {{-- IP --}}
                <div class="form-group">
                    <label for="ip">IP *</label>
                    <input 
                    type="text" 
                    id="ip" 
                    name="ip" 
                    value="{{ old('ip', isset($dispositivo) ? $dispositivo->ip : '') }}" 
                    placeholder="192.168.1.1" 
                    pattern="^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$"
                    required 
                    min="0"
                    minlength="7"
                    class="form-control-custom">
                    @error('ip') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                {{-- Dashboard --}}
                <div class="form-group">
                    <label for="dashboard_id">Dashboard *</label>
                    <select 
                        id="dashboard_id"
                        name="dashboard_id"
                        required
                        class="form-control-custom">
                        <option value="" disabled {{ isset($dispositivo) ? '' : 'selected' }}>Seleccione un dashboard</option>
                        @foreach($dashboards as $dashboard)
                            <option value="{{ $dashboard->id }}" {{ old('dashboard_id', isset($dispositivo) ? $dispositivo->dashboard_id : '') === $dashboard->id ? 'selected' : '' }}>{{ $dashboard->nombre }}</option>
                        @endforeach
                    </select>
                    @error('dashboard_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            </div>
           

            {{-- Botones de Acción inferiores --}}
            <div class="form-actions">
                <button type='reset' class="btn-reiniciar">Reiniciar</button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-desktop"></i> {{ isset($dispositivo) ? 'Actualizar dispositivo' : 'Crear dispositivo' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('usuario-formulario.css') }}">
@endpush