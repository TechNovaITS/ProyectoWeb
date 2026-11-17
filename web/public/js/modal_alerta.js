document.addEventListener('DOMContentLoaded', function () {
        var cerrarModal = document.getElementById('cerrarModal');
        cerrarModal.addEventListener('show.bs.modal', function (event) {
            // Botón que disparó el modal
            var button = event.relatedTarget;
            
            // Extraer la información de los atributos data-*
            var cerrarURL = button.getAttribute('data-url');
            var cerrarTabla = button.getAttribute('data-tabla');
            var cerrarNombre = button.getAttribute('data-nombre');
            
            // Actualizar el contenido del modal con la información del departamento
            var nombreElemento = document.getElementById('nombreElemento');
            nombreElemento.textContent = cerrarNombre;
            var tablaElemento = document.getElementById('tablaElemento');
            tablaElemento.textContent = cerrarTabla;
            
            // Actualizar la acción del formulario con la ruta correspondiente (ej: /departamentos/{id})
            var form = document.getElementById('formCerrar');   
            form.action = cerrarURL;
        });
    });