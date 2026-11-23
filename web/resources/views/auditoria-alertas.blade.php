@extends('layouts.app')

@section('title', 'Gestión de Usuarios - Sentinel')

@section('content')
<div class="departments-wrapper">
    <!-- Cabecera superior y migas de pan -->
    <div class="content-header-top">
        <div class="breadcrumbs">
             <span>{{ (auth()->user()->rol === 'Administrador') ? 'Administración' : 'Auditor' }}</span> / <span class="current">Auditoria</span>
        </div>    
    </div>
    <div class="content-header">
        <div class="page-title-container">
            <h1 class="page-title">Alertas</h1>
        </div>
        <div class="header-actions">
            <a 
            class="btn-primary"
            href="{{ route('auditoria.informes') }}">
                <i class="fa-solid fa-file-lines"></i> Auditar Informes
            </a>
            <a 
            class="btn-primary"
            href="{{ route('auditoria.actividad') }}">
                <i class="fa-solid fa-chart-bar"></i> Auditar Actividad
            </a>
        </div>
     </div>
</div>
@livewire('alerta-auditoria-table')
@endsection
@push('styles')
    <!-- Enlace al archivo CSS independiente -->
    <link rel="stylesheet" href="{{ asset('departamentos.css') }}">
@endpush