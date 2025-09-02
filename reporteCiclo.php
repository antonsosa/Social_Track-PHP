<?php
include 'conex.php';

require_once 'auth.php';

// Verificar que el usuario tiene el rol de administrador (rol id 1)
verificarRol(1);

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Reporte por Ciclo - Administrador de Laboratorios</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Links de Boostraps, iconoes, etc -->
        <?php include_once './includes/headLinks.php' ?>

        <!-- JavaScript Libraries y Template-->
        <?php include './includes/scriptsLibrerias.php'; ?>
        <style>
        .form-container3 {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 10vh; /* Ajustar altura mínima para centrar verticalmente */
        }
        .input-group3, .button-group3 {
            margin-bottom: 20px; /* Espacio entre los elementos */
        }
    
            .button-container {
                display: flex;
                justify-content: center; /* Centra el botón horizontalmente */
                width: 100%;
            }
    </style>
    </head>

    <body>
        <!-- Navbar o barra de navegacion con menu -->
        <?php $currentPage = 'reporteCiclo.php';
        include('includes/navBarAdmin.php'); ?>

        <!-- Header Banner -->
        <?php $bannerTitle = "Reporte por Ciclo";
        include './includes/headerBanner.php'; ?>

        <!-- Formulario -->
<div class="container form-container3" >
    <form class="row g-3" action="reporteCiclo.php" method="post">

        <!-- Dropdown list para seleccionar el ciclo -->
        <div class="input-group col-md-6">
            <table class="table">
                <thead>
                    <tr>
                        <th style="text-align: center;">Seleccionar ciclo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="text-align: center;">
                        <td>
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
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Botón "Buscar" -->
        <div class="button-container">
            <button type="submit" class="btn btn-secondary" style="width: 100px;" name="buscar">Buscar</button>
        </div>

    </form>
    <br>
</div>

        <!-- Información de horas registradas -->
        <div class="container" style="width: 800px;">
        <?php
            // Código PHP para mostrar la información de las horas registradas
            if (isset($_POST['buscar'])) {
                // Obtener el valor del ciclo seleccionado
                $ciclo = $_POST['ciclo'];

                // Realizar la consulta para obtener la información de las horas registradas en ese ciclo
                $sql_horas = "SELECT * FROM vista_horas_ciclo WHERE Ciclo = '$ciclo'";
                $result_horas = mysqli_query($conexion, $sql_horas);
                
                $contadorId = 0;
                // Mostrar los resultados en una tabla
                
                if ($result_horas && $result_horas->num_rows > 0) {
                    // Mostrar los resultados en formato de tabla
                    echo "<div class='container'>";
                    echo "<div class='row'>";
                    echo "<div class='col-md-12'>";
                    echo "<br>";
                    echo "<br>";
                    echo "<h3 style='text-align: center;'>Total horas registradas por estudiante en $ciclo</h3>";
                    echo "<br>";
                    echo "<table name='tablaHoras' id='tablaHoras' class='table table-bordered'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th style='width: 2%;'>Id</th>";
                    echo "<th style='width: 8%;'>Carnet</th>";
                    echo "<th style='width: 20%;'>Nombre</th>";
                    echo "<th style='width: 8%;'>Ciclo</th>";
                    echo "<th style='width: 7%;'>Total de horas</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($row = $result_horas->fetch_assoc()) {
                        echo "<tr>";
                        $contadorId ++;
                        echo "<td>" . $contadorId . "</td>";
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
                    // Si no se encontraron resultados
                    echo "<div class='container text-center'>";
                    echo "<div class='row'>";
                    echo "<div class='col-md-12'>";
                    echo "<h3>No se encontraron horas registradas para el ciclo seleccionado.</h3>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            }
        ?>
        </div>
        <!-- Botón para generar PDF -->
        <div class="container text-center"><br>
            <a href="pdfC.php?ciclo=<?php echo urlencode($ciclo); ?>" target="_blank" class="icon-link"><img src="./img/PDF64px.png" alt=""></a>
            <a href="excelC.php?ciclo=<?php echo urlencode($ciclo); ?>" class="icon-link"><img src="./img/excel64px.png" alt=""></a>
        </div><br>

<style>
.icon-link {
    display: inline-block;
    margin: 0 20px; /* Ajusta el valor para cambiar el espacio entre los enlaces */
}
</style>


    </body>
</html>
