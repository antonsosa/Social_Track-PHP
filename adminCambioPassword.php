<?php
include 'conex.php';
require_once 'auth.php';

// Verificar que el usuario tiene el rol de administrador (rol id 1)
verificarRol(1);

$registro_agregado = false;
$pass_no_coincide = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $contraseña_actual = $_POST['contraseña_actual'];
    $nueva_contraseña = $_POST['nueva_contraseña'];
    $confirmar_contraseña = $_POST['confirmar_contraseña'];

    // Verificar si las contraseñas coinciden
    if ($nueva_contraseña != $confirmar_contraseña) {
        echo "Las nuevas contraseñas no coinciden.";
    } else {
        // Llamar al procedimiento almacenado para cambiar la contraseña
        $id_encargado = $_SESSION['id']; // id almacenado en la sesión
        $stmt = $conexion->prepare("CALL CambiarContraseñaEncargado(?, ?, ?, @resultado)");
        $stmt->bind_param("iss", $id_encargado, $contraseña_actual, $nueva_contraseña);
        $stmt->execute();

        // Obtener el resultado del procedimiento almacenado
        $result = $conexion->query("SELECT @resultado");
        $row = $result->fetch_assoc();
        $resultado = $row['@resultado'];

        if ($resultado == 1) {
            $registro_agregado = true;
            $contraseña_actual = '';
            $nueva_contraseña = '';
            $confirmar_contraseña = '';
        } else {
            $pass_no_coincide = true; 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Cambio de Contraseña - Administrador de Laboratorios</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Links de Boostraps, iconos, etc -->
    <?php include_once './includes/headLinks.php' ?>

    <!-- enlace a archivo js para validaciones -->
    <script src="js/validaPassCambioEnca.js"></script>

    <!-- JavaScript Libraries y Template -->
    <?php include './includes/scriptsLibrerias.php'; ?>
</head>
<body>
    <!-- Navbar o barra de navegación con menú -->
    <?php $currentPage = 'adminCambioPassword.php'; include('includes/navBarAdmin.php'); ?>

    <!-- Header Banner -->
    <?php $bannerTitle = "Cambio de Contraseña"; include './includes/headerBanner.php'; ?>

    <div class="container" style="width: 500px;">
        <form class="row g-3" id="cambioPassword" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="col-md-12">
                <label for="contraseña_actual" class="form-label">Contraseña actual</label>
                <input type="password" class="form-control" id="contraseña_actual" name="contraseña_actual" value="<?php echo isset($contraseña_actual) ? $contraseña_actual : ''; ?>" required><br>
            </div>
            <div class="col-md-12">
                <label for="nueva_contraseña" class="form-label">Nueva contraseña</label>
                <input type="password" class="form-control" id="nueva_contraseña" name="nueva_contraseña" value="<?php echo isset($nueva_contraseña) ? $nueva_contraseña : ''; ?>" required><br>
            </div>
            <div class="col-md-12">
                <label for="confirmar_contraseña" class="form-label">Confirmar contraseña</label>
                <input type="password" class="form-control" id="confirmar_contraseña" name="confirmar_contraseña" value="<?php echo isset($confirmar_contraseña) ? $confirmar_contraseña : ''; ?>"  required><br>
            </div>
            <div class="container">
                <div class="row">
                    <!-- Botón "Cancelar" -->
                    <div class="col-md-5 mx-auto">
                        <button type="button" class="btn btn-secondary" style="width: 200px;" onclick="limpiarFormulario()">Cancelar</button><br>
                    </div>
                    <!-- Botón "Modificar" -->
                    <div class="col-md-5 mx-auto">
                        <button type="submit" class="btn btn-secondary" style="width: 200px;">Modificar</button><br>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Script para limpiar el formulario -->
    <script>
        function limpiarFormulario() {
            // Seleccionar el formulario
            var form = document.getElementById("cambioPassword");
            // Restablecer los valores de los campos del formulario
            form.reset();
        }
    </script>

    <!-- Agregar mensaje de registro -->
    <script>
        <?php if ($registro_agregado) { ?>
        Swal.fire({
            icon: 'success',
            title: 'Contraseña guardada exitosamente',
            showConfirmButton: true,
        });
        <?php } ?>

        <?php if ($pass_no_coincide) { ?>
        Swal.fire({
            icon: 'error',
            title: 'La contraseña actual no coincide',
            showConfirmButton: true,
        });
        <?php } ?>
    </script>
</body>
</html>
