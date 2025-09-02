document.addEventListener("DOMContentLoaded", function() {
    // Obtenemos el formulario y los campos
    var form = document.querySelector('#cambioPassword');
    var contraseña_actual = document.getElementById('contraseña_actual');
    var nueva_contraseña = document.getElementById('nueva_contraseña');
    var confirmar_contraseña = document.getElementById('confirmar_contraseña');

    form.addEventListener('submit', function(event) {
        var contraseñaPattern = /^[a-zA-Z0-9@$?¡\-_\s]{8,15}$/;

        if (!contraseñaPattern.test(nueva_contraseña.value) || !contraseñaPattern.test(confirmar_contraseña.value)) {
            alert("La contraseña debe tener entre 8 y 15 caracteres y puede contener letras, números, espacio y un carácter especial @$?¡\-_");
            event.preventDefault();
            return false;

        } else if (nueva_contraseña.value !== confirmar_contraseña.value) {
            alert("Las contraseñas no coinciden");
            event.preventDefault();
            return false;
        }
    });
});
