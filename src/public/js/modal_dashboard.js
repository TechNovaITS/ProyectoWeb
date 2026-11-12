document.addEventListener('DOMContentLoaded', function () {
     var actualizarDashboardModal = document.getElementById('actualizarDashboardModal');
        actualizarDashboardModal.addEventListener('show.bs.modal', function (event) {
            // Botón que disparó el modal
            var button = event.relatedTarget;
            
            // Extraer la información de los atributos data-*
            var dashboardURL = button.getAttribute('data-url');
            var dashboardNombre = button.getAttribute('data-nombre');
            var dashboardUrlInput = button.getAttribute('data-url-dashboard');
            
            // Actualizar la acción del formulario con la ruta correspondiente (ej: /dashboards/{id})
            var form = document.getElementById('formActualizarDashboard');
            form.action = dashboardURL;
            
            var nombreInput = document.getElementById('nombreactualizar');
            nombreInput.value = dashboardNombre;

            var urlInput = document.getElementById('urlactualizar');
            urlInput.value = dashboardUrlInput;
        });
});