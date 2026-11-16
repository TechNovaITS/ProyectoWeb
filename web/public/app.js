document.addEventListener('DOMContentLoaded', function () {
        var eliminarModal = document.getElementById('eliminarModal');
        eliminarModal.addEventListener('show.bs.modal', function (event) {
            // Botón que disparó el modal
            var button = event.relatedTarget;
            
            // Extraer la información de los atributos data-*
            var eliminarURL = button.getAttribute('data-url');
            var eliminarTabla = button.getAttribute('data-tabla');
            var eliminarNombre = button.getAttribute('data-nombre');
            
            // Actualizar el contenido del modal con la información del departamento
            var nombreElemento = document.getElementById('nombreElemento');
            nombreElemento.textContent = eliminarNombre;
            var tablaElemento = document.getElementById('tablaElemento');
            tablaElemento.textContent = eliminarTabla;
            
            // Actualizar la acción del formulario con la ruta correspondiente (ej: /departamentos/{id})
            var form = document.getElementById('formEliminar');   
            form.action = eliminarURL;
        });
    });