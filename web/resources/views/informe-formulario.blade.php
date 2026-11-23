@extends('layouts.app')

@section('title', 'Gestión de Informes - Sentinel')

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
            <span> {{ auth()->user()->rol == "Administrador" ? "Administración" : "Operaciones" }} </span> / <span>Alertas</span> / <span class="current">{{ isset($informe) ? 'Editar Informe' : 'Crear Informe' }}</span>
        </div>    
    </div>
    <div class="content-header">
        <div class="page-title-container">
            <h1 class="page-title">{{ isset($informe) ? 'Editar Informe' : 'Crear Informe' }}</h1>
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
  {{-- Tarjeta del Formulario --}}
    <div class="card-user-form">
        <div class="card-header-custom">
            <div class="user-icon-box">
                <i class="fas fa-file-lines"></i>
            </div>
            <div>
                <h3>Datos del Informe</h3>
                <p>Los campos marcados con * son obligatorios.</p>
            </div>
        </div>

        <form action="{{ isset($informe) ? route('informe.updated', $informe->id) : route('informe.store', $alerta->id) }}" method="POST" class="user-form">
            @csrf
            @if(isset($informe))
                @method('PUT')
            @endif
            <div class="form-row-text">
                {{-- Causa --}}
                <div class="form-group">
                    <label for="causa">Causa *</label>
                    <textarea 
                        id="causa" 
                        name="causa"
                        placeholder="Descripción de la causa que provoco la alerta" 
                        required 
                        class="form-control-custom">{{ old('causa', isset($informe) ? $informe->causa : '') }}</textarea>
                    @error('causa') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                {{-- Resolucion --}}
                <div class="form-group">
                    <label for="resolucion">Resolución *</label>
                    <textarea
                    id="resolucion" 
                    name="resolucion" 
                    placeholder="Descripción de la resolución de la alerta" 
                    required 
                    class="form-control-custom">{{ old('resolucion', isset($informe) ? $informe->resolucion : '') }}</textarea>
                    <small class="form-text">Resolución del informe</small>
                    @error('resolucion') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Botones de Acción inferiores --}}
            <div class="form-actions">
                <button type='reset' class="btn-reiniciar">Reiniciar</button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-file-lines"></i> {{ isset($informe) ? 'Actualizar informe' : 'Crear informe' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('styles')
    <!-- Enlace al archivo CSS independiente -->
    <link rel="stylesheet" href="{{ asset('departamentos.css') }}">
    <link rel="stylesheet" href="{{ asset('dispositivo.css') }}">
    <link rel="stylesheet" href="{{ asset('usuario-formulario.css') }}">
@endpush