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

    $ciclo = $_GET['ciclo'];

    //Tabla 1
    // Obtener los datos de la vista vista_horas_alumnos
    $sql_laboratorio = "SELECT * FROM vista_horas_ciclo WHERE Ciclo = '$ciclo'";
    $result_laboratorio = mysqli_query($conexion, $sql_laboratorio);
    $row = mysqli_fetch_assoc($result_laboratorio);

    if ($result_laboratorio->num_rows > 0) {
        $pdf->Ln(7);
        $pdf->SetXY(140, 36); // Establecer la posición X y Y para el siguiente elemento
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 7, utf8_decode($row['Ciclo']), 1, 0, 'C'); // Campo 'Ciclo'
    }

    //Tabla 2
    // Obtener los datos de la vista vista_horas_alumnos
    $sql_horas = "SELECT * FROM vista_horas_ciclo WHERE Ciclo LIKE '$ciclo'";
    $result_horas = mysqli_query($conexion, $sql_horas);
    $contadorId = 0;

    $sql_horas2 = "SELECT NOW() AS 'Hora del Servidor'";
    $result_horas2 = mysqli_query($conexion, $sql_horas2);
    $row2 = mysqli_fetch_assoc($result_horas2);

    if ($row2) {
        $pdf->SetXY(250, 10); // Establecer la posición X y Y para el siguiente elemento
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 7, utf8_decode($row2['Hora del Servidor']), 1, 0, 'C'); // Campo 'Hora del Servidor'
    }

    // Mostrar el logo
    $pdf->Image('img/logoUtec.jpg', 10, 7, 30); // Nombre del archivo de la imagen, posición X, posición Y, tamaño de la imagen

    // Colocar el nombre de la U al lado derecho del logo
    $pdf->SetFont('Arial', 'B', 15); // Cambiar el tamaño de la fuente a 12
    $pdf->SetXY(75, 10); // Establecer la posición X y Y para el siguiente elemento
    $pdf->Cell(160, 15, utf8_decode('Universidad Tecnológica de El Salvador'), 0, 1, 'C'); // AnchoCelda, AltoCelda, título, borde(1-0), saltoLinea(1-0), posicion(L-C-R), ColorFondo(1-0)
    $pdf->SetFont('Arial', 'B', 12); // Cambiar el tamaño de la fuente a 12
    $pdf->SetXY(75, 10); // Establecer la posición X y Y para el siguiente elemento
    $pdf->Cell(160, 30, utf8_decode('Sistema de Control de Servicio Social en los Laboratorios de Informática'), 0, 1, 'C');
    $pdf->SetFont('Arial', 'B', 12); // Cambiar el tamaño de la fuente a 12
    $pdf->SetXY(75, 10); // Establecer la posición X y Y para el siguiente elemento
    $pdf->Cell(160, 45, utf8_decode('Reporte por Ciclo'), 0, 1, 'C');
    
    $pdf->Ln(0); // Salto de línea
    $pdf->SetTextColor(103); //color

    $pdf->SetXY(93, 43); // Establecer la posición X y Y para el siguiente elemento
    $pdf->SetTextColor(0, 0, 0); //colorTexto
    $pdf->SetDrawColor(163, 163, 163); //colorBorde
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(5, 7, utf8_decode('ID'), 1, 0, 'C');
    $pdf->Cell(20, 7, utf8_decode('CARNET'), 1, 0, 'C');
    $pdf->Cell(70, 7, utf8_decode('NOMBRE'), 1, 0, 'C');
    $pdf->Cell(30, 7, utf8_decode('TOTAL HORAS'), 1, 0, 'C');
    $pdf->Ln(7);

    // Definir las coordenadas X e Y iniciales
    $posicionX = 93; // Posición horizontal inicial
    $posicionY = 50; // Posición vertical inicial

    // Establecer la posición inicial
    $pdf->SetXY($posicionX, $posicionY);

    // Definir el desplazamiento en X e Y
    $desplazamientoX = 5; // Desplazamiento horizontal
    $desplazamientoY = 7; // Desplazamiento vertical

    // Mostrar los datos en el PDF
    if ($result_horas->num_rows > 0) {
        $pdf->SetFont('Arial', 'B', 8);
        while ($row = $result_horas->fetch_assoc()) {
            $contadorId++;
            $pdf->SetXY($posicionX, $posicionY);
            $pdf->Cell(5, 7, utf8_decode($contadorId), 1, 0, 'C'); // Campo 'Id'
            $pdf->Cell(20, 7, utf8_decode($row['Carnet']), 1, 0, 'C'); // Campo 'Carnet'
            $pdf->Cell(70, 7, utf8_decode($row['Nombre']), 1, 0, 'C'); // Campo 'Nombre'
            $pdf->Cell(30, 7, utf8_decode($row['Total_Horas']), 1, 1, 'C'); // Campo 'Total de horas' con salto de línea
            // Actualizar la posición vertical para la próxima fila
            $posicionY += $desplazamientoY;
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
