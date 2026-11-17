<!-- Modal de Confirmación Cierre de Alerta -->
<div class="modal fade" id="cerrarModal" tabindex="-1" aria-labelledby="cerrarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            
            <!-- Cabecera del Modal -->
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-danger fs-4" id="cerrarModalLabel">
                    Cerrar Alerta <span id="nombreElemento"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Formulario dinámico para cerrar alerta -->
            <!-- Nota: La ruta 'action' se actualizará automáticamente con JavaScript al hacer clic en el botón de Cerrar Alerta-->
            <form id="formCerrar" action="" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-body px-4 py-4">
                    <p class="text-secondary mb-1">No se podran crear nuevos informes.</p>
                    <p class="text-secondary mb-1">¿Estás seguro de que deseas cerrar la <span id="tablaElemento"></span>?</p>
                    <p class="fw-bold text-dark fs-5" id="CerrarID"></p>
                    <p class="text-muted small mb-0">Esta acción no se puede deshacer.</p>
                </div>

                <!-- Pie del Modal -->
                <div class="modal-footer border-top px-4 py-3">
                    <button type="button" class="btn btn-light border px-4 py-2 text-dark fw-medium" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger px-4 py-2 fw-medium">
                        Sí, Cerrar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
<script src="{{ asset('js/modal_alerta.js') }}"></script>