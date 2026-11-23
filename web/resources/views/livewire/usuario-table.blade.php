@push('styles')
    <!-- Enlace al archivo CSS independiente -->
    <link rel="stylesheet" href="{{ asset('livewire.css') }}">
@endpush
<div class="container-fluid py-2 px-1 px-sm-2">

    <!-- Bloque Principal de la Tabla con Buscador -->
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        
        <!-- Barra de Búsqueda Integrada -->
        <div class="card-header bg-white py-3 px-3 px-md-4 d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-3 border-bottom">
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <input type="text" wire:model.live.debounce.300ms="search" 
                    placeholder="Buscar email..." 
                    class="form-control form-control-sm">
            </div>
        </div>

        <!-- Tabla Estilizada con Bootstrap -->
        <div class="table-responsive m-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase small text-secondary text-nowrap">
                    <tr>
                        <!-- Sort ID -->
                        <th scope="col" wire:click="sortBy('ci')" class="py-3 px-2 px-md-3 scope-wire">
                            CI 
                            @if($sortField === 'ci')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">↕</span>
                            @endif
                        </th>
                        
                        <!-- Sort Email -->
                        <th scope="col" wire:click="sortBy('email')" class="py-3 px-2 px-md-3 scope-wire">
                            Email 
                            @if($sortField === 'email')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">↕</span>
                            @endif
                        </th>

                        <!-- Sort Rol -->
                        <th scope="col" wire:click="sortBy('rol')" class="py-3 px-2 px-md-3 scope-wire">
                            Rol 
                            @if($sortField === 'rol')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">↕</span>
                            @endif
                        </th>

                        <!-- Sort Departamento -->
                        <th scope="col" wire:click="sortBy('id_departamento')" class="py-3 px-2 px-md-3 scope-wire">
                            Departamento 
                            @if($sortField === 'id_departamento')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">↕</span>
                            @endif
                        </th>
                        
                        <!-- Sort Fecha Creacion -->
                        <th scope="col" wire:click="sortBy('created_at')" class="py-3 px-2 px-md-3 scope-wire">
                            Fecha Creación 
                            @if($sortField === 'created_at')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">↕</span>
                            @endif
                        </th>
                        
                        <!-- Sort Ultima Modificacion -->
                        <th scope="col" wire:click="sortBy('updated_at')" class="py-3 px-2 px-md-3 scope-wire">
                            Última Modificación 
                            @if($sortField === 'updated_at')
                                <span class="ms-1">{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                            @else
                                <span class="ms-1 text-muted opacity-50">↕</span>
                            @endif
                        </th>
                        
                        <th scope="col" class="py-3 px-2 px-md-3 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <!-- ID -->
                            <td class="py-3 px-2 px-md-3 fw-medium text-nowrap">
                                {{ str_pad($usuario->ci, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <!-- Email -->
                            <td class="py-3 px-2 px-md-3 text-dark text-nowrap">
                                {{ $usuario->email }}
                            </td>
                            <!-- Rol -->
                            <td class="py-3 px-2 px-md-3 text-dark text-nowrap">
                                {{ $usuario->rol }}
                            </td>
                            <!-- Departamento -->
                            <td class="py-3 px-2 px-md-3 text-dark text-nowrap">
                                {{ $usuario->belongsToDepartamento->nom_departamento ?? '-' }}
                            </td>
                            <!-- Fecha Creación -->
                            <td class="py-3 px-2 px-md-3 text-muted small text-nowrap">
                                {{ $usuario->created_at ? $usuario->created_at->translatedFormat('d M · H:i') : '-' }}
                            </td>
                            <!-- Última Modificación -->
                            <td class="py-3 px-2 px-md-3 text-muted small text-nowrap">
                                {{ $usuario->updated_at ? $usuario->updated_at->translatedFormat('d M · H:i') : '-' }}
                            </td>
                            <!-- Botones de Acción -->
                            <td class="py-3 px-2 px-md-3 text-end text-nowrap">
                                <div class="d-inline-flex gap-2">
                                    <a type="button" 
                                            class="btn btn-outline-secondary btn-sm px-2 py-1 fw-semibold" 
                                            href="{{ route('usuarios.update', $usuario->ci) }}">
                                        Editar
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-sm px-2 py-1 fw-semibold" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#eliminarModal"
                                            data-url="{{ route('usuarios.destroy', $usuario->ci) }}"
                                            data-nombre="{{ $usuario->email }}"
                                            data-tabla="usuario">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-muted">
                                No se encontraron usuarios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación Inferior -->
            <div class="card-footer bg-white py-3 d-flex flex-column flex-sm-row justify-content-sm-end align-items-end align-items-sm-center gap-2 border-top">            <div>
                {{ $usuarios->links() }}
            </div>
        </div>

    </div>

</div>