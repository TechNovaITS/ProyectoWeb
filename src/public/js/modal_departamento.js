document.addEventListener('DOMContentLoaded', function () {
      var actualizarDepartamentoModal = document.getElementById('actualizarDepartamentoModal');
        actualizarDepartamentoModal.addEventListener('show.bs.modal', function (event) {
            // Botón que disparó el modal
            var button = event.relatedTarget;
            
            // Extraer la información de los atributos data-*
            var departamentoURL = button.getAttribute('data-url');
            var departamentoNombre = button.getAttribute('data-nombre');
            
            // Actualizar la acción del formulario con la ruta correspondiente (ej: /departamentos/{id})
            var form = document.getElementById('formActualizarDepartamento');
            form.action = departamentoURL;
            
            var nombreInput = document.getElementById('nombreactualizar');
            nombreInput.value = departamentoNombre;
        });
});