<?php
    // Conectando con la base de datos 
    require_once "conex.php";

    
    require_once 'auth.php';

    // Verificar que el usuario tiene el rol de encargado (rol id 2)
    verificarRol(2);


    // Definir una variable para controlar si se muestra la SweetAlert
    $registro_modificado = false;
    $error_modificacion = false;
    $alumno_no_registrado = false;

    // Función para limpiar y validar los datos de entrada
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Verificar si se ha enviado el formulario de búsqueda
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar'])) {

        // Recoger el DUI del formulario de búsqueda y limpiarlo
        $alumno_busqueda = test_input($_POST['carnet_busqueda']);

        // Consultar la base de datos para obtener el encargado por su número de empleado
        $sql = "SELECT * FROM alumnos WHERE carnet_alumno = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $alumno_busqueda);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Si se encuentra el alumno, rellenar los campos del formulario con sus datos
            $row = $result->fetch_assoc();
            $id_alumno = $row['id_alumno'];
            $nombres = $row['nombres_alumno'];
            $apellidos = $row['apellidos_alumno'];
            $carrera = $row['carrera_alumno'];
            $carnet = $row['carnet_alumno'];
            $dui = $row['DUI_alumno'];
            $telefono = $row['telefono_alumno'];
            $email = $row['correo_alumno'];
            $contacto_emergencia = $row['contactoEmergencia_alumno'];
            $telefono_emergencia = $row['telEmergencia_alumno'];
        } else {
            $alumno_no_registrado = true;
        }
    }

    // Verificar si se ha enviado el formulario de modificación
    if (isset($_POST['modificar'])) {
        // Recoger los datos del formulario de modificación y limpiarlos
        $id_alumno = test_input($_POST['id_alumno']);
        $nombres = test_input($_POST['nombres']);
        $apellidos = test_input($_POST['apellidos']);
        $carrera = test_input($_POST['carrera']);
        $carnet = test_input($_POST['carnet']);
        $dui = test_input($_POST['dui']);
        $telefono = test_input($_POST['telefono']);
        $email = test_input($_POST['email']);
        $contacto_emergencia = test_input($_POST['contactoE']);
        $telefono_emergencia = test_input($_POST['telefonoE']);

        // Preparar la consulta SQL para llamar al stored procedure
        $sql_sp = "CALL modificar_alumno(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_sp = $conexion->prepare($sql_sp);
        $stmt_sp->bind_param("isssssssss", $id_alumno, $nombres, $apellidos, $carnet, $dui, $telefono, $email, $contacto_emergencia, $telefono_emergencia, $carrera);

        if ($stmt_sp->execute()) {
            $registro_modificado = true;
            $id_alumno = '';
            $nombres = '';
            $apellidos = '';
            $carrera = '';
            $carnet = '';
            $dui = '';
            $telefono = '';
            $email = '';
            $contacto_emergencia = '';
            $telefono_emergencia = '';
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
        <title>Modificar Alumno - Administrador de Laboratorios</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Links de Boostraps, iconoes, etc -->
        <?php include_once './includes/headLinks.php' ?>

        <!-- Enlace al archivo de SweetAlert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- JavaScript Libraries y Template-->
        <?php include './includes/scriptsLibrerias.php'; ?>
    </head>

    <body>

        <!-- Navbar o barra de navegacion con menu -->
        <?php $currentPage = 'encargadoModificarAlumno.php';
        include('includes/navBarEncargado.php');?>

        <!-- Header Banner -->
        <?php $bannerTitle = "Modificar Datos de Alumnos";
        include './includes/headerBanner.php'; ?>

        <!-- Formulario -->
        <div class="container" style="width: 800px;">
            <form class="row g-3" id="adminModificarA" action="encargadoModificarAlumno.php" method="post">

                <!-- Campo de búsqueda por carnet -->
                <div class="col-md-12">
                    <div class="input-group">
                        <input class="form-control" style="width: 675px;" type="search" placeholder="Carnet" aria-label="Search" name="carnet_busqueda">
                        <label class="input-group-text">
                            <button class="button-search" type="submit" name="buscar"><img src="./img/search2.ico" alt=""></button>
                        </label>
                    </div>
                </div>

                <!-- Campos del formulario -->
                <input type="hidden" name="id_alumno" value="<?php echo isset($id_alumno) ? $id_alumno : ''; ?>">
                <div class="col-md-6">
                    <label for="nombres" class="form-label">Nombres:</label>
                    <input type="text" class="form-control" id="nombres" name="nombres" value="<?php echo isset($nombres) ? $nombres : ''; ?>">
                </div>
                <div class="col-md-6">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo isset($apellidos) ? $apellidos : ''; ?>">
                </div>
                <div class="col-md-6">
                    <label for="carrera" class="form-label">Carrera</label>
                    <input type="text" class="form-control" id="carrera" name="carrera" value="<?php echo isset($carrera) ? $carrera : ''; ?>">
                </div>
                <div class="col-md-6">
                    <label for="carnet" class="form-label">Carnet</label>
                    <input type="text" class="form-control" id="carnet" name="carnet" value="<?php echo isset($carnet) ? $carnet : ''; ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label for="dui" class="form-label">DUI</label>
                    <input type="text" class="form-control" id="dui" name="dui" value="<?php echo isset($dui) ? $dui : ''; ?>">
                </div>
                <div class="col-md-6">
                    <label for="telefono" class="form-label">Telefono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo isset($telefono) ? $telefono : ''; ?>">
                </div>
                <div class="col-12">
                    <label for="email" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>">
                </div>
                <div class="col-md-6">
                    <label for="contactoE" class="form-label">Contacto emergencia</label>
                    <input type="text" class="form-control" id="contactoE" name="contactoE" value="<?php echo isset($contacto_emergencia) ? $contacto_emergencia : ''; ?>">
                </div>
                <div class="col-md-6">
                    <label for="telefonoE" class="form-label">Telefono emergencia</label>
                    <input type="text" class="form-control" id="telefonoE" name="telefonoE" value="<?php echo isset($telefono_emergencia) ? $telefono_emergencia : ''; ?>"><br>
                </div>

                <div class="container">
                    <div class="row">
                        <!-- Botón "Cancelar" -->
                        <div class="col-md-5 mx-auto">
                            <button type="button" class="btn btn-secondary" style="width: 200px;" onclick="limpiarFormulario()">Cancelar</button><br>
                        </div>
                        <!-- Botón "Agregar" -->
                        <div class="col-md-3 mx-auto">
                            <button class="btn btn-secondary" style="width: 200px;" type="submit" name="modificar">Modificar</button>
                        </div><br><br><br><br>
                    </div>
                </div>
            </form>
        </div>

        <!-- Script para limpiar el formulario -->
        <script>
            function limpiarFormulario() {
                // Seleccionar el formulario
                var form = document.getElementById("adminModificarA");
                // Obtener todos los elementos del formulario
                var formElements = form.elements;
                // Recorrer todos los elementos
                for (var i = 0; i < formElements.length; i++) {
                    var element = formElements[i];
                    element.value = '';
                }
            }
        </script>

        <!-- Agregar mensaje de registro -->
        <script>
        <?php if ($registro_modificado) { ?>
            Swal.fire({
                icon: 'success',
                title: 'Registro modificado con exito',
                showConfirmButton: true
            });
        <?php } ?>
        <?php if ($error_modificacion) { ?>
            Swal.fire({
                icon: 'error',
                title: 'Error al modificar',
                text: 'Error al actualizar los datos del alumno.',
                showConfirmButton: true
            });
        <?php } ?>
        <?php if ($alumno_no_registrado) { ?>
            Swal.fire({
                icon: 'error',
                title: 'Alumno no registrado',
                text: 'El carnet ingresado no se encuentra en la base de datos',
                showConfirmButton: true
            });
        <?php } ?>
        </script>
    </body>
</html>
