@extends('layouts.app')

@section('content')

<div class="departments-wrapper">
    <!-- Cabecera superior y migas de pan -->
    <div class="content-header-top">
        <div class="breadcrumbs">
            <span> {{ auth()->user()->rol === 'Administrador' ? 'Administración' : 'Auditoria' }}</span> / <span>Auditar</span> / <span class="current">Actividad</span>
        </div>    
    </div>

    <!-- Título de la sección -->
     <div class="content-header">
        <div class="page-title-container">
            <h1 class="page-title">Actividad</h1>
        </div>
        <div class="header-actions">
            <a 
            class="btn-primary"
            href="{{ route('auditoria.actividad') }}">
                <i class="fa-solid fa-arrow-left"></i> Volver Atras
            </a>
        </div>
     </div>
</div>
<div class="device-info-card spoiler-caja">
    <div class="info-grid">
            <div class="info-group">
                <label>ID</label>
                <div class="info-value">{{ $actividad->id }}</div>
            </div>

            <div class="info-group">
                <label>Log Name</label>
                <div class="info-value">{{ $actividad->log_name }}</div>
            </div>

            <div class="info-group">
                <label>Descripcion</label>
                <div class="info-value">{{ $actividad->description }}</div>
            </div>
            <div class="info-group">
                <label>Sujeto</label>
                <div class="info-value">{{ $actividad->subject_type }} - {{ $actividad->subject_id }}</div>
            </div>
            <div class="info-group">
                <label>Evento</label>
                <div class="info-value">{{ $actividad->event }}</div>
            </div>
            <div class="info-group">
                <label>Autor</label>
                <div class="info-value">{{ $actividad->causer_type }} - {{ $actividad->causer_id }}</div>
            </div>

    <div class="info-group attr-changes-group ">
    <label>Cambios de Atributos</label>
    <div class="info-value">
        @php
            $rawChanges = $actividad->attribute_changes;

            if ($rawChanges instanceof \Illuminate\Support\Collection) {
                $changesCollection = $rawChanges;
            } elseif (is_string($rawChanges) && $rawChanges !== '') {
                $decoded = json_decode($rawChanges, true);
                $changesCollection = collect(json_last_error() === JSON_ERROR_NONE ? $decoded : ['valor' => $rawChanges]);
            } else {
                $changesCollection = collect((array) $rawChanges);
            }

            $old = collect($changesCollection->get('old', []));
            $attributes = collect($changesCollection->get('attributes', []));
            $hasDiff = $old->isNotEmpty() || $attributes->isNotEmpty();

            $keys = $hasDiff
                ? $old->keys()->merge($attributes->keys())->unique()->values()
                : $changesCollection->keys()->values();
        @endphp

        @if($keys->isEmpty())
            <span class="attr-value-muted">Sin cambios registrados.</span>
        @elseif($hasDiff)
            <div class="attr-changes-wrapper">
                <table class="attr-changes-table">
                    <thead>
                        <tr>
                            <th>Campo</th>
                            <th>Anterior</th>
                            <th>Nuevo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($keys as $key)
                            @php
                                $prevVal = $old->get($key);
                                $nextVal = $attributes->get($key);
                                $changed = $prevVal !== $nextVal;
                            @endphp
                            <tr class="{{ $changed && $actividad->event === 'updated' ? 'attr-row-changed' : '' }}">
                                <td class="attr-field-name">{{ $key }}</td>
                                <td>
                                    @if($old->has($key))
                                        @include('partials.activity-value', ['value' => $prevVal])
                                    @else
                                        <span class="attr-value-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($attributes->has($key))
                                        @include('partials.activity-value', ['value' => $nextVal])
                                    @else
                                        <span class="attr-value-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <ul class="attr-list">
                @foreach($keys as $key)
                    <li>
                        <span class="attr-field-name">{{ $key }}</span>:
                        @include('partials.activity-value', ['value' => $changesCollection->get($key)])
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

            <div class="info-group">
                <label>Propiedades</label>
                <div class="info-value">{{ $actividad->properties }}</div>
            </div>
            <div class="info-group">
                <label>Fecha de Creación</label>
                <div class="info-value">{{ $actividad->created_at }}</div>
            </div>
            <div class="info-group">
                <label>Fecha de Actualización</label>
                <div class="info-value">{{ $actividad->updated_at }}</div>
            </div>
    </div>
</div>
@endsection
@push('styles')
    <!-- Enlace al archivo CSS independiente -->
    <link rel="stylesheet" href="{{ asset('departamentos.css') }}">
    <link rel="stylesheet" href="{{ asset('dispositivo.css') }}">
    <link rel="stylesheet" href="{{ asset('usuario-formulario.css') }}">
@endpush