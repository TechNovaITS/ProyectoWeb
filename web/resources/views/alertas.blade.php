@extends('layouts.app')

@section('title', 'Gestión de Alertas - Sentinel Ops')
@section('content')
@if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

<div class="departments-wrapper">
    <!-- Cabecera superior y migas de pan -->
    <div class="content-header-top">
        <div class="breadcrumbs">
            <span> {{ auth()->user()->rol === 'Administrador' ? 'Administración' : 'Operador' }}</span> / <span class="current">Alertas</span>
        </div>    
    </div>

    <!-- Título de la sección -->
     <div class="content-header">
        <div class="page-title-container">
            <h1 class="page-title">Alertas</h1>
        </div>
        <div class="header-actions">
            <a 
            class="btn-primary"
            href="{{ route('alertas.informesindex') }}">
                <i class="fa-solid fa-file-lines"></i> Informes
            </a>
            <a 
            class="btn-primary"
            href="{{ route('alertas.progreso') }}">
                <i class="fa-solid fa-bell"></i> Alertas En Progreso
            </a>
        </div>
     </div>
</div>

<div class="kpi-container">
    {{-- Card: Alertas pendinetes --}}
    <div class="kpi-card">
        <div class="kpi-icon kpi-icon-red">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            
        </div>
        <div class="kpi-content">
            <span class="kpi-label">Alertas pendientes</span>
            <span class="kpi-value">{{ $conteoEstados['Pendiente'] ?? 0 }}</span>
        </div>
    </div>

    {{-- Card: En Progreso --}}
    <div class="kpi-card">
        
        <div class="kpi-icon kpi-icon-yellow">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>
        <div class="kpi-content">
            <span class="kpi-label">En Progreso</span>
            <span class="kpi-value">{{ $conteoEstados['En Progreso'] ?? 0 }}</span>
        </div>
    </div>

    {{-- Card: Cerradas --}}
    <div class="kpi-card">
        <div class="kpi-icon kpi-icon-blue">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <path d="m9 12 2 2 4-4"/>
            </svg>
        </div>
        <div class="kpi-content">
            <span class="kpi-label">Cerradas</span>
            <span class="kpi-value">{{ $conteoEstados['Cerrado'] ?? 0 }}</span>
        </div>
    </div>
</div>
@livewire('alerta-table')

@endsection
<link rel="stylesheet" href="{{ asset('alertas-cards.css') }}">
<link rel="stylesheet" href="{{ asset('departamentos.css') }}">