<?php
include 'conex.php';

    
require_once 'auth.php';

// Verificar que el usuario tiene el rol de encargado (rol id 2)
verificarRol(2);


$registro_modificado = false;
$error_registro = false;
$error_registro2 = false;
$error_laboratorio = false;
$error_carnet = false;
$error_registro3 = false;

// Llamar ciclo actual a campo hidden
$sql_ciclo = "SELECT * FROM ciclo_actual";
$stmt_ciclo = $conexion->prepare($sql_ciclo);
$stmt_ciclo->execute();
$result_ciclo = $stmt_ciclo->get_result();

if ($result_ciclo->num_rows > 0) {
    // Si se encuentra el ciclo actual, almacenarlo en la variable
    $row = $result_ciclo->fetch_assoc();
    $ciclo = $row['ciclo_actual'];
}
$id_encargado = $_SESSION['id']; // id almacenado en la sesión

// Verificar si se ha enviado el formulario para marcar la entrada o salida
if (isset($_POST['guardar'])) {
    // Obtener los valores del formulario
    $horaEntrada = $_POST['horaEntrada'];
    $actividad = $_POST['actividad'];
    $laboratorio = $_POST['laboratorio'];
    $horaSalida = $_POST['horaSalida'];
    $carnet_alumno = $_POST['carnet'];
    $id_encargado = $_POST['idEncargado'];
    $nombre_ciclo = $_POST['ciclo_actual'];

    // Concatenar "Laboratorio " con el número recibido
    $nombre_laboratorio = "Laboratorio " . $laboratorio;

    // Convertir horaSalida a NULL si está vacío
    $horaSalida = empty($horaSalida) ? NULL : $horaSalida;

    // Verificar si los campos requeridos están vacíos
    if (empty($carnet_alumno) || empty($horaEntrada) || empty($laboratorio)) {
        $error_registro2 = true; // Error por campos vacíos
    } else {
        // Verificar si el carnet del alumno existe en la base de datos
        $query_check_carnet = "SELECT COUNT(*) FROM alumnos WHERE carnet_alumno = ?";
        $stmt_check_carnet = $conexion->prepare($query_check_carnet); 
        $stmt_check_carnet->bind_param("s", $carnet_alumno); 
        $stmt_check_carnet->execute(); 
        $stmt_check_carnet->bind_result($count_carnet); 
        $stmt_check_carnet->fetch(); 
        $stmt_check_carnet->close();

        if ($count_carnet == 0) {
            $error_carnet = true; // Error por carnet inexistente
        } else {
            // Verificar si el laboratorio existe en la base de datos
            $query_check_lab = "SELECT COUNT(*) FROM laboratorios WHERE id_laboratorio = ?";
            $stmt_check_lab = $conexion->prepare($query_check_lab); 
            $stmt_check_lab->bind_param("i", $laboratorio); 
            $stmt_check_lab->execute(); 
            $stmt_check_lab->bind_result($count_lab); 
            $stmt_check_lab->fetch(); 
            $stmt_check_lab->close();

            if ($count_lab == 0) {
                $error_laboratorio = true; // Error por laboratorio inexistente
            } else {
                // Verificar si ya existe un registro con el mismo carnet y horaSalida es NULL
                $query_check = "SELECT COUNT(*) FROM horas WHERE id_alumno_hora = ? AND salida_hora IS NULL";
                $stmt_check = $conexion->prepare($query_check); 
                $stmt_check->bind_param("s", $carnet_alumno); 
                $stmt_check->execute(); 
                $stmt_check->bind_result($count); 
                $stmt_check->fetch(); 
                $stmt_check->close();

                if ($count > 0) {
                    $error_registro = true; // Error por registro existente sin salida
                } else {
                    // Llamar al procedimiento almacenado para insertar los datos
                    $stmt = $conexion->prepare("CALL Insertar_Horas(?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssisss", $horaEntrada, $horaSalida, $actividad, $carnet_alumno, $id_encargado, $laboratorio, $nombre_laboratorio, $nombre_ciclo);
                    $stmt->execute();
                    $registro_modificado = true; // Registro insertado correctamente

                     // Redireccionar al mismo script para evitar reenvío del formulario al actualizar la página
                     header("Location: " . $_SERVER['PHP_SELF']);
                     exit;
                }
            }
        }
    }
}

// Verificar si se ha enviado un formulario de modificación
if (isset($_POST["guardar2"])) {
    // Asignar valores a los parámetros
    $id_registro = $_POST["id_registro2"];
    $carnet = $_POST["carnet2"];
    $hora_entrada = $_POST["horaEntrada2"];
    $actividad = $_POST["actividad2"];
    $laboratorio = $_POST["laboratorio2"];
    $hora_salida = $_POST["horaSalida2"];

    if (!empty($actividad) && !empty($hora_salida)) {
        // Llamar al procedimiento almacenado
        $stmt = $conexion->prepare("CALL Update_Horas(?, ?, ?, ?, ?, ?, @p_result)");
        $stmt->bind_param("isssis", $id_registro, $carnet, $hora_entrada, $actividad, $laboratorio, $hora_salida);
        $stmt->execute();

        // Obtener el valor de la variable de salida
        $select = $conexion->query("SELECT @p_result");
        $result = $select->fetch_assoc();
        $p_result = $result['@p_result'];

        if ($p_result == 1) {
            $registro_modificado = true;
        } else {
            $error_registro3 = true;
        }
    }else {
        $error_registro2 = true;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Marcar - Encargado de Laboratorios</title>
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
    <?php $currentPage = 'encargadoMarcacion.php';
    include('includes/navBarEncargado.php'); ?>

    <!-- Header Banner -->   
    <?php $bannerTitle = "Marcación";
    include './includes/headerBanner.php'; ?>

    <!-- Formulario1 - Revisar duracion de una sesion de horas sociales -->       
    <div class="container">
        <!-- Formulario2 - Insertar horas sociales -->  
        <div class="form-contenedor">    
            <form class="row g-3" action="encargadoMarcacion.php" method="post">
                <input type="hidden" name="idEncargado" value="<?php echo isset($id_encargado) ? $id_encargado : ''; ?>">
                <input type="hidden" name="ciclo_actual" value="<?php echo isset($ciclo) ? $ciclo : ''; ?>">

                <div class="contenedor">
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
                            <tr>
                                <td>
                                    <input type="hidden" class="form-control" name="Id" id="Id" style="width: 1px;" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="carnet" id="carnet" style="width: 150px;">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="horaEntrada" id="horaEntrada" style="width: 175px;" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="actividad" id="actividad" style="width: 250px;" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="laboratorio" id="laboratorio" style="width: 50px;">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="horaSalida" id="horaSalida" style="width: 175px;" readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success" id="botonEntrada" name="entrada">Entrada</button>
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-warning" name="guardar" id="botonGuardar">Agregar</button>
                                </td>
                            </tr>
                            <?php
                                    // Llamar al procedimiento almacenado para obtener los datos del encargado por id_encargado
                                    $stmt2 = $conexion->prepare("CALL ObtenerRegistrosLaboratorios(?)");
                                    $stmt2->bind_param("i", $id_encargado);
                                    $stmt2->execute();
                                    $result = $stmt2->get_result();
                                    if ($result->num_rows > 0) {
                                        // Mostrar los resultados
                                        while ($row = $result->fetch_assoc()) {
                                            ?>
                                            <tr>
                                                <form class="form-guardado" action="encargadoMarcacion.php" method="post" onsubmit="return validarFormulario(<?php echo $row['id_registro_hora']; ?>)">
                                                    <td><input type="hidden" class="form-control" name="id_registro2" value="<?php echo $row['id_registro_hora']; ?>"></td>
                                                    <td><input type="text" style="width: 150px;" class="form-control" name="carnet2" id="carnet2_<?php echo $row['id_registro_hora']; ?>" value="<?php echo $row['id_alumno_hora']; ?>" readonly></td>
                                                    <td><input type="text" style="width: 175px;"  class="form-control" name="horaEntrada2" id="horaEntrada2_<?php echo $row['id_registro_hora']; ?>" value="<?php echo $row['entrada_hora']; ?>" readonly></td>
                                                    <td><input type="text" style="width: 250px;" class="form-control" name="actividad2" id="actividad2_<?php echo $row['id_registro_hora']; ?>" value="<?php echo $row['actividad_hora']; ?>" maxlength="90"></td>
                                                    <td><input type="text" style="width: 50px;" class="form-control" name="laboratorio2" id="laboratorio2_<?php echo $row['id_registro_hora']; ?>" value="<?php echo $row['id_laboratorio_hora']; ?>" readonly></td>
                                                    <td><input type="text" style="width: 175px;" class="form-control" name="horaSalida2" id="horaSalida2_<?php echo $row['id_registro_hora']; ?>" value="<?php echo $row['salida_hora']; ?>" readonly></td>
                                                    <td><button type="button" class="btn btn-danger botonSalida" data-id="<?php echo $row['id_registro_hora']; ?>" name="salida">Salida</button></td>
                                                    <td><button type="submit" class="btn btn-warning" name="guardar2">Guardar</button></td>
                                                </form>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                            <script>
                                document.addEventListener("DOMContentLoaded", function () {
                                    var horaActualPHP = "";
                                    var botonEntrada = document.getElementById('botonEntrada');
                                    botonEntrada.addEventListener("click", function () {
                                        // Obtener la hora del servidor al hacer clic en el botón de entrada
                                        obtenerHoraServidor("entrada");
                                        // Deshabilitar el botón después de hacer clic en él
                                        botonEntrada.disabled = true;
                                    });
                                    var botonesSalida = document.querySelectorAll('.botonSalida');
                                    botonesSalida.forEach(function (boton) {
                                        boton.addEventListener("click", function () {
                                            var idRegistro = this.getAttribute('data-id');
                                            obtenerHoraServidor("salida", idRegistro);
                                        });
                                    });
                                    function obtenerHoraServidor(tipo, idRegistro) {
                                        // Realizar una petición AJAX para obtener la hora actual del servidor
                                        var xhr = new XMLHttpRequest();
                                        xhr.onreadystatechange = function () {
                                            if (xhr.readyState === 4 && xhr.status === 200) {
                                                horaActualPHP = xhr.responseText;
                                                // Actualizar el campo de hora correspondiente según el tipo de botón
                                                if (tipo === "entrada") {
                                                    document.getElementById('horaEntrada').value = horaActualPHP;
                                                } else if (tipo === "salida") {
                                                    document.getElementById('horaSalida2_' + idRegistro).value = horaActualPHP;
                                                }
                                            }
                                        };
                                        xhr.open("GET", "obtenerHoraServidor.php", true);
                                        xhr.send();
                                    }
                                });
                            </script>
                            <!-- Agregar mensaje de registro -->
                            <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                <?php if ($error_registro) { ?>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error, Carnet con registro activo',
                                        showConfirmButton: true
                                    });
                                <?php } ?>
                                <?php if ($error_registro2) { ?>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error, Llenar todos los campos',
                                        showConfirmButton: true
                                    });
                                <?php } ?>
                                <?php if ($error_registro3) { ?>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error, El registro ya fue cerrado',
                                        showConfirmButton: true
                                    });
                                <?php } ?>
                                <?php if ($error_laboratorio) { ?>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error, El Laboratorio no existe',
                                        showConfirmButton: true
                                    });
                                <?php } ?>
                                <?php if ($registro_modificado) { ?>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Registro guardado',
                                        showConfirmButton: true
                                    });
                                <?php } ?>
                                <?php if ($error_carnet) { ?>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'El carnet ingresado no existe',
                                        showConfirmButton: true
                                    });
                                <?php } ?>
                            });
                            </script>
                        </tbody>
                    </table><br>
                </div>
                <div class="container text-center">
                    <a href="encargadoMarcacion.php" > <button type="button" class="btn btn-secondary">Actualizar</button></a>
                </div>
            </form>
        </div> 
    </div>
</body>
</html>
