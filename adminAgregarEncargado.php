<?php
    // Conectando con la base de datos 
    require_once "conex.php";

    require_once 'auth.php';

    // Verificar que el usuario tiene el rol de administrador (rol id 1)
    verificarRol(1);

    // Definir una variable para controlar si se muestra la alerta de SweetAlert
    $registro_agregado = false;
    $error_registro = false;

    // Obtener los datos de la vista de laboratorios
    $sql_laboratorios = "SELECT id_laboratorio, nombre_laboratorio FROM vista_laboratorios";
    $result_laboratorios = mysqli_query($conexion, $sql_laboratorios);

    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recoger los datos del formulario y limpiarlos
        $nombres = test_input($_POST['nombres']);
        $apellidos = test_input($_POST['apellidos']);
        $email = test_input($_POST['email']);
        $contraseña_clara = test_input($_POST['contraseña']);
        $contraseña_hash = password_hash($contraseña_clara, PASSWORD_DEFAULT);
        $tipo_usuario = $_POST['tipo_usuario'];
        $numero_empleado = test_input($_POST['numero_empleado']);
        $dui = test_input($_POST['dui']);
        $laboratorios = isset($_POST['laboratorios']) ? $_POST['laboratorios'] : array(); // Recoger los laboratorios seleccionados

        // Obtener los nombres de laboratorios seleccionados
        $nombres_laboratorios = array();
        foreach ($laboratorios as $laboratorio_id) {
            $sql_nombre_laboratorio = "SELECT nombre_laboratorio FROM laboratorios WHERE id_laboratorio = ?";
            $stmt_nombre_laboratorio = $conexion->prepare($sql_nombre_laboratorio);
            $stmt_nombre_laboratorio->bind_param("i", $laboratorio_id);
            $stmt_nombre_laboratorio->execute();
            $result_nombre_laboratorio = $stmt_nombre_laboratorio->get_result();
            $row_nombre_laboratorio = $result_nombre_laboratorio->fetch_assoc();
            $nombres_laboratorios[] = $row_nombre_laboratorio['nombre_laboratorio'];
        }

        // Convertir los nombres de laboratorios seleccionados en una cadena separada por comas
        $laboratorios_asignados = implode(",", $nombres_laboratorios);

        // Verificar si los datos de numEmpleado y DUI ya existen en la base de datos
        $sql_check = "SELECT COUNT(*) AS num_rows FROM encargados WHERE numEmpleado_encargado = ? OR DUI_encargado = ? OR email_encargado = ?";
        $stmt_check = $conexion->prepare($sql_check);
        $stmt_check->bind_param("sss", $numero_empleado, $dui, $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $row_check = $result_check->fetch_assoc();

        if ($row_check['num_rows'] > 0) {
            // Los datos de numEmpleado o DUI ya existen en la base de datos
            $error_registro = true;
        } else {
            // Preparar la llamada al procedimiento almacenado
            $sql_sp = "CALL agregar_encargado(?, ?, ?, ?, ?, ?, ?, ?)";

            // Ejecutar la llamada al procedimiento almacenado
            $stmt_sp = $conexion->prepare($sql_sp);
            $stmt_sp->bind_param("ssssssss", $nombres, $apellidos, $email, $contraseña_hash, $numero_empleado, $dui, $tipo_usuario, $laboratorios_asignados);

            if ($stmt_sp->execute()) {
                $registro_agregado = true;
                $nombres = '';
                $apellidos = '';
                $email = '';
                $contraseña = '';
                $numero_empleado = '';
                $dui = '';
                $laboratorios = array();
            } else {
                $error_registro = true;
            }
        }
        // Cerrar la conexión
        $conexion->close();
    }

    // Función para limpiar y validar los datos de entrada
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>


<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Agregar Encargado - Administrador de Laboratorios</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <!-- Adapta el diseño y el contenido de la página al tamaño y la resolución de la pantalla del dispositivo en el que se está visualizando -->
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Links de Boostraps, iconos, etc -->
        <?php include_once './includes/headLinks.php' ?>

        <!-- Enlace al archivo de SweetAlert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- enlace a archivo js para validaciones -->
        <script src="js/validaAdmin_Ag.js"></script>

        <!-- JavaScript Libraries y Template-->
        <?php include './includes/scriptsLibrerias.php'; ?>
    </head>

    <body>


        <!-- Navbar o barra de navegacion con menu -->
        <?php $currentPage = 'adminAgregarEncargado.php';
        include('includes/navBarAdmin.php'); ?>

        <!-- Header Banner -->
        <?php $bannerTitle = "Agregar Datos de Encargados";
        include './includes/headerBanner.php'; ?>

        <!-- Formulario -->
        <div class="container" style="width: 800px;">
            <form class="row g-3" id="adminAgregar" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="col-md-6">
                    <label for="nombres" class="form-label">Nombres:</label>
                    <input type="text" class="form-control" id="nombres" name="nombres" value="<?php echo isset($nombres) ? $nombres : ''; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo isset($apellidos) ? $apellidos : ''; ?>" required>
                </div>
                <div class="col-12">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="contraseña" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contraseña" name="contraseña" value="<?php echo isset($contraseña) ? $contraseña : ''; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="inputState" class="form-label">Tipo de usuario</label>
                    <select id="inputState" class="form-select" name="tipo_usuario" required>
                        <option value="">Escoger...</option>
                        <option value="1" <?php if (isset($tipo_usuario) && $tipo_usuario == '1') echo 'selected'; ?>>Administrador</option>
                        <option value="2" <?php if (isset($tipo_usuario) && $tipo_usuario == '2') echo 'selected'; ?>>Encargado</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="numero" class="form-label">Numero de empleado</label>
                    <input type="text" class="form-control" id="numero" name="numero_empleado" value="<?php echo isset($numero_empleado) ? $numero_empleado : ''; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="dui" class="form-label">DUI</label>
                    <input type="text" class="form-control" id="dui" name="dui" value="<?php echo isset($dui) ? $dui : ''; ?>" required><br>
                </div>

                <!-- Inicio de la checklist -->
                <div class="container">
                    <div class="row">
                        <?php
                            // Recorrer los resultados y generar las casillas de verificación
                            while ($row = mysqli_fetch_assoc($result_laboratorios)) {
                                echo '<div class="col-md-3">';
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input" type="checkbox" name="laboratorios[]" value="' . $row['id_laboratorio'] . '">';
                                echo '<label class="form-check-label">' . $row['nombre_laboratorio'] . '</label>';
                                echo '</div>';
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="https://www.utec.edu.sv/home/campus_utec" target="_blank" style="font-size: 20px; color: blue;">Ubicación de Laboratorios</a>
                </div><br><br>

                <div class="container">
                    <div class="row">
                        <!-- Botón "Cancelar" -->
                        <div class="col-md-5 mx-auto">
                            <button type="button" class="btn btn-secondary" style="width: 200px;" onclick="limpiarFormulario()">Cancelar</button><br>
                        </div>
                        <!-- Botón "Agregar" -->
                        <div class="col-md-5 mx-auto">
                            <button type="submit" class="btn btn-secondary" style="width: 200px;">Registrar</button><br>
                        </div>
                    </div><br><br>
                </div><br><br>
            </form>
        </div>

        <style>
            /* Estilo para cambiar el color de fondo de las casillas de verificación cuando están marcadas */
            .form-check-input:checked {
                background-color: #5a1533;
            }
        </style>

        <!-- Script para limpiar el formulario -->
        <script>
            function limpiarFormulario() {
                // Seleccionar el formulario
                var form = document.getElementById("adminAgregar");
                // Restablecer los valores de los campos del formulario
                form.reset();
            }
        </script>

        <!-- Agregar mensaje de registro -->
        <script>
            <?php if ($registro_agregado) { ?>
            Swal.fire({
                icon: 'success',
                title: 'Registro agregado',
                showConfirmButton: true,
            });
            <?php } elseif ($error_registro) { ?>
            Swal.fire({
                icon: 'error',
                title: 'Error al registrar',
                text: 'Imposible continuar! Datos duplicados en Correo, Numero de empleado o DUI.',
                showConfirmButton: true,
            });
            <?php } ?>
        </script>
    </body>
</html>