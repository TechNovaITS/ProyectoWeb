@extends('layouts.app')

@section('content')
<div class="device-details-container">
    <!-- Encabezado de la Sección -->
    <div class="device-header">
        <div class="header-titles">
            <span class="breadcrumb-nav">Administración / Dispositivos / Ver dispositivo</span>
            <h1>Gestión de dispositivos</h1>
        </div>
        <a href="{{ route('dispositivos.index') }}" class="btn-volver-atras">
            <i class="fa-solid fa-circle-xmark"></i> Volver Atras
        </a>
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
                $embedUrl = "{$grafanaUrl}?var-equipo=" . urlencode($dispositivo->nombre) . "&kiosk";
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
@endpush