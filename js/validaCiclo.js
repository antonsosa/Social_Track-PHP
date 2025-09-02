document.addEventListener("DOMContentLoaded", function() {
    // Obtenemos el formulario y los campos
    var form = document.querySelector('#adminAgregarCiclo');
    var ciclo = document.getElementById('actividad');

    form.addEventListener('submit', function(event) {
        var cicloPattern = /^Ciclo\s0[1-3]-20[2-9][0-9]$/u;

        if (!cicloPattern.test(ciclo.value)) {
            alert("Por favor ingrese un formato de ciclo válido 'Ciclo 0X-20XX'");
            event.preventDefault();
            return false;
        }
    });
});