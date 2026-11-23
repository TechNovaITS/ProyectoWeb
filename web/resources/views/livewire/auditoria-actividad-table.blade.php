@push('styles')
    <!-- Enlace al archivo CSS independiente -->
    <link rel="stylesheet" href="{{ asset('livewire.css') }}">
@endpush
<div class="container-fluid py-1 px-30">

    <!-- Bloque Principal de la Tabla con Buscador -->
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        
        <!-- Barra de Búsqueda Integrada -->
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row flex-wrap justify-content-between align-items-center gap-3 border-bottom">
    <div class="d-flex flex-column flex-sm-row flex-wrap align-items-center gap-3">
        <div class="search-input">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por Log Name..."
                class="form-control form-control-sm"
            >
        </div>

        <div class="search-input">
            <input
                type="text"
                wire:model.live.debounce.300ms="searchCode"
                placeholder="Buscar por CI Usuario..."
                class="form-control form-control-sm"
            >
        </div>
    </div>

    <button
        type="button"
        wire:click="exportCsv"
        wire:loading.attr="disabled"
        class="btn btn-primary btn-sm"
    >
        <span wire:target="exportCsv">
            <i class="fa-solid fa-file-export me-1"></i>Exportar CSV
        </span>
    </button>
</div>


        <!-- Tabla Estilizada con Bootstrap --> 
        <div class="table-responsive m-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <!-- Sort ID --> 
                        <th scope="col" wire:click="sortBy('id')" class="py-3 px-4 scope-wire" >
                            ID 
                            @if($sortField === 'id')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">&#8597;</span>
                            @endif
                        </th>
                        
                        <!-- Sort log_name -->
                        <th scope="col" wire:click="sortBy('log_name')" class="py-3 px-4 scope-wire">
                            Log Name 
                            @if($sortField === 'log_name')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">&#8597;</span>
                            @endif
                        </th>
                        <!-- Sort Evento -->
                        <th scope="col" wire:click="sortBy('event')" class="py-3 px-4 scope-wire">
                            Evento 
                            @if($sortField === 'event')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">&#8597;</span>
                            @endif
                        </th>
                        
                        <!-- Causer ID -->
                        <th scope="col" wire:click="sortBy('causer_id')" class="py-3 px-4 scope-wire">
                            Ci Usuario 
                            @if($sortField === 'causer_id')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">&#8597;</span>
                            @endif
                        </th>
                    
                        
                        <th scope="col" class="py-3 px-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actividades as $actividad)
                        <tr>
                            <!-- ID -->
                            <td class="py-3 px-4 fw-medium">
                                {{ str_pad($actividad->id, 2, '0', STR_PAD_LEFT) }}</a>
                            </td>
                            <!-- Log Name -->
                            <td class="py-3 px-4 text-dark">
                                {{ $actividad->log_name }}
                            </td>
                            <!-- Evento -->
                            <td class="py-3 px-4 text-dark">
                                {{ $actividad->event}}
                            </td>
                            <!-- Causer ID -->
                            <td class="py-3 px-4 text-muted fs-7">
                                {{ $actividad->causer_id }}
                            </td>
                        
                            <!-- Botones de Acción -->
                            <td class="py-3 px-4 text-end">
                                <div class="d-inline-flex gap-2">
                                    <a 
                                        class="btn btn-outline-secondary btn-sm px-3 py-1 fw-semibold" 
                                        href="{{ route('auditoria.showactividad', $actividad->id) }}">
                                        Ver Actividad
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-muted">
                                No se encontraron actividades registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

       <!-- Paginación Inferior -->
            <div class="card-footer bg-white py-3 d-flex flex-column flex-sm-row justify-content-sm-end align-items-end align-items-sm-center gap-2 border-top">            <div>
                {{ $actividades->links() }}
            </div>
        </div>

    </div>

</div>