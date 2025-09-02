//Validacion para que el admin pueda agregar encargados
document.addEventListener("DOMContentLoaded", function() {
    // Obtenemos el formulario y los campos
    var form = document.querySelector('#adminAgregar');
    var nombres = document.getElementById('nombres');
    var apellidos = document.getElementById('apellidos');
    var email = document.getElementById('email');
    var contraseña = document.getElementById('contraseña');
    var numero = document.getElementById('numero');
    var dui = document.getElementById('dui');

    form.addEventListener('submit', function(event) {
        var nombresPattern = /^[\p{L}\s]+$/u;
        var apellidosPattern = /^[\p{L}\s]+$/u;
        var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}$/;
        var contraseñaPattern = /^[a-zA-Z0-9@$!¡#]{8,15}$/;
        var numeroPattern = /^[A-Za-z0-9]+$/;
        var duiPattern = /^\d{8}-\d$/;

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

        if (!emailPattern.test(email.value)) {
            alert("Por favor ingrese un email válido");
            event.preventDefault();
            return false;
        }

        if (!contraseñaPattern.test(contraseña.value)) {
            alert("La contraseña debe tener entre 8 y 15 caracteres y puede contener letras, números y un carácter especial @$?!¡#");
            event.preventDefault();
            return false;
        }
        
        if (!numeroPattern.test(numero.value)) {
            alert("Ingrese un número de empleado válido");
            event.preventDefault();
            return false;
        }
        
        if (!duiPattern.test(dui.value)) {
            alert("Ingrese DUI válido en el formato 12345678-9");
            event.preventDefault();
            return false;
        }
        return true;
    });

});