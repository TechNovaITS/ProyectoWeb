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
    @include('alerta')
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