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
$pdf->SetFont('Arial', 'B', 15); // Cambiar el tamaño de la fuente a 15
$pdf->SetXY(68, 10); // Establecer la posición X y Y para el siguiente elemento
$pdf->Cell(160, 15, utf8_decode('Universidad Tecnológica de El Salvador'), 0, 1, 'C'); // AnchoCelda, AltoCelda, título, borde(1-0), saltoLinea(1-0), posicion(L-C-R), ColorFondo(1-0)

$pdf->SetFont('Arial', 'B', 12); // Cambiar el tamaño de la fuente a 12
$pdf->SetXY(68, 10); // Establecer la posición X y Y para el siguiente elemento
$pdf->Cell(160, 30, utf8_decode('Sistema de Control de Servicio Social en los Laboratorios de Informática'), 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 12); // Cambiar el tamaño de la fuente a 12
$pdf->SetXY(68, 10); // Establecer la posición X y Y para el siguiente elemento
$pdf->Cell(160, 45, utf8_decode('Reporte Detallado por Laboratorio'), 0, 1, 'C');

$pdf->Ln(10); // Salto de línea
$pdf->SetTextColor(103); //color

//hora del servidor
$sql_horas2 = "SELECT NOW() AS 'Hora del Servidor'";
$result_horas2 = mysqli_query($conexion, $sql_horas2);
$row2 = mysqli_fetch_assoc($result_horas2);

if ($row2) {
    $pdf->SetXY(250, 10); // Establecer la posición X y Y para el siguiente elemento
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 7, utf8_decode($row2['Hora del Servidor']), 1, 0, 'C');
}

$ciclo = $_GET['ciclo'];
$busqueda = $_GET['busqueda'];

// Tabla 1
// Obtener los datos de la vista vista_horas_alumnos
$sql_laboratorio = "SELECT * FROM vista_horas_alumnos WHERE Laboratorio = '$busqueda' AND Ciclo = '$ciclo'";
$result_laboratorio = mysqli_query($conexion, $sql_laboratorio);

if ($result_laboratorio->num_rows > 0) {
    $row = mysqli_fetch_assoc($result_laboratorio);
    $pdf->SetXY(118, 35); // Establecer la posición X y Y para el siguiente elemento
    $pdf->SetTextColor(0, 0, 0); //colorTexto
    $pdf->SetDrawColor(163, 163, 163); //colorBorde
    $pdf->SetFont('Arial', 'B', 8);

    $pdf->Ln(7);

    $pdf->SetXY(118, 42); // Establecer la posición X y Y para el siguiente elemento
    $pdf->Cell(30, 7, utf8_decode($row['Ciclo']), 1, 0, 'C'); // Campo 'Ciclo'
    $pdf->Cell(30, 7, utf8_decode($row['Laboratorio']), 1, 0, 'C'); // Campo 'Laboratorio'
}

// Tabla 2
// Obtener los datos de la vista vista_horas_alumnos
$sql_horas = "SELECT * FROM vista_horas_alumnos WHERE Laboratorio = '$busqueda' AND Ciclo = '$ciclo'";
$result_horas = mysqli_query($conexion, $sql_horas);
$contadorId = 0;

if ($result_horas->num_rows > 0) {
    // Mostrar los resultados en formato de tabla
    $pdf->SetXY(10, 49); // Establecer la posición X y Y para el siguiente elemento
    $pdf->SetTextColor(0, 0, 0); //colorTexto
    $pdf->SetDrawColor(163, 163, 163); //colorBorde
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(6, 7, utf8_decode('ID'), 1, 0, 'C');
    $pdf->Cell(20, 7, utf8_decode('CARNET'), 1, 0, 'C');
    $pdf->Cell(45, 7, utf8_decode('NOMBRE'), 1, 0, 'C');
    $pdf->Cell(28, 7, utf8_decode('ENTRADA'), 1, 0, 'C');
    $pdf->Cell(28, 7, utf8_decode('SALIDA'), 1, 0, 'C');
    $pdf->Cell(115, 7, utf8_decode('ACTIVIDAD'), 1, 0, 'C');
    $pdf->Cell(20, 7, utf8_decode('ENCARGADO'), 1, 0, 'C');
    $pdf->Cell(13, 7, utf8_decode('HORAS'), 1, 0, 'C');
    $pdf->Ln(7);

    $pdf->SetFont('Arial', '', 8);
    while ($row = $result_horas->fetch_assoc()) {
        $contadorId++;
        $pdf->Cell(6, 7, $contadorId, 1, 0, 'C'); // Campo 'Id'
        $pdf->Cell(20, 7, utf8_decode($row['Carnet']), 1, 0, 'C'); // Campo 'Carnet'
        $pdf->Cell(45, 7, utf8_decode($row['Nombre']), 1, 0, 'L'); // Campo 'Nombre'
        $pdf->Cell(28, 7, utf8_decode($row['Entrada']), 1, 0, 'C'); // Campo 'Entrada'
        $pdf->Cell(28, 7, utf8_decode($row['Salida']), 1, 0, 'C'); // Campo 'Salida'
        $pdf->Cell(115, 7, utf8_decode($row['Actividad']), 1, 0, 'L'); // Campo 'Actividad'
        $pdf->Cell(20, 7, utf8_decode($row['Encargado']), 1, 0, 'C'); // Campo 'Encargado'
        $pdf->Cell(13, 7, utf8_decode($row['Horas']), 1, 1, 'C'); // Campo 'Horas'
    }
} else {
    // Si no se encontraron resultados
    $pdf->SetXY(10, 50); // Establecer la posición X y Y para el siguiente elemento
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 10, utf8_decode('No se encontraron horas registradas.'), 0, 1, 'C');
}

// Pie de página
$pdf->SetY(-15); 
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, utf8_decode('Página '.$pdf->PageNo().' de {nb}'), 0, 0, 'C');

// Salida del PDF
$pdf->Output();
?>
