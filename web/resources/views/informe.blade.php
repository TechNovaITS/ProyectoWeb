@extends('layouts.app')
@section('content')
<div class="departments-wrapper">
    <!-- Cabecera superior y migas de pan -->
    <div class="content-header-top">
        <div class="breadcrumbs">
            <span>@if (auth()->user()->rol === 'Administrador') 
                    Administración
                  @elseif (auth()->user()->rol === 'Operador')
                    Operaciones
                  @else
                   Auditor 
                   @endif
                </span> / <span>{{ request()->routeIs('auditoria.*') ? 'Auditar' : 'Informes' }}</span> / <span class="current">Ver Informe</span>
        </div>     
    </div>
    <div class="content-header">
        <div class="page-title-container">
            <h1 class="page-title">Informe</h1>
        </div>
        <div class="header-actions">
            <a 
            class="btn-primary"
            @if (request()->routeIs('auditoria.*'))
                href="{{ route('auditoria.informes') }}"
            @elseif(isset($informe))
                href="{{ route('alertas.informesindex') }}"
            @else
                href="{{ route('alertas.progreso') }}"
            @endif> 
                <i class="fa-solid fa-arrow-left"></i> Volver Atras
            </a>
        </div>
     </div>
</div>
@if (!request()->routeIs('auditoria.*'))
<details class="spoiler-caja">
  <summary>Haz clic aquí para ver la Alerta</summary>
    <div class="spoiler-contenido">
        @include('alerta')
    </div>
</details>
@endif
<div class="device-info-card spoiler-caja">
    <div class="info-grid">
            @if (request()->routeIs('auditoria.*'))
                <div class="info-group">
                    <label>CI Usuario</label>
                    <div class="info-value-text">{{ $asigna->ci_usuario }}</div>
                </div>
                <div class="info-group">
                    <label>ID Alerta</label>
                    <div class="info-value-text">{{ $asigna->id_alerta }}</div>
                </div>
            @endif

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