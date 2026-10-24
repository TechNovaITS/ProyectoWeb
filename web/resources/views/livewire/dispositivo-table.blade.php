<div class="container-fluid py-1 px-30">

    <!-- Bloque Principal de la Tabla con Buscador -->
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        
        <!-- Barra de Búsqueda Integrada -->
        <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 border-bottom">
            <div style="width: 280px;">
                <input type="text" wire:model.live.debounce.300ms="search" 
                    placeholder="Buscar dispositivo..." 
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
                        
                        <!-- Sort Nombre -->
                        <th scope="col" wire:click="sortBy('nombre')" class="py-3 px-4" style="cursor: pointer; user-select: none;">
                            Nombre 
                            @if($sortField === 'nombre')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">&#8597;</span>
                            @endif
                        </th>
                        <!-- Sort IP -->
                        <th scope="col" wire:click="sortBy('ip')" class="py-3 px-4" style="cursor: pointer; user-select: none;">
                            IP 
                            @if($sortField === 'ip')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">&#8597;</span>
                            @endif
                        </th>
                        
                        <!-- Sort Fecha Creacion -->
                        <th scope="col" wire:click="sortBy('created_at')" class="py-3 px-4" style="cursor: pointer; user-select: none;">
                            Fecha Creacion 
                            @if($sortField === 'created_at')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">&#8597;</span>
                            @endif
                        </th>
                        
                        <!-- Sort Ultima Modificacion -->
                        <th scope="col" wire:click="sortBy('updated_at')" class="py-3 px-4" style="cursor: pointer; user-select: none;">
                            Ultima Modificacion 
                            @if($sortField === 'updated_at')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">&#8597;</span>
                            @endif
                        </th>
                        
                        <th scope="col" class="py-3 px-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dispositivos as $dispositivo)
                        <tr>
                            <!-- ID -->
                            <td class="py-3 px-4 fw-medium">
                                <a href="{{ route('dispositivos.show', $dispositivo->id) }}">
                                {{ str_pad($dispositivo->id, 2, '0', STR_PAD_LEFT) }}</a>
                            </td>
                            <!-- Nombre -->
                            <td class="py-3 px-4 text-dark">
                                {{ $dispositivo->nombre }}
                            </td>
                            <!-- IP -->
                            <td class="py-3 px-4 text-dark">
                                {{ $dispositivo->ip }}
                            </td>
                            <!-- Fecha Creación -->
                            <td class="py-3 px-4 text-muted fs-7">
                                {{ $dispositivo->created_at ? $dispositivo->created_at->translatedFormat('d M · H:i') : '-' }}
                            </td>
                            <!-- Última Modificación -->
                            <td class="py-3 px-4 text-muted fs-7">
                                {{ $dispositivo->updated_at ? $dispositivo->updated_at->translatedFormat('d M · H:i') : '-' }}
                            </td>
                            <!-- Botones de Acción -->
                            <td class="py-3 px-4 text-end">
                                <div class="d-inline-flex gap-2">
                                    <button type="button" 
                                            class="btn btn-outline-secondary btn-sm px-3 py-1 fw-semibold" 
                                            >
                                        Editar
                                    </button>
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-sm px-3 py-1 fw-semibold" 
                                            >
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-muted">
                                No se encontraron dispositivos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación Inferior -->
        <div class="card-footer bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 border-top">
            <div class="text-muted fs-7">
                Mostrando 
                <span class="fw-semibold">{{ $dispositivos->firstItem() ?? 0 }}</span> a 
                <span class="fw-semibold">{{ $dispositivos->lastItem() ?? 0 }}</span> de 
                <span class="fw-semibold">{{ $dispositivos->total() }}</span> registros
            </div>
            <div>
                {{ $dispositivos->links() }}
            </div>
        </div>

    </div>

</div>