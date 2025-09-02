<?php
    include 'conex.php';

    require_once 'auth.php';

    // Verificar que el usuario tiene el rol de administrador (rol id 1)
    verificarRol(1);
    $modificacion = false;
    $modificacion2 = false;
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Modificar Horas - Administrador de Laboratorios</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Links de Boostraps, iconoes, etc -->
        <?php include_once './includes/headLinks.php' ?>

        <!-- JavaScript Libraries y Template-->
        <?php include './includes/scriptsLibrerias.php'; ?>
    </head>

    <body>
        <!-- Navbar o barra de navegacion con menu -->
        <?php $currentPage = 'adminModificarMarcacion.php';
        include('includes/navBarAdmin.php'); ?>

        <!-- Header Banner -->   
        <?php $bannerTitle = "Modificar Datos de Marcación";
        include './includes/headerBanner.php'; ?>

            <style>
                .icon-link {
                    display: inline-block;
                    margin: 0 20px; /* Ajusta el valor para cambiar el espacio entre los enlaces */
                }
            </style>
            <!-- Formulario2 - Insertar horas sociales -->  
            <div class="form2-contenedor2" >    
                <form class="row g-3" action="adminModificarMarcacion.php" method="post">
                    <!-- Formulario1 - Revisar duraccion de untila sesion de horas sociales -->       
                    <div class="container-nav col-md-12">
                        <!-- Dropdown list para seleccionar el ciclo -->
                        <nav class="navbar navbar-light bg-transparent">
                            <div class="col-md-6">
                                <div class="input-group" style="width: 300px;" >
                                    <select class="form-control custom-select"  aria-label="ciclo" name="ciclo">
                                        <option value="" selected>Seleccionar el ciclo</option>
                                        <?php
                                            // Consulta SQL para obtener los datos del ciclo desde la nueva vista
                                            $sql_ciclos = "SELECT * FROM vista_ciclos";
                                            $result_ciclos = mysqli_query($conexion, $sql_ciclos);

                                            // Verificar si se encontraron resultados
                                            if ($result_ciclos->num_rows > 0) {
                                                // Generar las opciones del dropdown
                                                while ($row = $result_ciclos->fetch_assoc()) {
                                                    echo "<option value='" . $row['Ciclo'] . "'>" . $row['Ciclo'] . "</option>";
                                                }
                                            } else {
                                                // Manejar el caso en que no se encuentren resultados
                                                echo "<option value='' disabled selected>No hay ciclos disponibles</option>";
                                            }
                                        ?>
                                    </select>
                                </div><br>
                            </div>
                        </nav>

                        <!-- Campo de búsqueda -->
                        <nav class="navbar navbar-light bg-transparent">
                            <div class="col-md-6">
                                <div class="input-group" style="width: 360px;">
                                    <input class="form-control" type="text"
                                        placeholder="Digitar carnet en formato: 27-2727-2024" aria-label="Search" name="busqueda">
                                    <label class="input-group-text" for="enviar" height="50px">
                                        <button type="submit" class="button-search" name="enviar">
                                            <img src="./img/search2.ico" alt="buscar">
                                        </button>
                                    </label>
                                </div>
                            </div>
                        </nav>                   
                    </div>
                        <!-- Botón para cancelar y limpiar el formulario -->
                        <div class="text-center">
                        <button type="submit" class="btn btn-secondary" style="width: 200px;" name="cancelar">Cancelar</button>
                        </div>
                    <div  class="container-nav col-md-12" >
                        <table class="table">
                            <thead>
                                <tr>  
                                    <th type="hidden" style="text-align: center;"></th>
                                    <th style="text-align: center;">Carnet</th>           
                                    <th style="text-align: center;">Hora entrada</th>
                                    <th style="text-align: center;">Actividad realizada</th>   
                                    <th style="text-align: center;">Lab</th>        
                                    <th style="text-align: center;">Hora salida</th>                   
                                    <th colspan="3" style="text-align: center;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // Verificar si se ha enviado un formulario de modificación
                                    if(isset($_POST["guardar2"])){
                                        // Asignar valores a los parámetros
                                        $id_registro = $_POST["id_registro2"];
                                        $carnet = $_POST["carnet2"];
                                        $hora_entrada = $_POST["horaEntrada2"];
                                        $actividad = $_POST["actividad2"];
                                        $laboratorio = $_POST["laboratorio2"];
                                        $hora_salida = $_POST["horaSalida2"];
                                        
                                        if (!empty($actividad) && !empty($hora_salida)) {
                                        // Llamar al procedimiento almacenado
                                        $stmt = $conexion->prepare("CALL Update_Horas2(?, ?, ?, ?, ?, ?)");
                                        $stmt->bind_param("isssis", $id_registro, $carnet, $hora_entrada, $actividad, $laboratorio, $hora_salida);
                                        $stmt->execute();
                                        $modificacion2 = true;
                                    } else {
                                        $modificacion = true;
                                    }
                                    }

                                    // Manejar la eliminación de variables de sesión
                                    if (isset($_POST['cancelar'])) {
                                        unset($_SESSION['busqueda']);
                                        unset($_SESSION['ciclo']);
                                        // Redirigir para evitar reenvío de formulario
                                        exit();
                                    }

                                    // Obtener los valores del formulario y guardarlos en la sesión
                                    if (isset($_POST['enviar'])) {
                                        $busqueda = $_POST['busqueda'];
                                        $ciclo = $_POST['ciclo'];
                                        // Guardar las variables en la sesión
                                        $_SESSION['busqueda'] = $busqueda;
                                        $_SESSION['ciclo'] = $ciclo;
                                    }

                                    // Recuperar las variables de sesión y usarlas en la llamada al procedimiento almacenado
                                    if (isset($_SESSION['busqueda']) && isset($_SESSION['ciclo'])) {
                                        $busqueda = $_SESSION['busqueda'];
                                        $ciclo = $_SESSION['ciclo'];

                                        // Llamar al procedimiento almacenado para obtener los datos del alumno por parámetros
                                        $stmt2 = $conexion->prepare("CALL registros_horas(?, ?)");
                                        $stmt2->bind_param('ss', $busqueda, $ciclo);
                                        $stmt2->execute();
                                        $result = $stmt2->get_result();

                                        if ($result->num_rows > 0) {
                                            // Mostrar los resultados
                                            while ($row = $result->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <form id="modificarEA" action="adminModificarMarcacion.php" method="post">
                                                        <td><input type="hidden" class="form-control" name="id_registro2" value="<?php echo $row['id_registro_hora']; ?>"></td>
                                                        <td><input type="text" style="width: 135px;" class="form-control" name="carnet2" id="carnet2_<?php echo $row['id_registro_hora']; ?>" value="<?php echo $row['id_alumno_hora']; ?>" readonly></td>
                                                        <td><input type="text" style="width: 175px;" class="form-control" name="horaEntrada2" id="horaEntrada2_<?php echo $row['id_registro_hora']; ?>" value="<?php echo $row['entrada_hora']; ?>"></td>
                                                        <td><input type="text" style="width: 250px;" class="form-control" name="actividad2" id="actividad2_<?php echo $row['id_registro_hora']; ?>" value="<?php echo $row['actividad_hora']; ?>"></td>
                                                        <td><input type="text" style="width: 50px;" class="form-control" name="laboratorio2" id="laboratorio2_<?php echo $row['id_registro_hora']; ?>" value="<?php echo $row['id_laboratorio_hora']; ?>"></td>
                                                        <td><input type="text" style="width: 175px;" class="form-control" name="horaSalida2" id="horaSalida2_<?php echo $row['id_registro_hora']; ?>" value="<?php echo $row['salida_hora']; ?>"></td>
                                                        <td><button type="submit" class="btn btn-warning" name="guardar2">Actualizar</button></td>
                                                    </form>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                    <br>
                                    <!-- Script para limpiar el formulario -->
                                    <script>
                                    function limpiarFormulario() {
                                        var form = document.getElementById("modificarEA");
                                        if (form) {
                                            form.reset();
                                        }
                                    }
                                    </script>
                                    <!-- Agregar mensaje de registro -->
                                    <?php if ($modificacion) { ?>
                                        <script>
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error, Llenar todos los campos',
                                                showConfirmButton: true,
                                            });
                                        </script>
                                    <?php } ?>
                                    <?php if ($modificacion2) { ?>
                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Registro modificado',
                                                showConfirmButton: true,
                                            });
                                        </script>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div><p><br><br> </p>
                </form>
            </div> 
        </div>
    </body><br><br>
</html>
