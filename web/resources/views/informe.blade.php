@extends('layouts.app')
@section('content')
<div class="departments-wrapper">
    <!-- Cabecera superior y migas de pan -->
    <div class="content-header-top">
        <div class="breadcrumbs">
            <span> {{ auth()->user()->rol == "Administrador" ? "Administración" : "Operaciones" }} </span> / <span>Alertas</span> / <span class="current">Informe</span>
        </div>    
    </div>
    <div class="content-header">
        <div class="page-title-container">
            <h1 class="page-title">Informe</h1>
        </div>
        <div class="header-actions">
            <a 
            class="btn-primary"
            @if(isset($informe))
                href="{{ route('alertas.informesindex') }}"
            @else
            href="{{ route('alertas.progreso') }}"
            @endif>
                <i class="fa-solid fa-arrow-left"></i> Volver Atras
            </a>
        </div>
     </div>
</div>
<details class="spoiler-caja">
  <summary>Haz clic aquí para ver la Alerta</summary>
    <div class="spoiler-contenido">
        @include('alerta')
    </div>
</details>
<div class="device-info-card spoiler-caja">
    <div class="info-grid">
            <div class="info-group-full">
                <label>CAUSA DEL INCIDENTE</label>
                <div class="info-value-text">{{ $informe->causa }}</div>
            </div>

            <div class="info-group-full">
                <label>RESOLUCIÓN DEL INCIDENTE</label>
                <div class="info-value-text">{{ $informe->resolucion }}</div>
            </div>
    </div>
</div>
@endsection
@push('styles')
    <!-- Enlace al archivo CSS independiente -->
    <link rel="stylesheet" href="{{ asset('departamentos.css') }}">
    <link rel="stylesheet" href="{{ asset('dispositivo.css') }}">
    <link rel="stylesheet" href="{{ asset('usuario-formulario.css') }}">
@endpush