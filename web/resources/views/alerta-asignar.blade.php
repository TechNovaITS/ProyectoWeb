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
                </span> / <span>{{ request()->routeIs('auditoria.*') ? 'Auditar' : 'Alertas' }}</span> / <span class="current">Asignar Alerta</span>
        </div>    
    </div>

    <!-- Título de la sección -->
     <div class="content-header">
        <div class="page-title-container">
            <h1 class="page-title">Alerta</h1>
        </div>
        <div class="header-actions">
            <a 
            class="btn-primary"
            href=" {{ request()->routeIs('auditoria.*') ? route('auditoria.alertas') : route('alertas.index') }}">
                <i class="fa-solid fa-arrow-left"></i> Volver Atras
            </a>
        </div>
     </div>
</div>
@include('alerta')
    <div class="card-user-form">
    <form action="{{ route('alertas.store', $alerta->id) }}" method="POST" class="asignar-form">
    @csrf
    @if (!request()->routeIs('auditoria.*'))
        @if (auth()->user()->rol === 'Administrador') 
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
        @else
            <div class="asignar-container">
                <div class="asignar-group">
                    <button value="{{ auth()->user()->ci }}" name="ci_usuario" type="submit" class="btn-submit">Asignarme</button>
                </div>
            </div> 
        @endif
    @endif
</form>
</div>

    
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('dispositivo.css') }}">
<link rel="stylesheet" href="{{ asset('usuario-formulario.css') }}">
<link rel="stylesheet" href="{{ asset('departamentos.css') }}">
@endpush