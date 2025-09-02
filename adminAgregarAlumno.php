<?php
    // Conectando con la base de datos 
    require_once "conex.php";

    require_once 'auth.php';

    // Verificar que el usuario tiene el rol de administrador (rol id 1)
    verificarRol(1);

    // Definir una variable para controlar si se muestra la alerta de SweetAlert
    $registro_agregado = false;
    $error_registro = false;

    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recoger los datos del formulario
        $nombres = test_input($_POST['nombres']);
        $apellidos = test_input($_POST['apellidos']);
        $carnet = test_input($_POST['carnet']);
        $dui = test_input($_POST['dui']);
        $telefono = test_input($_POST['telefono']);
        $email = test_input($_POST['email']);
        $contacto_emergencia = test_input($_POST['contacto']);
        $telefono_emergencia = test_input($_POST['telefonoE']);
        $carrera = test_input($_POST['carrera']);

        // Preparar la consulta SQL para verificar si ya existen datos iguales
        $sql_select = "SELECT COUNT(*) AS num_rows FROM alumnos WHERE carnet_alumno = ? OR correo_alumno = ?";

        // Ejecutar la consulta SELECT
        $stmt = $conexion->prepare($sql_select);
        $stmt->bind_param("ss", $carnet, $email);
        $stmt->execute();
        $result_select = $stmt->get_result();
        $row_check = $result_select->fetch_assoc();

        // Verificar si ya existen datos iguales
        if ($row_check['num_rows'] > 0) {
            // Ya existe un registro con el mismo carnet o DUI
            $error_registro = true;
        } else {
            // Preparar la llamada al procedimiento almacenado
            $sql_call_procedure = "CALL InsertarAlumno(?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Ejecutar la llamada al procedimiento almacenado
            $stmt_insert = $conexion->prepare($sql_call_procedure);
            $stmt_insert->bind_param("sssssssss", $nombres, $apellidos, $carnet, $dui, $telefono, $email, $contacto_emergencia, $telefono_emergencia, $carrera);

            $stmt_insert->execute();
            if ($stmt_insert->affected_rows > 0) {
                // La inserción fue exitosa
                $registro_agregado = true;

                // Limpiar los datos
                $nombres = '';
                $apellidos = '';
                $carnet = '';
                $dui = '';
                $telefono = '';
                $email = '';
                $contacto_emergencia = '';
                $telefono_emergencia = '';
                $carrera = '';
            } else {
                // Hubo un error durante la inserción
                $error_registro = true;
            }
        }
        // Cerrar la conexión
        $conexion->close();
    }

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
        <title>Agregar Alumnos - Administrador de Laboratorios</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Links de Boostraps, iconoes, etc -->
        <?php include_once './includes/headLinks.php' ?>

        <!-- JavaScript Libraries y Template-->
        <?php include './includes/scriptsLibrerias.php'; ?>

        <!-- enlace a archivo js para validaciones -->
        <script src="js/validaAdmin_Al.js"></script>
    </head>

    <body>


        <!-- Navbar o barra de navegacion con menu -->
        <?php $currentPage = 'adminAgregarAlumno.php';
        include('includes/navBarAdmin.php'); ?>

        <!-- Header Banner -->
        <?php $bannerTitle = "Agregar Datos de Alumnos";
        include './includes/headerBanner.php'; ?>

        <!-- Formulario -->
        <div class="container" style="width: 800px;">
            <form class="row g-3" id="adminAlumno" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div class="col-md-6">
                    <label for="nombres" class="form-label">Nombres:</label>
                    <input type="text" class="form-control" id="nombres" name="nombres" value="<?php echo isset($nombres) ? $nombres : ''; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo isset($apellidos) ? $apellidos : ''; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="carrera" class="form-label">Carrera</label>
                    <input type="text" class="form-control" id="carrera" name="carrera" value="<?php echo isset($carrera) ? $carrera : ''; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="carnet" class="form-label">Carnet</label>
                    <input type="text" class="form-control" id="carnet" name="carnet" value="<?php echo isset($carnet) ? $carnet : ''; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="dui" class="form-label">DUI</label>
                    <input type="text" class="form-control" id="dui" name="dui" value="<?php echo isset($dui) ? $dui : ''; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="telefono" class="form-label">Telefono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo isset($telefono) ? $telefono : ''; ?>" required>
                </div>
                <div class="col-12">
                    <label for="email" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required autocomplete="off">
                </div>
                <div class="col-md-6">
                    <label for="contacto" class="form-label">Contacto emergencia</label>
                    <input type="text" class="form-control" id="contacto" name="contacto" value="<?php echo isset($contacto_emergencia) ? $contacto_emergencia : ''; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="telefonoE" class="form-label">Telefono emergencia</label>
                    <input type="text" class="form-control" id="telefonoE" name="telefonoE" value="<?php echo isset($telefono_emergencia) ? $telefono_emergencia : ''; ?>" required><br>
                </div>

                <div class="container">
                    <div class="row">
                         <!-- Botón "Cancelar" -->
                        <div class="col-md-5 mx-auto">
                            <button type="button" class="btn btn-secondary" style="width: 200px;" onclick="limpiarFormulario()">Cancelar</button><br>
                        </div>
                        <!-- Botón "Agregar" -->
                        <div class="col-md-3 mx-auto">
                            <button class="btn btn-secondary" style="width: 200px;" type="submit">Registrar</button>
                        </div>
                    </div><br><br>
                </div>
            </form>
        </div>

        <!-- Script para limpiar el formulario -->
        <script>
            function limpiarFormulario() {
                // Seleccionar el formulario
                var form = document.getElementById("adminAlumno");
                // Restablecer los valores de los campos del formulario
                form.reset();
            }
        </script>

        <!-- Agregar mensaje de registro -->
        <script>
            <?php if ($registro_agregado){ ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Registro agregado',
                    showConfirmButton: true,
                });
            <?php } elseif ($error_registro){ ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error al registrar',
                    text: 'Imposible continuar! Datos duplicados en Carnet o Correo.',
                    showConfirmButton: true,
                });
            <?php } ?>
        </script>
    </body>
</html>
