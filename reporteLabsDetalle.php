<?php
    include 'conex.php';
    // Confirmar sesión, si no está logged in, lo manda a index.php
    require_once 'auth.php';

    // Verificar que el usuario tiene el rol de administrador (rol id 1)
    verificarRol(1);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Consultar Alumnos - Administrador de Laboratorios</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Links de Boostraps, iconoes, etc -->
    <?php include_once './includes/headLinks.php' ?>

    <!-- JavaScript Libraries y Template-->
    <?php include './includes/scriptsLibrerias.php'; ?>
    <style>
        .container-nav {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .form-group {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
        }
        .form-group .input-group {
            margin-right: 10px;
        }
        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <!-- Navbar o barra de navegacion con menu -->
    <?php $currentPage = 'reporteLabsDetalle.php';
    include('includes/navBarAdmin.php'); ?>

    <!-- Header Banner -->
    <?php $bannerTitle = "Reporte Laboratorios (Detalle)";
    include './includes/headerBanner.php'; ?>

    <!-- Formulario -->
    <div class="container" style="width: 1300px;">
        <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="container-nav col-md-12">

                <div class="form-group">
                    <!-- Dropdown list para seleccionar el ciclo -->
                    <div class="input-group" style="width: 300px;">
                        <select class="form-control custom-select" aria-label="ciclo" name="ciclo">
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
                    </div>

                    <!-- Campo de búsqueda -->
                    <div class="input-group" style="width: 300px;">
                        <select class="form-control custom-select" aria-label="laboratorio" name="laboratorio">
                            <option value="" selected>Seleccionar el laboratorio</option>
                            <?php
                            // Consulta SQL para obtener los datos del ciclo desde la nueva vista
                            $sql_ciclos = "SELECT * FROM vista_laboratorios";
                            $result_ciclos = mysqli_query($conexion, $sql_ciclos);

                            // Verificar si se encontraron resultados
                            if ($result_ciclos->num_rows > 0) {
                                // Generar las opciones del dropdown
                                while ($row = $result_ciclos->fetch_assoc()) {
                                    echo "<option value='" . $row['nombre_laboratorio'] . "'>" . $row['nombre_laboratorio'] . "</option>";
                                }
                            } else {
                                // Manejar el caso en que no se encuentren resultados
                                echo "<option value='' disabled selected>No hay laboratorios disponibles</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                 <br>               
                <!-- Botón de búsqueda -->
                <div class="button-container">
                    <button class="btn btn-secondary" type="submit" class="button-search" name="enviar" style="width: 100px;">
                        Buscar
                    </button>
                </div>
            </div>
            <?php
                // Verificar si se ha enviado el formulario de búsqueda
                if(isset($_POST['enviar'])){
                    // Recuperar el valor ingresado en la barra de búsqueda
                    $ciclo = $_POST['ciclo'];
                    $busqueda = $_POST['laboratorio'];

                    // Mostrar la información de la vista vista_horas_alumnos
                    $sql_horas = "SELECT * FROM vista_horas_alumnos WHERE Laboratorio = '$busqueda' AND Ciclo = '$ciclo'";
                    $result_horas = mysqli_query($conexion, $sql_horas);
                    $contadorId = 0;

                    if ($result_horas->num_rows > 0) {
                        // Mostrar los resultados en formato de tabla
                        echo "<div class='container'>";
                        echo "<div class='row'>";
                        echo "<div class='col-md-12'>";
                        echo "<br>";
                        echo "<br>";
                        echo "<h3 style='text-align: center;'>Detalle de horas registradas para $busqueda en $ciclo</h3>";
                        echo "<br>";
                        echo "<table name='tablaHoras' id='tablaHoras' class='table table-bordered'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th style='width: 3%;'>Id</th>";
                        echo "<th style='width: 10%;'>Carnet</th>";
                        echo "<th style='width: 20%;'>Nombre</th>";
                        echo "<th style='width: 10%;'>Hora de entrada</th>";
                        echo "<th style='width: 10%;'>Hora de salida</th>";
                        echo "<th style='width: 38%;'>Actividad realizada</th>";
                        echo "<th style='width: 8%;'>Encargado</th>";
                        echo "<th style='width: 4%;'>Horas realizadas</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = $result_horas->fetch_assoc()) {
                            echo "<tr>";
                            $contadorId ++;
                            echo "<td>" . $contadorId . "</td>";
                            echo "<td>" . $row['Carnet'] . "</td>";
                            echo "<td>" . $row['Nombre'] . "</td>";
                            echo "<td>" . $row['Entrada'] . "</td>";
                            echo "<td>" . $row['Salida'] . "</td>";
                            echo "<td>" . $row['Actividad'] . "</td>";
                            echo "<td>" . $row['Encargado'] . "</td>";
                            echo "<td>" . $row['Horas'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    } else {
                        // Si no se encontraron resultados
                        echo "<div class='container text-center'>";
                        echo "<div class='row'>";
                        echo "<div class='col-md-12'>";
                        echo "<h3>No se encontraron horas registradas para $busqueda en $ciclo.</h3>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>"; // Cierre del contenedor adicional
                }
            ?>
            <div class="container text-center"><br>
                <a href="pdfLD.php?ciclo=<?php echo urlencode($ciclo); ?>&busqueda=<?php echo urlencode($busqueda); ?>" target="_blank"><img src="./img//PDF64px.png" alt=""></a>
                <a href="excelLD.php?ciclo=<?php echo urlencode($ciclo); ?>&busqueda=<?php echo urlencode($busqueda); ?>" class="icon-link"><img src="./img/excel64px.png" alt=""></a>
            </div><br>
        </form><br>
    </div>
    <style>
        .icon-link {
            display: inline-block;
            margin: 0 20px; /* Ajusta el valor para cambiar el espacio entre los enlaces */
        }
    </style>
</body>
</html>
