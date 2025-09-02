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

    // Obtener los datos de la vista vista_infolab
    $sql_lab = "SELECT * FROM vista_infolab";
    $result_lab = mysqli_query($conexion, $sql_lab);
    $contadorId = 0;

    $sql_lab2 = "SELECT NOW() AS 'Hora del Servidor'";
    $result_lab2 = mysqli_query($conexion, $sql_lab2);
    $row2 = mysqli_fetch_assoc($result_lab2);

    if ($row2) {
        $pdf->SetXY(250, 10); // Establecer la posición X y Y para el siguiente elemento
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 7, $row2['Hora del Servidor'], 1, 0, 'C'); // Campo 'Hora del Servidor'
    }

    // Mostrar el logo
    $pdf->Image('img/logoUtec.jpg', 10, 5, 30); // Nombre del archivo de la imagen, posición X, posición Y, tamaño de la imagen

    // Colocar el nombre de la empresa al lado derecho del logo
    $pdf->SetFont('Arial', 'B', 15); // Cambiar el tamaño de la fuente a 15
    $pdf->SetXY(68, 10); // Establecer la posición X y Y para el siguiente elemento
    $pdf->Cell(160, 15, utf8_decode('Universidad Tecnológica de El Salvador'), 0, 1, 'C'); // AnchoCelda, AltoCelda, título, borde(1-0), saltoLinea(1-0), posicion(L-C-R), ColorFondo(1-0)
    $pdf->SetFont('Arial', 'B', 12); // Cambiar el tamaño de la fuente a 12
    $pdf->SetXY(68, 10); // Establecer la posición X y Y para el siguiente elemento
    $pdf->Cell(160, 30, utf8_decode('Sistema de Control de Servicio Social en los Laboratorios de Informática'), 0, 1, 'C');
    $pdf->SetFont('Arial', 'B', 12); // Cambiar el tamaño de la fuente a 12
    $pdf->SetXY(68, 10); // Establecer la posición X y Y para el siguiente elemento
    $pdf->Cell(160, 45, utf8_decode('Información de Laboratorios'), 0, 1, 'C');
    
    $pdf->Ln(0); // Salto de línea
    $pdf->SetTextColor(103); //color

    /* CAMPOS DE LA TABLA */
    $pdf->SetXY(63, 40); // Establecer la posición X y Y para el siguiente elemento
    $pdf->SetTextColor(0, 0, 0); //colorTexto
    $pdf->SetDrawColor(163, 163, 163); //colorBorde
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(5, 7, utf8_decode('N°'), 1, 0, 'C');
    $pdf->Cell(70, 7, utf8_decode('Nombre laboratorio'), 1, 0, 'C');
    $pdf->Cell(25, 7, utf8_decode('Teléfono'), 1, 0, 'C');
    $pdf->Cell(70, 7, utf8_decode('Ubicación'), 1, 0, 'C');
    $pdf->Ln(7);

    // Definir las coordenadas X e Y iniciales
    $posicionX = 63; // Posición horizontal inicial
    $posicionY = 47; // Posición vertical inicial

    // Establecer la posición inicial
    $pdf->SetXY($posicionX, $posicionY);

    // Definir el desplazamiento en X e Y
    $desplazamientoX = 5; // Desplazamiento horizontal
    $desplazamientoY = 7; // Desplazamiento vertical

    // Mostrar los datos en el PDF
    if ($result_lab->num_rows > 0) {
        $pdf->SetFont('Arial', 'B', 8);
        while ($row = $result_lab->fetch_assoc()) {
            $contadorId++;
            $pdf->SetXY($posicionX, $posicionY);
            $pdf->Cell(5, 7, $contadorId, 1, 0, 'C'); // Campo 'Id'
            $pdf->Cell(70, 7, utf8_decode($row['Denominacion_laboratorio']), 1, 0, 'C'); // Campo 'Denominación'
            $pdf->Cell(25, 7, $row['telefono_laboratorio'], 1, 0, 'C'); // Campo 'Teléfono'
            $pdf->Cell(70, 7, utf8_decode($row['ubicacion_laboratorio']), 1, 1, 'C'); // Campo 'Ubicación' con salto de línea

            // Actualizar la posición vertical para la próxima fila
            $posicionY += $desplazamientoY;
        }
    }

    // Pie de página
    $posicionPiePagina = $pdf->GetY();
    $pdf->SetY($posicionPiePagina); 
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Cell(0, 10, 'Pagina '.$pdf->PageNo().' de {nb}', 0, 0, 'C');

    // Salida del PDF
    $pdf->Output();
?>
