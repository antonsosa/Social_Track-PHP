<?php
    // Incluir la librería FPDF
    require('fpdf.php');

    // Incluir el archivo de conexión a la base de datos
    include('conex.php');

    // Crear una nueva instancia de FPDF con orientación horizontal
    $pdf = new FPDF('L'); // 'L' para orientación horizontal
    $pdf->AddPage();

    // Establecer alias para el número total de páginas
    $pdf->AliasNbPages();

    // Especificar la fuente que deseas utilizar
    $pdf->SetFont('Arial', 'B', 19); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto

    // Mostrar el logo
    $pdf->Image('img/logoUtec.jpg', 10, 5, 30); // Nombre del archivo de la imagen, posición X, posición Y, tamaño de la imagen

    // Colocar el nombre de la empresa al lado derecho del logo
    $pdf->SetFont('Arial', 'B', 15); // Cambiar el tamaño de la fuente a 12
    $pdf->SetXY(68, 10); // Establecer la posición X y Y para el siguiente elemento
    $pdf->Cell(160, 15, utf8_decode('Universidad Tecnológica de El Salvador'), 0, 1, 'C'); // AnchoCelda, AltoCelda, título, borde(1-0), saltoLinea(1-0), posicion(L-C-R), ColorFondo(1-0)
    $pdf->SetFont('Arial', 'B', 12); // Cambiar el tamaño de la fuente a 12
    $pdf->SetXY(68, 10); // Establecer la posición X y Y para el siguiente elemento
    $pdf->Cell(160, 30, utf8_decode('Sistema de Control de Servicio Social en los Laboratorios de Informática'), 0, 1, 'C');
    $pdf->SetFont('Arial', 'B', 12); // Cambiar el tamaño de la fuente a 12
    $pdf->SetXY(68, 10); // Establecer la posición X y Y para el siguiente elemento
    $pdf->Cell(160, 43, utf8_decode('Reporte por Alumno'), 0, 1, 'C');
    
    $pdf->Ln(-5); // Salto de línea
    $pdf->SetTextColor(103); //color

    $busqueda = $_GET['busqueda'];
    //Tabla 1
    // Obtener los datos de la vista vista_horas_alumnos
    $sql_alumno = "SELECT * FROM vista_alumnos WHERE Carnet = '$busqueda'";
    $result_alumno = mysqli_query($conexion, $sql_alumno);
    $row = mysqli_fetch_assoc($result_alumno);
    $contadorId = 0;

    $sql_horas2 = "SELECT NOW() AS 'Hora del Servidor'";
    $result_horas2 = mysqli_query($conexion, $sql_horas2);
    $row2 = mysqli_fetch_assoc($result_horas2);

    if ($row2) {
        $pdf->SetXY(250, 10); // Establecer la posición X y Y para el siguiente elemento
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 7, utf8_decode($row2['Hora del Servidor']), 1, 0, 'C');
    }

    if ($row) {
        $pdf->SetXY(60, 35); // Establecer la posición X y Y para el siguiente elemento
        $pdf->SetTextColor(0, 0, 0); //colorTexto
        $pdf->SetDrawColor(163, 163, 163); //colorBorde
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 7, utf8_decode('CARNET'), 1, 0, 'C');
        $pdf->Cell(50, 7, utf8_decode('NOMBRE'), 1, 0, 'C');
        $pdf->Cell(70, 7, utf8_decode('CARRERA'), 1, 0, 'C');

        $pdf->Ln(7);
    
        $pdf->SetXY(60, 42); // Establecer la posición X y Y para el siguiente elemento
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 7, utf8_decode($row['Carnet']), 1, 0, 'C'); // Campo 'Id'
        $pdf->Cell(50, 7, utf8_decode($row['Nombre']), 1, 0, 'C'); // Campo 'Entrada'
        $pdf->Cell(70, 7, utf8_decode($row['Carrera']), 1, 0, 'C'); // Campo 'Salida'
    }

    //2da TABLA
    $sql_horas3 = "SELECT Carnet, Total_General FROM vista_horas_final WHERE Carnet = '$busqueda' ORDER BY Carnet LIMIT 1";
    $result_horas3 = mysqli_query($conexion, $sql_horas3);
    $row3 = mysqli_fetch_assoc($result_horas3);

    if($row3){
        $pdf->SetXY(210, 35); // Establecer la posición X y Y para el siguiente elemento
        $pdf->SetTextColor(0, 0, 0); //colorTexto
        $pdf->SetDrawColor(163, 163, 163); //colorBorde
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 7, utf8_decode('TOTAL GENERAL'), 1, 0, 'C');

        $pdf->Ln(5);

        $pdf->SetXY(210, 42); // Establecer la posición X y Y para el siguiente elemento
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 7, utf8_decode($row3['Total_General']), 1, 0, 'C'); // Campo 'Total_General'
    }

    //3da TABLA
    $sql_horas = "SELECT Total_General, Ciclo, Total_Horas FROM vista_horas_final WHERE Carnet = '$busqueda'";
    $result_horas = mysqli_query($conexion, $sql_horas);

    /* CAMPOS DE LA TABLA */
    $pdf->SetTextColor(0, 0, 0); //colorTexto
    $pdf->SetDrawColor(163, 163, 163); //colorBorde
    $pdf->SetXY(110, 55); // Establecer la posición X y Y para el siguiente elemento
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(5, 7, utf8_decode('ID'), 1, 0, 'C');
    $pdf->Cell(30, 7, utf8_decode('CICLO'), 1, 0, 'C');
    $pdf->Cell(30, 7, utf8_decode('TOTAL DE HORAS'), 1, 0, 'C');
    $pdf->Ln(7);

// Mostrar los datos en el PDF
if ($result_horas->num_rows > 0) {
    $pdf->SetFont('Arial', 'B', 8);
    while ($row = $result_horas->fetch_assoc()) {
        $contadorId++;
        $pdf->SetXY(110, $pdf->GetY()); // Establecer coordenadas X y mantener la coordenada Y actual
        $pdf->Cell(5, 7, utf8_decode($contadorId), 1, 0, 'C'); // Campo 'Id'
        $pdf->SetXY(115, $pdf->GetY()); // Establecer coordenadas X y mantener la coordenada Y actual
        $pdf->Cell(30, 7, utf8_decode($row['Ciclo']), 1, 0, 'C'); // Campo 'Entrada'
        $pdf->SetXY(145, $pdf->GetY()); // Establecer coordenadas X y mantener la coordenada Y actual
        $pdf->Cell(30, 7, utf8_decode($row['Total_Horas']), 1, 0, 'C'); // Campo 'Salida'
        $pdf->Ln(7);
    }
}

    // Pie de página
    $posicionPiePagina = $pdf->GetY();
    $pdf->SetY($posicionPiePagina); 
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Cell(0, 10, utf8_decode('Página '.$pdf->PageNo().' de {nb}'), 0, 0, 'C');
    

    // Salida del PDF
    $pdf->Output();
?>
