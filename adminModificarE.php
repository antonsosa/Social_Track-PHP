<?php
    // Conectando con la base de datos 
    require_once "conex.php";

    require_once 'auth.php';

    // Verificar que el usuario tiene el rol de administrador (rol id 1)
    verificarRol(1);

    // Función para limpiar y validar los datos de entrada
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Definir una variable para controlar si se muestra la SweetAlert
    $registro_agregado = false;
    $error_registro = false;

    // Obtener los datos de la vista de laboratorios
    $sql_laboratorios = "SELECT id_laboratorio, nombre_laboratorio FROM vista_laboratorios";
    $result_laboratorios = mysqli_query($conexion, $sql_laboratorios);

    // Verificar si se ha enviado el formulario de búsqueda
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar'])) {
        // Recoger el número de empleado del formulario de búsqueda y limpiarlo
        $empleado_busqueda = test_input($_POST['numEmpleado_busqueda']);

        // Consultar la base de datos para obtener el encargado por su número de empleado
        $sql = "SELECT * FROM encargados WHERE numEmpleado_encargado = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $empleado_busqueda);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Si se encuentra el encargado, obtener sus datos
            $row = $result->fetch_assoc();
            $id_encargado = $row['id_encargado'];
            $nombres = $row['nombres_encargado'];
            $apellidos = $row['apellidos_encargado'];
            $email = $row['email_encargado'];
            $contraseña = $row['password_encargado'];
            $numero_empleado = $row['numEmpleado_encargado'];
            $dui = $row['DUI_encargado'];
            $tipo_usuario = $row['id_rol_encargado'];

            // Obtener la lista de laboratorios asignados al encargado
            $laboratorios_asignados = explode(",", $row['laboratorios_asignados']);
        } else {
            $error_registro = true;
        }
    }

    // Verificar si se ha enviado el formulario
    if (isset($_POST['modificar'])) {
        // Recoger los datos del formulario y limpiarlos
        $id_encargado = test_input($_POST['id_encargado']);
        $nombres = test_input($_POST['nombres']);
        $apellidos = test_input($_POST['apellidos']);
        $email = test_input($_POST['email']);
        $contraseña = test_input($_POST['contraseña']);
        $tipo_usuario = $_POST['tipo_usuario'];
        $numero_empleado = test_input($_POST['numero_empleado']);
        $dui = test_input($_POST['dui']);
        $laboratorios_asignados = isset($_POST['laboratorios']) ? $_POST['laboratorios'] : array(); // Recoger los laboratorios seleccionados

        // Convertir los laboratorios seleccionados en una cadena separada por comas
        $laboratorios_asignados = implode(",", $laboratorios_asignados);

        // Llamar al procedimiento almacenado para modificar el encargado
        $sql_sp = "CALL modificar_encargado(?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_sp = $conexion->prepare($sql_sp);
        $stmt_sp->bind_param("issssssss", $id_encargado, $nombres, $apellidos, $email, $contraseña, $numero_empleado, $dui, $tipo_usuario, $laboratorios_asignados);

        if ($stmt_sp->execute()) {
            $registro_agregado = true;
            $nombres = '';
            $apellidos = '';
            $email = '';
            $contraseña = '';
            $numero_empleado = '';
            $dui = '';
            $laboratorios_asignados = array();
        }
    }
    // Cerrar la conexión
    $conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Modificar Encargado - Administrador de Laboratorios</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <!-- Adapta el diseño y el contenido de la página al tamaño y la resolución de la pantalla del dispositivo en el que se está visualizando -->
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Links de Boostraps, iconos, etc -->
        <?php include_once './includes/headLinks.php' ?>

        <!-- Enlace al archivo de SweetAlert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- enlace a archivo js para validaciones -->
        <script src="js/validaAdmin_ModE.js"></script>

        <!-- JavaScript Libraries y Template-->
        <?php include './includes/scriptsLibrerias.php'; ?>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            .input-group {
                display: flex;
                align-items: center;
            }
            .input-group input {
                flex: 1;
            }
            .input-group .toggle-password {
                cursor: pointer;
                margin-left: -30px;
            }
        </style>
    </head>

    <body>
        <!-- Navbar o barra de navegacion con menu -->
        <?php $currentPage = 'adminModificarE.php';
        include('includes/navBarAdmin.php'); ?>

        <!-- Header Banner -->
        <?php $bannerTitle = "Modificar Datos de Encargados";
        include './includes/headerBanner.php'; ?>

        <!-- Formulario de modificación con formulario de búsqueda integrado -->
        <div class="container" style="width: 800px;">
            <form class="row g-3" id="adminModificarE" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"method="post">

                <!-- Formulario de búsqueda por Numero de empleado -->
                <div class="col-md-12">
                    <div class="input-group">
                        <input class="form-control" style="width: 675px;" type="search" placeholder="Numero de empleado"aria-label="Search" name="numEmpleado_busqueda">
                        <label class="input-group-text">
                            <button class="button-search" type="submit" name="buscar"><img src="./img/search2.ico"alt=""></button>
                        </label>
                    </div>
                </div>

                <!-- Campos de modificación -->
                <input type="hidden" name="id_encargado" value="<?php echo isset($id_encargado) ? $id_encargado : ''; ?>">
                <div class="col-md-6">
                    <label for="nombres" class="form-label">Nombres:</label>
                    <input type="text" class="form-control" id="nombres" name="nombres"value="<?php echo isset($nombres) ? $nombres : ''; ?>">
                </div>
                <div class="col-md-6">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="apellidos" name="apellidos"value="<?php echo isset($apellidos) ? $apellidos : ''; ?>">
                </div>
                <div class="col-12">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"value="<?php echo isset($email) ? $email : ''; ?>">
                </div>
                <div class="col-md-6">
                    <label for="contraseña" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="contraseña" name="contraseña"value="<?php echo isset($contraseña) ? $contraseña : ''; ?>">
                        <span class="toggle-password">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="inputState" class="form-label">Tipo de usuario</label>
                    <select id="inputState" class="form-select" name="tipo_usuario">
                        <option value="">Escoger...</option>
                        <option value="1" <?php if (isset($tipo_usuario) && $tipo_usuario == '1') echo 'selected'; ?>>
                            Administrador</option>
                        <option value="2" <?php if (isset($tipo_usuario) && $tipo_usuario == '2') echo 'selected'; ?>>
                            Encargado</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="numero" class="form-label">Numero de empleado</label>
                    <input type="text" class="form-control" id="numero" name="numero_empleado"value="<?php echo isset($numero_empleado) ? $numero_empleado : ''; ?>">
                </div>
                <div class="col-md-6">
                    <label for="dui" class="form-label">DUI</label>
                    <input type="text" class="form-control" id="dui" name="dui"value="<?php echo isset($dui) ? $dui : ''; ?>"><br>
                </div>

                <!-- Inicio de la checklist -->
                <div class="container">
                    <div class="row">
                        <?php
                            // Recorrer los resultados y generar las casillas de verificación
                            while ($row = mysqli_fetch_assoc($result_laboratorios)) {
                                echo '<div class="col-md-3">';
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input" type="checkbox" name="laboratorios[]" value="' . $row['nombre_laboratorio'] . '"';
                                // Verificar si el laboratorio está asignado al encargado buscado
                                if (isset($laboratorios_asignados) && in_array($row['nombre_laboratorio'], $laboratorios_asignados)) {
                                    echo 'checked'; // Marcar el checkbox si el laboratorio está asignado
                                }
                                echo '>';
                                echo '<label class="form-check-label">' . $row['nombre_laboratorio'] . '</label>';
                                echo '</div>';
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>

                <div class="container"><br>
                    <div class="row">
                        <!-- Botón "Cancelar" -->
                        <div class="col-md-5 mx-auto">
                            <button type="button" class="btn btn-secondary" style="width: 200px;" onclick="limpiarFormulario()">Cancelar</button><br>
                        </div>
                        <div class="col-md-3 mx-auto">
                            <button type="submit" class="btn btn-secondary" style="width: 200px;" name="modificar">Modificar</button><br>
                        </div>
                        <br><br><br><br>
                    </div>
                </div>
            </form>
        </div>

        <!-- Script para limpiar el formulario -->
        <script>
            function limpiarFormulario() {
                // Seleccionar el formulario
                var form = document.getElementById("adminModificarE");
                // Obtener todos los elementos del formulario
                var formElements = form.elements;
                // Recorrer todos los elementos
                for (var i = 0; i < formElements.length; i++) {
                    var element = formElements[i];
                    // Verificar si es un checkbox
                    if (element.type === 'checkbox') {
                        // Desmarcar el checkbox
                        element.checked = false;
                    } else {
                        // Establecer el valor en vacío para otros tipos de elementos
                        element.value = '';
                    }
                }
            }

            document.querySelector('.toggle-password').addEventListener('click', function () {
                const passwordInput = document.getElementById('contraseña');
                const eyeIcon = document.getElementById('eyeIcon');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            });
        </script>

        <!-- Agregar mensaje de registro -->
        <script>
            <?php if ($registro_agregado) { ?>
            Swal.fire({
                icon: 'success',
                title: 'Registro modificado con exito',
                showConfirmButton: true
            });
            <?php } ?>
            <?php if ($error_registro) { ?>
            Swal.fire({
                icon: 'error',
                title: 'El numero de empleado no existe',
                text: 'El numero de empleado ingresado no se encuentra en la base de datos',
                showConfirmButton: true
            });
            <?php } ?>
        </script>
    </body>
</html>


