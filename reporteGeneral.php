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
    <title>Reporte General - Administrador de Laboratorios</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Links de Boostraps, iconos, etc -->
    <?php include_once './includes/headLinks.php' ?>

    <!-- JavaScript Libraries y Template-->
    <?php include './includes/scriptsLibrerias.php'; ?>
</head>

<body>
    <!-- Navbar o barra de navegacion con menu -->
    <?php
    $currentPage = 'reporteGeneral.php';
    include('includes/navBarAdmin.php');
    ?>

    <!-- Header Banner -->
    <?php
    $bannerTitle = "Reporte Alumnos (General)";
    include './includes/headerBanner.php';
    ?>

    <!-- Formulario -->
    <div class="container">
        <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="container-nav col-md-12">
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

            <?php
            // Verificar si se ha enviado el formulario de búsqueda
            if (isset($_POST['enviar'])) {
                // Recuperar el valor ingresado en la barra de búsqueda
                $busqueda = $_POST['busqueda'];

                // Consulta SQL para buscar al alumno por carnet
                $sql = "SELECT * FROM vista_alumnos WHERE Carnet = '$busqueda'";
                $result = mysqli_query($conexion, $sql);

                // Verificar si se encontraron resultados
                if ($result && mysqli_num_rows($result) > 0) {
                    // Mostrar los resultados
                    echo "<div class='container'>";
                    echo "<div class='row'>";
                    echo "<div class='col-md-12'>";
                    echo "<h3>Información del alumno:</h3>";
                    echo "<br>";
                    echo "<table class='table'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Carnet</th>";
                    echo "<th>Nombre</th>";
                    echo "<th>Carrera</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['Carnet']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Carrera']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                } else {
                    // Si no se encontraron resultados en vista_alumnos
                    echo "<div class='container text-center'>";
                    echo "<div class='row'>";
                    echo "<div class='col-md-12'>";
                    echo "<h3>No se encontraron resultados para la búsqueda.</h3>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }

                // Mostrar la información de la vista vista_horas_final
                $sql_horas = "SELECT * FROM vista_horas_final WHERE Carnet = '$busqueda' GROUP BY REVERSE(Ciclo)";
                $result_horas = mysqli_query($conexion, $sql_horas);

                if ($result_horas && mysqli_num_rows($result_horas) > 0) {
                    // Mostrar los resultados en formato de tabla
                    echo "<div class='container'>";
                    echo "<div class='row'>";
                    echo "<div class='col-md-12'>";
                    echo "<br>";
                    echo "<h3>Detalle de horas registradas por ciclos :</h3>";
                    echo "<br>";
                    echo "<table name='tablaHoras' id='tablaHoras' class='table table-bordered'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th style='width: 3%;'>ID Registro</th>";
                    echo "<th style='width: 7%;'>Ciclo</th>";
                    echo "<th style='width: 7%;'>Total horas</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    $contadorId = 0;
                    while ($row = mysqli_fetch_assoc($result_horas)) {
                        $contadorId++;
                        echo "<tr>";
                        echo "<td>" . $contadorId . "</td>";
                        echo "<td>" . htmlspecialchars($row['Ciclo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Total_Horas']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";

                    // Mostrar el Total General en una tabla independiente
                    echo "<div class='container'>";
                    echo "<div class='row'>";
                    echo "<div class='col-md-12'>";
                    echo "<br>";
                    echo "<table class='table table-bordered'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Total General</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    // Obtener el primer resultado para mostrar el Total General
                    mysqli_data_seek($result_horas, 0);
                    $row_total_general = mysqli_fetch_assoc($result_horas);
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row_total_general['Total_General']) . "</td>";
                    echo "</tr>";
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                } else {
                    // Si no se encontraron resultados en vista_horas_final
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
                <a href="pdfG.php?busqueda=<?php echo isset($busqueda) ? urlencode($busqueda) : ''; ?>" target="_blank"><img src="./img/PDF64px.png" alt=""></a>
                <a href="excelG.php?busqueda=<?php echo isset($busqueda) ? urlencode($busqueda) : ''; ?>" class="icon-link"><img src="./img/excel64px.png" alt=""></a>
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
