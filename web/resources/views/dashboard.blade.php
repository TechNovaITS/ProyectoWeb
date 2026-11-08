@extends('layouts.app')

@section('title', 'Gestión de Dashboards - Sentinel Ops')

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
            <span>Administración</span> / <span class="current">Dashboards</span>
        </div>    
    </div>

    <!-- Título de la sección -->
     <div class="content-header">
        <div class="page-title-container">
            <h1 class="page-title">Dashboards</h1>
        </div>
        <div class="header-actions">
            <button 
            data-bs-toggle="modal" data-bs-target="#crearDashboardModal"
            class="btn-primary">
                <i class="fa-solid fa-plus"></i> Crear dashboard
            </button>
        </div>
     </div>
</div>

@livewire('dashboard-table')
<!-- Modal -->
<div class="modal fade" id="crearDashboardModal" tabindex="-1" aria-labelledby="crearDashboardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            
            <!-- Cabecera del Modal -->
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-4" id="crearDashboardModalLabel">
                    Crear Nuevo Dashboard
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Formulario y Cuerpo del Modal -->
            <form action="{{ route('dashboards.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-4">
                    <div class="mb-3 row align-items-center">
                        <label for="nombre" class="col-auto col-form-label fw-semibold text-dark">Nombre:</label>
                        <div class="col">
                            <input type="text" 
                                   class="form-control border-success shadow-none py-2 px-3 text-secondary" 
                                   id="nombre" 
                                   name="nombre" 
                                   placeholder="Nombre para el nuevo dashboard" 
                                   maxlength="100"
                                   required>
                        </div>
                    </div>
                    <div class="mb-3 row align-items-center">
                        <label for="url" class="col-auto col-form-label fw-semibold text-dark">URL:</label>
                        <div class="col">
                            <input type="url" 
                                   class="form-control border-success shadow-none py-2 px-3 text-secondary" 
                                   id="url" 
                                   name="url" 
                                   placeholder="URL para el nuevo dashboard" 
                                   maxlength="255"
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
<div class="modal fade" id="actualizarDashboardModal" tabindex="-1" aria-labelledby="actualizarDashboardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            
            <!-- Cabecera del Modal -->
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-4" id="actualizarDashboardModalLabel">
                    Actualizar Dashboard
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Formulario y Cuerpo del Modal -->
            <form id="formActualizarDashboard" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body px-4 py-4">
                    <div class="mb-3 row align-items-center">
                        <label for="nombre" class="col-auto col-form-label fw-semibold text-dark">Nombre:</label>
                        <div class="col">
                            <input type="text" 
                                   class="form-control border-success shadow-none py-2 px-3 text-secondary" 
                                   id="nombreactualizar" 
                                   name="nombre" 
                                   placeholder="Nombre para el dashboard" 
                                   maxlength="100"
                                   required>
                        </div>
                    </div>
                     <div class="mb-3 row align-items-center">
                        <label for="url" class="col-auto col-form-label fw-semibold text-dark">URL:</label>
                        <div class="col">
                            <input type="text" 
                                   class="form-control border-success shadow-none py-2 px-3 text-secondary" 
                                   id="urlactualizar" 
                                   name="url" 
                                   placeholder="URL para el dashboard" 
                                   maxlength="255"
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
<script nonce="{{ app('csp-nonce') }}" src="{{ asset('js/modal_dashboard.js') }}"></script>