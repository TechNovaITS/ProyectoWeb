@extends('layouts.app')

@section('content')
<div class="departments-wrapper">
    <!-- Cabecera superior y migas de pan -->
    <div class="content-header-top">
        <div class="breadcrumbs">
            <span> {{ auth()->user()->rol == "Administrador" ? "Administración" : "Operaciones" }} </span> / <span>Dispositivos</span> / <span class="current">Ver dispositivo</span>
        </div>    
    </div>
    <div class="content-header">
        <div class="page-title-container">
            <h1 class="page-title">Ver Dispositivo</h1>
        </div>
        <div class="header-actions">
            <a 
            class="btn-primary"
            href="{{  route('dispositivos.index') }}">
                <i class="fa-solid fa-arrow-left"></i> Volver Atras
            </a>
        </div>
     </div>
</div>

    <!-- Tarjeta de Datos del Dispositivo -->
    <div class="device-info-card">
        <h2>Datos del Servidor</h2>
        
        <div class="info-grid">
            <div class="info-group">
                <label>NOMBRE DISPOSITIVO</label>
                <div class="info-value">{{ $dispositivo->nombre }}</div>
            </div>

            <div class="info-group">
                <label>IP</label>
                <div class="info-value">{{ $dispositivo->ip }}</div>
            </div>

            <div class="info-group">
                <label>FECHA CREACION</label>
                <div class="info-value">{{ $dispositivo->created_at->format('d M - H:i') }}</div>
            </div>

            <div class="info-group">
                <label>ULTIMA MODIFICACION</label>
                <div class="info-value">{{ $dispositivo->updated_at->format('d M - H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Sección de Gráficas reemplazada por el Iframe de Grafana -->
    <div class="grafana-section">
        <div class="grafana-wrapper">
            @php
                $embedUrl = "$dashboard->url" . urlencode($dispositivo->nombre) . "&kiosk";
            @endphp

            <iframe 
                src="{{ $embedUrl }}" 
                width="100%" 
                height="600" 
                frameborder="0">
            </iframe>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('dispositivo.css') }}">
<link rel="stylesheet" href="{{ asset('departamentos.css') }}">
@endpush