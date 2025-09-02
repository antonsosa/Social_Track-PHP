<?php
// Conectando con la base de datos 
require_once "conex.php";
require_once 'auth.php';

// Verificar que el usuario tiene el rol de administrador (rol id 1)
verificarRol(1);

// Variable para controlar si se muestra la alerta de SweetAlert
$registro_agregado = false;
$error_registro = false;
$error_id_laboratorio = false;

// Llamar al último laboratorio actual
$sql_max_lab = "SELECT MAX(id_laboratorio) AS max_id FROM laboratorios";
$stmt_max_lab = $conexion->prepare($sql_max_lab);
$stmt_max_lab->execute();
$result_max_lab = $stmt_max_lab->get_result();
if ($result_max_lab->num_rows > 0) {
    $row = $result_max_lab->fetch_assoc();
    $max_laboratorio = $row['max_id'];
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre_lab = $_POST['nombre_lab'];
    $telefono_lab = $_POST['telefono_lab'];
    $ubicacion_lab = $_POST['ubicacion_lab'];
    $Denominacion_laboratorio= $_POST['Denominacion_laboratorio'];

    // Convertir el número recibido a un entero
    $nombre_lab_num = (int)$nombre_lab;

    // Obtener el valor máximo de id_laboratorio
    $sql_max_id = "SELECT MAX(id_laboratorio) AS max_id FROM laboratorios";
    $result_max_id = $conexion->query($sql_max_id);
    $row_max_id = $result_max_id->fetch_assoc();
    $max_id_laboratorio = $row_max_id['max_id'];

    // Verificar que el nuevo número no sea mayor que el máximo actual
    if ($nombre_lab_num > $max_id_laboratorio + 1) {
        $error_id_laboratorio = true;
    } else {
        // Concatenar "Laboratorio " con el número recibido
        $nombre_lab_completo = "Laboratorio " . $nombre_lab;

        // Verificar si el id_laboratorio ya existe en la base de datos
        $sql_check = "SELECT COUNT(*) AS num_rows FROM laboratorios WHERE id_laboratorio = ?";
        $stmt_check = $conexion->prepare($sql_check);
        $stmt_check->bind_param("i", $nombre_lab_num);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $row_check = $result_check->fetch_assoc();

        if ($row_check['num_rows'] > 0) {
            // Si el id_laboratorio ya existe, muestra un mensaje de error
            $error_registro = true;
        } else {
            // Si el id_laboratorio no existe, procede a agregar el laboratorio
            $sql = "CALL agregar_laboratorio(?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssss", $nombre_lab_completo, $telefono_lab, $ubicacion_lab, $Denominacion_laboratorio);

            if ($stmt->execute()) {
                $registro_agregado = true;
                $nombre_lab = '';
                $telefono_lab = '';
                $ubicacion_lab = '';
                $Denominacion_laboratorio = '';

            } else {
                $error_registro = true;
            }
            // Cerrar el statement
            $stmt->close();
        }
        // Cerrar el statement de verificación
        $stmt_check->close();
    }
}
// Cerrar la conexión
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Agregar Laboratorio - Administrador de Laboratorios</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Links de Bootstrap, iconos, etc -->
    <?php include_once './includes/headLinks.php' ?>
    <!-- Links Fin -->

    <!-- enlace a archivo js para validaciones -->
    <script src="js/validaAdminAgregarLabs.js"></script>

    <!-- JavaScript Libraries y Template-->
    <?php include './includes/scriptsLibrerias.php'; ?>
</head>

<body>
    <!-- Navbar o barra de navegación con menú -->
    <?php $currentPage = 'adminAgregarLabs.php';
    include('includes/navBarAdmin.php');?>

    <!-- Header Banner -->
    <?php $bannerTitle = "Agregar Datos de Laboratorios";
    include './includes/headerBanner.php'; ?>


        <form class="row g-3" action="marcacion.php" method="post">
            <div class="centrar" style="width: 500px;">
                <label>Último laboratorio agregado: </label>
                <input type="text" id="maxLaboratorio" name="max_laboratorio" value="<?php echo isset($max_laboratorio) ? $max_laboratorio : ''; ?>" readonly>
            </div>
        </form>
    <br>

    <div class="container" style="width: 500px;">
        <form class="row g-3" id="agregarLabs" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="col-md-12">
                <label for="nombre_lab" class="form-label">Número del laboratorio:</label>
                <input type="number" class="form-control" id="nombre_lab" name="nombre_lab" required><br>
            </div>
            <div class="col-md-12">
                <label for="Denominacion_laboratorio" class="form-label">Nombre del laboratorio:</label>
                <input type="text" class="form-control" id="Denominacion_laboratorio" name="Denominacion_laboratorio" required><br>
            </div>
            <div class="col-md-12">
                <label for="telefono_lab" class="form-label">Teléfono:</label>
                <input type="text" class="form-control" id="telefono_lab" name="telefono_lab" required><br>
            </div>
            <div class="col-md-12">
                <label for="ubicacion_lab" class="form-label">Ubicación del laboratorio:</label>
                <input type="text" class="form-control" id="ubicacion_lab" name="ubicacion_lab" required><br><br>
            </div>

            <div class="container">
                <div class="row">
                <!-- Botón "Cancelar" -->
                    <div class="col-md-5 mx-auto">
                        <button type="button" class="btn btn-secondary" style="width: 200px;" onclick="limpiarFormulario()">Cancelar</button><br>
                    </div>
                <!-- Botón "Agregar" -->
                    <div class="col-md-5 mx-auto">
                        <button type="submit" class="btn btn-secondary" style="width: 200px;">Agregar</button><br>
                    </div>
                </div> <br> <br>
            </div>
        </form>
    </div>

    <!-- Script para limpiar el formulario -->
    <script>
        function limpiarFormulario() {
            // Seleccionar el formulario
            var form = document.getElementById("agregarLabs");
            // Restablecer los valores de los campos del formulario
            form.reset();
        }
    </script>

    <!-- Agregar mensaje de registro -->
    <script>
        <?php if ($registro_agregado) { ?>
        Swal.fire({
            icon: 'success',
            title: 'Laboratorio agregado',
            showConfirmButton: true,
        }).then(function () {
                // Redirigir para actualizar el ciclo actual en la página
                window.location.href = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>";
            });
        <?php } elseif ($error_registro) { ?>
        Swal.fire({
            icon: 'error',
            title: 'Error al registrar',
            text: 'Imposible continuar! El laboratorio ya existe.',
            showConfirmButton: true,
        });
        <?php } elseif ($error_id_laboratorio) { ?>
        Swal.fire({
            icon: 'error',
            title: 'Error al registrar',
            text: 'El número del laboratorio no puede ser mayor al número actual más alto.',
            showConfirmButton: true,
        });
        <?php } ?>
    </script>
</body>
</html>
