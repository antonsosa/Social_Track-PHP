<?php
include 'conex.php';

require_once 'auth.php';

// Verificar que el usuario tiene el rol de administrador (rol id 1)
verificarRol(1);

// Inicializar las variables
$registro_agregado = false;
$registro_ciclo_recibido = false;
$ciclo_ya_existe = false;

// Manejar el formulario para agregar un nuevo ciclo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agregarCiclo"])) {
    $nombre_ciclo = $_POST["actividad"];

    // Verificar si el ciclo ya existe
    $sql_verificar = "SELECT COUNT(*) AS count FROM ciclos WHERE nombre_ciclo = ?";
    $stmt_verificar = $conexion->prepare($sql_verificar);
    $stmt_verificar->bind_param("s", $nombre_ciclo);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();
    $row_verificar = $result_verificar->fetch_assoc();

    if ($row_verificar['count'] > 0) {
        // El ciclo ya existe
        $ciclo_ya_existe = true;
    } else {
        // Llamada al procedimiento almacenado
        $sql = "CALL InsertarCiclo(?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $nombre_ciclo);
        $stmt->execute();
        $stmt->close();

        // Marcar el registro como agregado
        $registro_agregado = true;
    }

    $stmt_verificar->close();
}

// Manejar el formulario para seleccionar el ciclo en curso
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guardarCiclo"])) {
    $nombre_ciclo_recibido = $_POST["nombre_ciclo_recibido"];

    // Llamada al procedimiento almacenado para insertar o actualizar en la tabla traspaso_nombre_ciclo
    $sql = "CALL InsertarCicloRecibido(?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $nombre_ciclo_recibido);
    $stmt->execute();
    $stmt->close();

    // Marcar el registro como agregado
    $registro_ciclo_recibido = true;
}

// Llamar ciclo actual a campo
$sql_ciclo = "SELECT * FROM ciclo_actual";
$stmt_ciclo = $conexion->prepare($sql_ciclo);
$stmt_ciclo->execute();
$result_ciclo = $stmt_ciclo->get_result();
if ($result_ciclo->num_rows > 0) {
    $row = $result_ciclo->fetch_assoc();
    $ciclo = $row['ciclo_actual'];
}

// Llamada al procedimiento almacenado para obtener los nombres de los ciclos
$sql_ciclos = "CALL ObtenerNombresCiclos()";
$result_ciclos = mysqli_query($conexion, $sql_ciclos);

// Verificar si se encontraron resultados
$options = '';
if (mysqli_num_rows($result_ciclos) > 0) {
    while ($row = mysqli_fetch_assoc($result_ciclos)) {
        $options .= '<option value="' . $row['nombre_ciclo'] . '">' . $row['nombre_ciclo'] . '</option>';
    }
} else {
    $options = '<option value="">No hay ciclos disponibles</option>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ciclo en Curso - Administrador de Laboratorios</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Enlace al archivo de SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Links de Bootstrap, iconos, etc -->
    <?php include_once './includes/headLinks.php'; ?>

    <!-- JavaScript Libraries y Template -->
    <?php include './includes/scriptsLibrerias.php'; ?>

    <!-- Enlace a archivo js para validaciones -->
    <script src="js/validaCiclo.js"></script>
</head>
<body>

    <!-- Navbar o barra de navegación con menú -->
    <?php $currentPage = 'adminCicloEnCurso.php'; 
    include('includes/navBarAdmin.php'); ?>

    <!-- Header Banner -->
    <?php $bannerTitle = "Ciclo en Curso"; 
    include './includes/headerBanner.php'; ?>

    <form class="row g-3" action="adminCicloEnCurso.php" method="post">
        <div class="centrar" style="width: 500px;">
            <label>Ciclo actual: </label>
            <input type="text" id="cicloActual" name="ciclo_actual" value="<?php echo isset($ciclo) ? $ciclo : ''; ?>" readonly>
        </div>
    </form>
    <br>
    <div class="container" style="width: 1000px;">
        <div class="row justify-content-between">
            <div class="col-md-4 mb-1">
                <form class="row g-3" id="adminAgregarCiclo" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Agregar nuevo ciclo</th>
                                <th colspan="2" style="text-align: center;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="text-align: center;">
                                <td>
                                    <input type="text" class="form-control" name="actividad" id="actividad" style="width: 250px;">
                                </td>
                                <td>
                                    <button type="submit" name="agregarCiclo" class="btn btn-secondary">Agregar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="col-md-5 mb-1">
                <form class="row g-3" id="adminCicloCurso" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Seleccionar ciclo en curso</th>
                                <th colspan="2" style="text-align: center;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="text-align: center;">
                                <td>
                                    <select id="inputState" class="form-select" name="nombre_ciclo_recibido" required>
                                        <option value="">Escoger...</option>
                                        <?php echo $options; ?>
                                    </select>
                                </td>
                                <td>
                                    <button type="submit" name="guardarCiclo" class="btn btn-secondary">Guardar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>

    <!-- Agregar mensaje de registro -->
    <?php if ($registro_agregado) { ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Ciclo registrado',
                showConfirmButton: true,
            });
        </script>
    <?php } ?>

    <?php if ($registro_ciclo_recibido) { ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Ciclo en curso guardado',
                showConfirmButton: true,
            }).then(function () {
                // Redirigir para actualizar el ciclo actual en la página
                window.location.href = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>";
            });
        </script>
    <?php } ?>

    <?php if ($ciclo_ya_existe) { ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'El ciclo ya existe',
                showConfirmButton: true,
            });
        </script>
    <?php } ?>
</body>
</html>
