<div class="container-fluid py-1 px-30">

    <!-- Bloque Principal de la Tabla con Buscador -->
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        
        <!-- Barra de Búsqueda Integrada -->
        <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 border-bottom">
            <div style="width: 280px;">
                <input type="text" wire:model.live.debounce.300ms="search" 
                    placeholder="Buscar por nombre de alerta..." 
                    class="form-control form-control-sm">
            </div>
        </div>

        <!-- Tabla Estilizada con Bootstrap --> 
        <div class="table-responsive m-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <!-- Sort ID --> 
                        <th scope="col" wire:click="sortBy('id')" class="py-3 px-4" style="cursor: pointer; user-select: none;">
                            ID 
                            @if($sortField === 'id')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">&#8597;</span>
                            @endif
                        </th>
                        
                        <!-- Sort Alerta -->
                        <th scope="col" wire:click="sortBy('alerta')" class="py-3 px-4" style="cursor: pointer; user-select: none;">
                            Alerta 
                            @if($sortField === 'alerta')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">&#8597;</span>
                            @endif
                        </th>
                        <!-- Sort Dispositivo -->
                        <th scope="col" wire:click="sortBy('dispositivo_id')" class="py-3 px-4" style="cursor: pointer; user-select: none;">
                            Dispositivo 
                            @if($sortField === 'dispositivo_id')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">&#8597;</span>
                            @endif
                        </th>
                        
                        <!-- Sort Iniciado -->
                        <th scope="col" wire:click="sortBy('iniciado_en')" class="py-3 px-4" style="cursor: pointer; user-select: none;">
                            Fecha Iniciado 
                            @if($sortField === 'iniciado_en')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">&#8597;</span>
                            @endif
                        </th>
                    
                        
                        <th scope="col" class="py-3 px-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alertas as $alerta)
                        <tr>
                            <!-- ID -->
                            <td class="py-3 px-4 fw-medium">
                                {{ str_pad($alerta->id, 2, '0', STR_PAD_LEFT) }}</a>
                            </td>
                            <!-- alerta -->
                            <td class="py-3 px-4 text-dark">
                                {{ $alerta->alerta }}
                            </td>
                            <!-- Dispositivo -->
                            <td class="py-3 px-4 text-dark">
                                {{ $alerta->belongsToDispositivo ? $alerta->belongsToDispositivo->nombre : 'Dispositivo No Registrado' }}
                            </td>
                            <!-- Fecha Iniciado -->
                            <td class="py-3 px-4 text-muted fs-7">
                                {{ $alerta->iniciado_en }}
                            </td>
                        
                            <!-- Botones de Acción -->
                            <td class="py-3 px-4 text-end">
                                <div class="d-inline-flex gap-2">
                                    @if(auth()->user()->rol === 'Administrador')
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-sm px-3 py-1 fw-semibold" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#eliminarModal"
                                            data-url="{{ route('alertas.destroy', $alerta->id) }}"
                                            data-nombre="{{ $alerta->id }} - {{ $alerta->alerta }}"
                                            data-tabla="alerta"
                                            >
                                        Eliminar
                                    </button>
                                    @endif
                                    <a 
                                        class="btn btn-outline-secondary btn-sm px-3 py-1 fw-semibold" 
                                        href="{{ route('alertas.asignar', $alerta->id) }}"
                                            >
                                        Asignar
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
                {{ $alertas->links() }}
            </div>
        </div>

    </div>

</div>