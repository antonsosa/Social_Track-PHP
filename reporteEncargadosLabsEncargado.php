<?php
    include 'conex.php';

    require_once 'auth.php';

    // Verificar que el usuario tiene el rol de encargado (rol id 2)
    verificarRol(2);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Detalle Encargados - Laboratorios</title>
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
        <?php $currentPage = 'reporteEncargadosLabsEncargado.php';
        include('includes/navBarEncargado.php'); ?>

        <!-- Header Banner -->
        <?php $bannerTitle = "Laboratorios Asignados";
        include './includes/headerBanner.php'; ?>

        <!-- Formulario -->
        <div class="container" style="width: 1000px;">
            <form class="row g-3" action="reporteEncargadosLabs.php" method="post">
                <?php
                    // Código para mostrar la información de la vista vista_horas_alumnos
                    $sql_horas = "SELECT * FROM vista_encargados";
                    $result_horas = mysqli_query($conexion, $sql_horas);
                    $contadorId = 0;

                    if ($result_horas->num_rows > 0) {
                        // Mostrar los resultados en formato de tabla
                        echo "<div class='container'>";
                        echo "<div class='row'>";
                        echo "<div class='col-md-12'>";
                        echo "<h3 style='text-align: center;'>Detalle de encargados y sus laboratorios asignados</h3>";
                        echo "<br>";
                        echo "<table name='tablaHoras' id='tablaHoras' class='table table-bordered'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th style='width: 2%;'>Nº</th>";
                        echo "<th style='width: 30%;'>Encargada/o</th>";
                        echo "<th style='width: 68%;'>Número de Laboratorios</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = $result_horas->fetch_assoc()) {
                            echo "<tr>";
                            $contadorId ++;
                            echo "<td>" . $contadorId . "</td>";
                            echo "<td>" . $row['nombre_completo'] . "</td>";
                            echo "<td>" . $row['laboratorios_asignados'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    
                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";

                        // Agregar botones de exportación
                        echo "<div class='container'>";
                        echo "<div class='row'>";
                        echo "<div class='col-md-12'>";
                        echo "<br>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }else {
                        // Si no se encontraron resultados
                        echo "<div class='container'>";
                        echo "<div class='row'>";
                        echo "<div class='col-md-12'>";
                        echo "<h3>No se encontraron horas registradas.</h3>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                ?>
                <div class="container text-center"><br>
                    <a href="pdfE.php" target="_blank" class="icon-link"><img src="./img/PDF64px.png" alt=""></a>

                </div><br>

<style>
.icon-link {
    display: inline-block;
    margin: 0 20px; /* Ajusta el valor para cambiar el espacio entre los enlaces */
}
</style>


            </form><br>
        </div>
    </body>
</html>