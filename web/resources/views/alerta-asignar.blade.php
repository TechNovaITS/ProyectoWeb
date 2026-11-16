@extends('layouts.app')

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
<div class="device-details-container">
    <!-- Encabezado de la Sección -->
    <div class="device-header">
        <div class="header-titles">
            <span class="breadcrumb-nav">Administración / Alertas / Asignar</span>
            <h1>Gestión de alertas</h1>
        </div>
        <a href="{{ route('alertas.index') }}" class="btn-volver-atras">
            <i class="fa-solid fa-circle-xmark"></i> Volver Atras
        </a>
    </div>
    <!-- Tarjeta de Datos del Dispositivo -->
    <div class="device-info-card">
        <h2>Datos de la Alerta</h2>
        
        <div class="info-grid">
            <div class="info-group">
                <label>ID ALERTA</label>
                <div class="info-value">{{ $alerta->id }}</div>
            </div>

            <div class="info-group">
                <label>TITULO</label>
                <div class="info-value">{{ $alerta->titulo }}</div>
            </div>

            <div class="info-group">
                <label>NOMBRE DE LA ALERTA</label>
                <div class="info-value">{{ $alerta->alerta }}</div>
            </div> 

            <div class="info-group">
                <label>DEPARTAMENTO</label>
                <div class="info-value">{{ $alerta->departamento }}</div>
            </div>

            <div class="info-group">
                <label>JOB</label>
                <div class="info-value">{{ $alerta->job }}</div>
            </div>

            <div class="info-group">
                <label>VALOR</label>
                <div class="info-value">{{ $alerta->valor_actual }}</div>
            </div>

            <div class="info-group">
                <label>DISPOSITIVO</label>
                @if($alerta->belongsToDispositivo)
                    <a class="info-value"
                    href="{{ route('dispositivos.show', $alerta->belongsToDispositivo->id)}}" 
                    target="_blank" 
                    rel="noopener noreferrer">
                    {{ $alerta->belongsToDispositivo->nombre }}
                    </a>
                @else
                    <div class="info-value">Dispositivo No Registrado</div>
                @endif
            </div>

            <div class="info-group-full">
                <label>SUMARIO</label>
                <div class="info-value-text">{{ $alerta->sumario }}</div>
            </div>

            <div class="info-group-full">
                <label>DESCRIPCION</label>
                <div class="info-value-text">{{ $alerta->descripcion }}</div>
            </div>

            <div class="info-group">
                <label>MANUAL</label>
                <a class="info-value" href="{{ $alerta->manual }}" target="_blank" rel="noopener noreferrer"> {{ $alerta->manual }}</a>
            </div>

            <div class="info-group">
                <label>URL GRAFANA</label>
                 <a  class="info-value" href="{{ $alerta->url_grafana }}" target="_blank" rel="noopener noreferrer"> {{ $alerta->url_grafana }}</a>
            </div>
            
            <div class="info-group">
                <label>FECHA INICIADO</label>
                <div class="info-value">{{ $alerta->iniciado_en }}</div>
            </div>

            <div class="info-group">
                <label>FECHA FINALIZADO</label>
                <div class="info-value">{{ $alerta->finalizado_en }}</div>
            </div>

            <div class="info-group">
                <label>FECHA CREACION</label>
                <div class="info-value">{{ $alerta->created_at->format('d M - H:i') }}</div>
            </div>

            <div class="info-group">
                <label>ULTIMA MODIFICACION</label>
                <div class="info-value">{{ $alerta->updated_at->format('d M - H:i') }}</div>
            </div>
        </div>
    </div>
    <div class="card-user-form">
    <form action="{{ route('alertas.store', $alerta->id) }}" method="POST" class="asignar-form">
    @csrf
    <div class="asignar-container">
        <div class="asignar-group">
            <label for="ci_usuario">Asignar a Usuario:</label>
            <select name="ci_usuario" id="ci_usuario" required class="asignar-select">
                <option value="" disabled selected>Seleccione un usuario</option>
                <option value="{{ auth()->user()->ci }}">Asignarme</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->ci }}">{{ $usuario->email }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-submit">Asignar</button>
        </div>
    </div> 
</form>
</div>

    
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('dispositivo.css') }}">
<link rel="stylesheet" href="{{ asset('usuario-formulario.css') }}">
@endpush