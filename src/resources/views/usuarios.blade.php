@extends('layouts.app')
{{-- resources/views/dashboard.blade.php (o donde quieras usarla) --}}

@section('title', 'Gestión de Usuarios - Sentinel Ops')

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
            <span>Administración</span> / <span class="current">Usuarios</span>
        </div>    
    </div>
    <div class="content-header">
        <div class="page-title-container">
            <h1 class="page-title">Usuarios</h1>
        </div>
        <div class="header-actions">
            <a 
            class="btn-primary"
           href="{{ route('usuarios.create') }}">
                <i class="fa-solid fa-user"></i> Crear usuario
            </a>
        </div>
     </div>
</div>
<livewire:usuario-table />
@include('modals.modal_eliminar')
@endsection

@push('styles')
    <!-- Enlace al archivo CSS independiente -->
    <link rel="stylesheet" href="{{ asset('departamentos.css') }}">
@endpush