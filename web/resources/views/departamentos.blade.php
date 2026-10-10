@extends('layouts.app')
{{-- resources/views/dashboard.blade.php (o donde quieras usarla) --}}

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
                <i class="fa-solid fa-plus"></i> Crear departamento
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
                        Actualizar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="eliminarDepartamentoModal" tabindex="-1" aria-labelledby="eliminarDepartamentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            
            <!-- Cabecera del Modal -->
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-danger fs-4" id="eliminarDepartamentoModalLabel">
                    Eliminar Departamento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Formulario dinámico para eliminar -->
            <!-- Nota: La ruta 'action' se actualizará automáticamente con JavaScript al hacer clic en el botón de eliminar -->
            <form id="formEliminarDepartamento" action="" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="modal-body px-4 py-4">
                    <p class="text-secondary mb-1">¿Estás seguro de que deseas eliminar el departamento?</p>
                    <p class="fw-bold text-dark fs-5" id="nombreDepartamentoEliminar"></p>
                    <p class="text-muted small mb-0">Esta acción no se puede deshacer.</p>
                </div>

                <!-- Pie del Modal -->
                <div class="modal-footer border-top px-4 py-3">
                    <button type="button" class="btn btn-light border px-4 py-2 text-dark fw-medium" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger px-4 py-2 fw-medium">
                        Sí, eliminar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Script de JavaScript para inyectar la URL y el nombre correcto del departamento al abrir el modal -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var eliminarDepartamentoModal = document.getElementById('eliminarDepartamentoModal');
        eliminarDepartamentoModal.addEventListener('show.bs.modal', function (event) {
            // Botón que disparó el modal
            var button = event.relatedTarget;
            
            // Extraer la información de los atributos data-*
            var departamentoId = button.getAttribute('data-id');
            
            // Actualizar la acción del formulario con la ruta correspondiente (ej: /departamentos/{id})
            var form = document.getElementById('formEliminarDepartamento');
            form.action = "{{ route('departamentos.destroy', ':id') }}".replace(':id', departamentoId);
        });
        actualizarDepartamentoModal.addEventListener('show.bs.modal', function (event) {
            // Botón que disparó el modal
            var button = event.relatedTarget;
            
            // Extraer la información de los atributos data-*
            var departamentoId = button.getAttribute('data-id');
            var departamentoNombre = button.getAttribute('data-nombre');
            
            // Actualizar la acción del formulario con la ruta correspondiente (ej: /departamentos/{id})
            var form = document.getElementById('formActualizarDepartamento');
            form.action = "{{ route('departamentos.update', ':id') }}".replace(':id', departamentoId);
            
            var nombreInput = document.getElementById('nombreactualizar');
            nombreInput.value = departamentoNombre;
        });
    });
</script>
@endsection

@push('styles')
    <!-- Enlace al archivo CSS independiente -->
    <link rel="stylesheet" href="{{ asset('departamentos.css') }}">
@endpush