@extends('layouts.app')
{{-- resources/views/dashboard.blade.php (o donde quieras usarla) --}}

@section('title', 'Gestión de Usuarios - Sentinel')

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
            <span> {{ auth()->user()->rol === 'Administrador' ? 'Administración' : 'Operaciones' }}</span> / <span class="current">Dispositivos</span>
        </div>    
    </div>
    <div class="content-header">
        <div class="page-title-container">
            <h1 class="page-title">Dispositivos</h1>
        </div>
        @if (auth()->user()->rol === 'Administrador')
        <div class="header-actions">
            <a 
            class="btn-primary"
            href="{{ route('dispositivos.create') }}">
                <i class="fa-solid fa-server"></i> Crear dispositivo
            </a>
        </div>
        @endif
     </div>
</div>
<livewire:dispositivo-table />
@endsection

@push('styles')
    <!-- Enlace al archivo CSS independiente -->
    <link rel="stylesheet" href="{{ asset('departamentos.css') }}">
@endpush