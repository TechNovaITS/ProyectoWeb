<div class="container-fluid py-1 px-30">

    <!-- Bloque Principal de la Tabla con Buscador -->
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        
        <!-- Barra de Búsqueda Integrada -->
        <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 border-bottom">
            <div style="width: 280px;">
                <input type="text" wire:model.live.debounce.300ms="search" 
                    placeholder="Buscar por ID de alerta..." 
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
                        <th scope="col" wire:click="sortBy('id_alerta')" class="py-3 px-4" style="cursor: pointer; user-select: none;">
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
                        
                        <!-- Sort Fecha Creacion -->
                        <th scope="col" wire:click="sortBy('created_at')" class="py-3 px-2 px-md-3" style="cursor: pointer; user-select: none;">
                            Fecha Creación 
                            @if($sortField === 'created_at')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">↕</span>
                            @endif
                        </th>
                        
                        <!-- Sort Ultima Modificacion -->
                        <th scope="col" wire:click="sortBy('updated_at')" class="py-3 px-2 px-md-3" style="cursor: pointer; user-select: none;">
                            Última Modificación 
                            @if($sortField === 'updated_at')
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
                                <a href=" {{ route('informes.show', $informe->id) }}" class="text-decoration-none ">
                                {{ str_pad($informe->id, 2, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <!-- ID alerta -->
                            <td class="py-3 px-4 text-dark">
                                {{ $informe->id_alerta }}
                            </td>
                             <!--  Alerta -->
                            <td class="py-3 px-4 text-dark">
                                {{ $informe->alerta }}
                            </td>
                            <!-- Fecha Creación -->
                            <td class="py-3 px-2 px-md-3 text-muted small text-nowrap">
                                {{ $informe->created_at ? $informe->created_at->translatedFormat('d M · H:i') : '-' }}
                            </td>
                            <!-- Última Modificación -->
                            <td class="py-3 px-2 px-md-3 text-muted small text-nowrap">
                                {{ $informe->updated_at ? $informe->updated_at->translatedFormat('d M · H:i') : '-' }}
                            </td>
                        
                            <!-- Botones de Acción -->
                            <td class="py-3 px-2 px-md-3 text-end text-nowrap">
                                <div class="d-inline-flex gap-2">
                                    <a type="button" 
                                            class="btn btn-outline-secondary btn-sm px-2 py-1 fw-semibold" 
                                            href="{{ route('alertas.informeupdate', $informe->id) }}">
                                        Editar
                                    </a>
                                </div>
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-sm px-2 py-1 fw-semibold" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#eliminarModal"
                                            data-url="{{ route('informes.destroy', $informe->id) }}"
                                            data-nombre="{{ $informe->id }}"
                                            data-tabla="informe">
                                        Eliminar
                                    </button>
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