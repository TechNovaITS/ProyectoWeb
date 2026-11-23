@push('styles')
    <!-- Enlace al archivo CSS independiente -->
    <link rel="stylesheet" href="{{ asset('livewire.css') }}">
@endpush
<div class="container-fluid py-1 px-30">

        <!-- Barra de Búsqueda Integrada -->
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row flex-wrap justify-content-between align-items-center gap-3 border-bottom">
    <div class="d-flex flex-column flex-sm-row flex-wrap align-items-center gap-3">
        <div class="search-input">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por ID de Alerta..."
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
                        <th scope="col" wire:click="sortBy('id')" class="py-3 px-4 scope-wire">
                            ID 
                            @if($sortField === 'id')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">&#8597;</span>
                            @endif
                        </th>
                        
                        <!-- Sort Alerta -->
                        <th scope="col" wire:click="sortBy('id_alerta')" class="py-3 px-4 scope-wire">
                            ID Alerta 
                            @if($sortField === 'id_alerta')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">&#8597;</span>
                            @endif
                        </th>
                        <!-- Sort Alerta -->
                        <th scope="col"  class="py-3 px-4">
                            Alerta 
                            
                        </th>
                        
                        <!-- Sort Fecha Eliminaicion -->
                        <th scope="col" wire:click="sortBy('deleted_at')" class="py-3 px-2 px-md-3 scope-wire">
                            Fecha Eliminación 
                            @if($sortField === 'deleted_at')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">↕</span>
                            @endif
                        </th>
                        
                        
                    
                        
                        <th scope="col" class="py-3 px-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($informes as $informe)
                        <tr>
                            <!-- ID -->
                            <td class="py-3 px-4 fw-medium">
                                {{ str_pad($informe->id, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <!-- ID alerta -->
                            <td class="py-3 px-4 text-dark">
                                {{ $informe->id_alerta }}
                            </td>
                             <!--  Alerta -->
                            <td class="py-3 px-4 text-dark">
                                {{ $informe->alerta }}
                            </td>
                            <!-- Fecha Eliminacion -->
                            <td class="py-3 px-2 px-md-3 text-muted small text-nowrap">
                                {{ $informe->deleted_at ? $informe->deleted_at->translatedFormat('d M · H:i') : '-' }}
                            </td>
                            
                        
                            <!-- Botones de Acción -->
                            <td class="py-3 px-2 px-md-3 text-end text-nowrap">
                                <div class="d-inline-flex gap-2">
                                    <a type="button" 
                                            class="btn btn-outline-secondary btn-sm px-2 py-1 fw-semibold" 
                                            href=" {{ route('auditoria.informe', $informe->id) }}">
                                        Ver Informe
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-muted">
                                No se encontraron alertas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

       <!-- Paginación Inferior -->
            <div class="card-footer bg-white py-3 d-flex flex-column flex-sm-row justify-content-sm-end align-items-end align-items-sm-center gap-2 border-top">            <div>
                {{ $informes->links() }}
            </div>
        </div>

    </div>
</div>