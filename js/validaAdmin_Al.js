document.addEventListener("DOMContentLoaded", function() {

    var form = document.querySelector('#adminAlumno');
    var nombres = document.getElementById('nombres');
    var apellidos = document.getElementById('apellidos');
    var carrera = document.getElementById('carrera');
    var carnet = document.getElementById('carnet');
    var dui = document.getElementById('dui');
    var telefono = document.getElementById('telefono')
    var email = document.getElementById('email');
    var contactoE = document.getElementById('contacto');
    var emergencia = document.getElementById('emergencia');

    form.addEventListener('submit', function(event) {

        var nombresPattern = /^[\p{L}\s]+$/u;
        var apellidosPattern = /^[\p{L}\s]+$/u;
        var carreraPattern = /^[\p{L}\s]+$/u;
        var carnetPattern = /^\d{2}-\d{4}-\d{4}$/;
        var duiPattern = /^\d{8}-\d$/;
        var telefonoPattern = /^\d{4}-\d{4}$/;
        var emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
        var contactoPattern = /^[\p{L}\s]+$/u;
        var emergenciaPattern = /^\d{4}-\d{4}$/;

        if (!nombresPattern.test(nombres.value)) {
            alert("Por favor ingrese un nombre válido");
            event.preventDefault();
            return false;
        }
        if (!apellidosPattern.test(apellidos.value)) {
            alert("Por favor ingrese un apellido válido");
            event.preventDefault();
            return false;
        }
        if (!carreraPattern.test(carrera.value)) {
            alert("Por favor ingrese un nombre de carrera válido");
            event.preventDefault();
            return false;
        }
        if (!carnetPattern.test(carnet.value)) {
            alert("Por favor ingrese un carnet valido en el formato 12-3456-7890");
            event.preventDefault();
            return false;
        }
        if (!duiPattern.test(dui.value)) {
            alert("Ingrese DUI válido en el formato 12345678-9");
            event.preventDefault();
            return false;
        }
        if (!emergenciaPattern.test(emergencia.value)) {
            alert("Ingrese un numero de emergencia valido en el formato 1234-5678");
            event.preventDefault();
            return false;
        }
        if (!telefonoPattern.test(telefono.value)) {
            alert("Ingrese un teléfono valido en el formato 1234-5678");
            event.preventDefault();
            return false;
        }
        if (!emailPattern.test(email.value)) {
            alert("Por favor ingrese un email válido");
            event.preventDefault();
            return false;
        }
        if (!contactoPattern.test(contactoE.value)) {
            alert("Por favor ingrese un nombre de contacto válido");
            event.preventDefault();
            return false;
        }
    })

});