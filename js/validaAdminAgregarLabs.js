document.addEventListener("DOMContentLoaded", function() {
    // Obtenemos el formulario y los campos
    var form = document.querySelector('#agregarLabs');
    var nombre = document.getElementById('nombre_lab');
    var Denominacion_laboratorio = document.getElementById('Denominacion_laboratorio');
    var telefono = document.getElementById('telefono_lab')
    var ubicacion = document.getElementById('ubicacion_lab');

    form.addEventListener('submit', function(event) {

        var nombrePattern = /^[0-9\d{2}]+$/;
        var Denominacion_laboratorioPattern = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ#, \s]+$/u;
        var telefonoPattern = /^\d{4}-\d{4}$/;
        var ubicacionPattern = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ#, \s]+$/u;

        if (!nombrePattern.test(nombre.value)) {
            alert("Por favor ingrese un número de laboratorio válido");
            event.preventDefault();
            return false;
        }

        if (!Denominacion_laboratorioPattern.test(Denominacion_laboratorio.value)) {
            alert("Por favor ingrese un nombre de laboratorio válido");
            event.preventDefault();
            return false;
        }

        if (!telefonoPattern.test(telefono.value)) {
            alert("Ingrese un teléfono valido en el formato 2234-5678");
            event.preventDefault();
            return false;
        }

        if (!ubicacionPattern.test(ubicacion.value)) {
            alert("Por favor ingrese una dirección válida que posea letras, números, espacio y un carácter especial #");
            event.preventDefault();
            return false;
        }

        return true;
    });

});