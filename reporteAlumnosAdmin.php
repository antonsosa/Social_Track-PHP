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
    </head>

    <body>
        <!-- Navbar o barra de navegacion con menu -->
        <?php $currentPage = 'reporteAlumnoAdmin.php';
        include('includes/navBarAdmin.php'); ?>

        <!-- Header Banner -->
        <?php $bannerTitle = "Reporte Alumnos (Ciclo)";
        include './includes/headerBanner.php'; ?>

        <!-- Formulario -->
        <div class="container" style="width: 1300px;">
            <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

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
                            <div class="input-group" style="width: 360px;" >
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

                <?php
                    // Verificar si se ha enviado el formulario de búsqueda
                    if(isset($_POST['enviar'])){
                        // Recuperar el valor ingresado en la barra de búsqueda
                        $busqueda = $_POST['busqueda'];
                        $ciclo = $_POST['ciclo'];

                        // Consulta SQL para buscar al alumno por carnet
                        $sql = "SELECT * FROM vista_horas_ciclo WHERE Carnet = '$busqueda' AND Ciclo = '$ciclo'";
                        $result = mysqli_query($conexion, $sql);

                        // Verificar si se encontraron resultados
                        if ($result->num_rows > 0) {
                            // Mostrar los resultados
                            echo "<div class='container'>";
                            echo "<div class='row'>";
                            echo "<div class='col-md-12'>";
                            echo "<h3>Información del alumno:</h3>";                  
                            echo "<table class='table'>";
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>Carnet</th>";
                            echo "<th>Nombre</th>";
                            echo "<th>Ciclo</th>";
                            echo "<th>Total de Horas (Ciclo Actual)</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['Carnet'] . "</td>";
                                echo "<td>" . $row['Nombre'] . "</td>";
                                echo "<td>" . $row['Ciclo'] . "</td>";
                                echo "<td>" . $row['Total_Horas'] . "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        } else { 
                            // Si no se encontraron resultados en vista_horas_ciclo
                            echo "<div class='container text-center'>";
                            echo "<div class='row'>";
                            echo "<div class='col-md-12'>";
                            //echo "<h3>No se encontraron resultados para la búsqueda.</h3>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }

                        // Mostrar la información de la vista vista_horas_alumnos
                        $sql_horas = "Select * from vista_horas_alumnos where Carnet = '$busqueda' AND Ciclo = '$ciclo'";
                        $result_horas = mysqli_query($conexion, $sql_horas);
                        $contadorId = 0;

                        if ($result_horas->num_rows > 0) {
                            // Mostrar los resultados en formato de tabla
                            echo "<div class='container'>";
                            echo "<div class='row'>";
                            echo "<div class='col-md-12'>";
                            echo "<br>";
                            echo "<br>";
                            echo "<h3>Detalle de horas registradas:</h3>";
                            echo "<br>";
                            echo "<table name='tablaHoras' id='tablaHoras' class='table table-bordered'>";
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th style='width: 3%;'>ID Registro</th>";
                            echo "<th style='width: 7%;'>Hora de entrada</th>";
                            echo "<th style='width: 7%;'>Hora de salida</th>";
                            echo "<th style='width: 30%;'>Actividad realizada</th>";
                            echo "<th style='width: 8%;'>Laboratorio</th>";
                            echo "<th style='width: 10%;'>Encargado</th>";
                            echo "<th style='width: 4%;'>Horas realizadas</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = $result_horas->fetch_assoc()) {
                                echo "<tr>";
                                $contadorId ++;
                                echo "<td>" . $contadorId . "</td>";
                                echo "<td>" . $row['Entrada'] . "</td>";
                                echo "<td>" . $row['Salida'] . "</td>";
                                echo "<td>" . $row['Actividad'] . "</td>";
                                echo "<td>" . $row['Laboratorio'] . "</td>";
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
                            // Si no se encontraron resultados en vista_horas_alumnos
                            echo "<div class='container text-center'>";
                            echo "<div class='row'>";
                            echo "<div class='col-md-12'>";
                            echo "<h3>No se encontraron horas registradas.</h3>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    }
                ?>
                <div class="container text-center"><br>
                <a href="pdfA.php?ciclo=<?php echo urlencode($ciclo); ?>&busqueda=<?php echo urlencode($busqueda); ?>" target="_blank"><img src="./img//PDF64px.png" alt=""></a>
                <a href="excelA.php?ciclo=<?php echo urlencode($ciclo); ?>&busqueda=<?php echo urlencode($busqueda); ?>" class="icon-link"><img src="./img/excel64px.png" alt=""></a>
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
