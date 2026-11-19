@extends('layouts.app')

@section('title', 'Gestión de Departamentos - Sentinel Ops')

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
            <span>Administración</span> / <span class="current">Departamentos</span>
        </div>    
    </div>

    <!-- Título de la sección -->
     <div class="content-header">
        <div class="page-title-container">
            <h1 class="page-title">Departamentos</h1>
        </div>
        <div class="header-actions">
            <button 
            data-bs-toggle="modal" data-bs-target="#crearDepartamentoModal"
            class="btn-primary">
                <i class="fa-solid fa-users"></i> Crear departamento
            </button>
        </div>
     </div>
</div>

@livewire('departamento-table')
<!-- Modal -->
<div class="modal fade" id="crearDepartamentoModal" tabindex="-1" aria-labelledby="crearDepartamentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            
            <!-- Cabecera del Modal -->
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-4" id="crearDepartamentoModalLabel">
                    Crear Nuevo Departamento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Formulario y Cuerpo del Modal -->
            <form action="{{ route('departamentos.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-4">
                    <div class="mb-3 row align-items-center">
                        <label for="nombre" class="col-auto col-form-label fw-semibold text-dark">Nombre:</label>
                        <div class="col">
                            <input type="text" 
                                   class="form-control border-success shadow-none py-2 px-3 text-secondary" 
                                   id="nombre" 
                                   name="nom_departamento" 
                                   placeholder="Nombre para el nuevo departamento" 
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Pie del Modal -->
                <div class="modal-footer border-top px-4 py-3">
                    <button type="button" class="btn btn-light border px-4 py-2 text-dark fw-medium" data-bs-dismiss="modal">
                        Salir
                    </button>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-medium" style="background-color: #1a56db; border-color: #1a56db;">
                        Crear
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Modal Update -->
<div class="modal fade" id="actualizarDepartamentoModal" tabindex="-1" aria-labelledby="actualizarDepartamentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            
            <!-- Cabecera del Modal -->
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-4" id="actualizarDepartamentoModalLabel">
                    Actualizar Departamento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Formulario y Cuerpo del Modal -->
            <form id="formActualizarDepartamento" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body px-4 py-4">
                    <div class="mb-3 row align-items-center">
                        <label for="nombre" class="col-auto col-form-label fw-semibold text-dark">Nombre:</label>
                        <div class="col">
                            <input type="text" 
                                   class="form-control border-success shadow-none py-2 px-3 text-secondary" 
                                   id="nombreactualizar" 
                                   name="nom_departamento" 
                                   placeholder="Nombre para el departamento" 
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Pie del Modal -->
                <div class="modal-footer border-top px-4 py-3">
                    <button type="button" class="btn btn-light border px-4 py-2 text-dark fw-medium" data-bs-dismiss="modal">
                        Salir
                    </button>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-medium" style="background-color: #1a56db; border-color: #1a56db;">
                        Actualizar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection

@push('styles')
    <!-- Enlace al archivo CSS independiente -->
    <link rel="stylesheet" href="{{ asset('departamentos.css') }}">
@endpush
<script  src="{{ asset('js/modal_departamento.js') }}"></script>
 