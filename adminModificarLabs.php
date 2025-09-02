<?php
// Conectando con la base de datos 
require_once "conex.php";
require_once 'auth.php';

// Verificar que el usuario tiene el rol de administrador (rol id 1)
verificarRol(1);

// Variable para controlar si se muestra la alerta de SweetAlert
$registro_modificado = false;
$error_modificacion = false;
$error_nombre_lab = false;

// Función para limpiar la entrada
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Verificar si se ha enviado el formulario de búsqueda
if (isset($_POST['buscar'])) {
    // Recoger el número de laboratorio del formulario de búsqueda y limpiarlo
    $lab_busqueda = test_input($_POST['numLab']);
    // Preparar el valor para la consulta con LIKE
    $lab_completo = "%" . $lab_busqueda . "%";

    // Consultar la base de datos para obtener el laboratorio por su nombre usando LIKE
    $sql = "SELECT * FROM laboratorios WHERE nombre_laboratorio LIKE ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $lab_completo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si se encuentra el laboratorio, rellenar los campos del formulario con sus datos
        $row = $result->fetch_assoc();
        $id_lab = $row['id_laboratorio'];
        $nombre_lab = $row['nombre_laboratorio'];
        $telefono_lab = $row['telefono_laboratorio'];
        $ubicacion_lab = $row['ubicacion_laboratorio'];
        $denominacion_lab = $row['Denominacion_laboratorio'];
    } else {
        $error_nombre_lab = true;
    }
}

    // Verificar si se ha enviado el formulario de modificación
    if (isset($_POST['modificar'])) {
        // Recoger los datos del formulario de modificación y limpiarlos
        $id_lab = test_input($_POST['id_lab']);
        $nombre_lab = test_input($_POST['nombre_lab']);
        $telefono_lab = test_input($_POST['telefono_lab']);
        $ubicacion_lab = test_input($_POST['ubicacion_lab']);
        $denominacion_lab = test_input($_POST['Denominacion_lab']);

        // Preparar la consulta SQL para llamar al stored procedure
        $sql_sp = "CALL modificar_laboratorio(?, ?, ?, ?, ?)";
        $stmt_sp = $conexion->prepare($sql_sp);
        $stmt_sp->bind_param("issss", $id_lab, $nombre_lab, $telefono_lab, $ubicacion_lab, $denominacion_lab);

        if ($stmt_sp->execute()) {
            $registro_modificado = true;
            $id_lab = '';
            $nombre_lab = '';
            $telefono_lab = '';
            $ubicacion_lab = '';
            $denominacion_lab = '';
        } else {
            $error_modificacion = true;
        }
        // Cerrar la conexión
        $conexion->close();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Modificar Laboratorio - Administrador de Laboratorios</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Links de Bootstrap, iconos, etc -->
    <?php include_once './includes/headLinks.php'; ?>
    <!-- Links Fin -->

    <!-- JavaScript Libraries y Template -->
    <?php include './includes/scriptsLibrerias.php'; ?>
</head>

<body>
    <!-- Navbar o barra de navegación con menú -->
    <?php $currentPage = 'adminModificarLabs.php';
    include('includes/navBarAdmin.php');?>

    <!-- Header Banner -->
    <?php $bannerTitle = "Modificar Datos de Laboratorios";
    include './includes/headerBanner.php'; ?>

    <div class="container" style="width: 500px;">
        <form class="row g-3" id="modificarLabs" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <!-- Formulario de búsqueda por Número de laboratorio -->
            <div class="col-md-12">
                <div class="input-group">
                    <input class="form-control" style="width: 400px;" type="number" name="numLab">
                    <label class="input-group-text">
                        <button class="button-search" type="submit" name="buscar"><img src="./img/search2.ico" alt=""></button>
                    </label>
                </div>
                <br>
            </div>
            <input type="hidden" class="form-control" id="id_lab" name="id_lab" value="<?php echo isset($id_lab) ? htmlspecialchars($id_lab) : ''; ?>">
            <div class="col-md-12">
                <label for="nombre_lab" class="form-label">Laboratorio:</label>
                <input type="text" class="form-control" id="nombre_lab" name="nombre_lab" value="<?php echo isset($nombre_lab) ? htmlspecialchars($nombre_lab) : ''; ?>"><br>
            </div>
            <div class="col-md-12">
                <label for="Denominacion_lab" class="form-label">Nombre del laboratorio:</label>
                <input type="text" class="form-control" id="Denominacion_lab" name="Denominacion_lab" value="<?php echo isset($denominacion_lab) ? htmlspecialchars($denominacion_lab) : ''; ?>"><br>
            </div>
            <div class="col-md-12">
                <label for="telefono_lab" class="form-label">Teléfono:</label>
                <input type="text" class="form-control" id="telefono_lab" name="telefono_lab" value="<?php echo isset($telefono_lab) ? htmlspecialchars($telefono_lab) : ''; ?>"><br>
            </div>
            <div class="col-md-12">
                <label for="ubicacion_lab" class="form-label">Ubicación del laboratorio:</label>
                <input type="text" class="form-control" id="ubicacion_lab" name="ubicacion_lab" value="<?php echo isset($ubicacion_lab) ? htmlspecialchars($ubicacion_lab) : ''; ?>"><br><br>
            </div>

            <div class="container">
                <div class="row">
                    <!-- Botón "Cancelar" -->
                    <div class="col-md-5 mx-auto">
                    <button type="button" class="btn btn-secondary" style="width: 200px;" onclick="window.location.href='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>'">Cancelar</button><br>
                    </div>
                    <!-- Botón "Modificar" -->
                    <div class="col-md-5 mx-auto">
                    <button class="btn btn-secondary" style="width: 200px;" type="submit" name="modificar">Modificar</button><br>
                    </div>
                </div>
                <br><br>
            </div>
        </form>
    </div>

    <!-- Script para limpiar el formulario -->
    <script>
        function limpiarFormulario() {
            // Seleccionar el formulario
            var form = document.getElementById("modificarLabs");
            // Restablecer los valores de los campos del formulario
            form.reset();
        }
    </script>

    <!-- Agregar mensaje de registro -->
    <script>
        <?php if ($registro_modificado) { ?>
        Swal.fire({
            icon: 'success',
            title: 'Laboratorio modificado',
            showConfirmButton: true,
        })
        <?php } elseif ($error_modificacion) { ?>
        Swal.fire({
            icon: 'error',
            title: 'Error al modificar',
            showConfirmButton: true,
        });
        <?php } elseif ($error_nombre_lab) { ?>
        Swal.fire({
            icon: 'error',
            title: 'El laboratorio no existe',
            showConfirmButton: true,
        });
        <?php } ?>
    </script>
</body>
</html>
