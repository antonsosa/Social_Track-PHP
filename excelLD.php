<?php
// Incluir el archivo de conexión a la base de datos
include('conex.php');

// Incluir la biblioteca PhpSpreadsheet
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Crear un nuevo objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Establecer la cabecera de la universidad
$sheet->mergeCells('E4:H4');
$sheet->setCellValue('E4', 'Universidad Tecnológica de El Salvador');
$sheet->getStyle('E4')->getFont()->setBold(true)->setSize(15);
$sheet->getStyle('E4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Segunda fila con el nombre del sistema
$sheet->mergeCells('E5:H5');
$sheet->setCellValue('E5', 'Sistema de Control de Servicio Social en los Laboratorios de Informática');
$sheet->getStyle('E5')->getFont()->setBold(true)->setSize(12);
$sheet->getStyle('E5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Tercera fila con el tipo de reporte
$sheet->mergeCells('E6:H6');
$sheet->setCellValue('E6', 'Reporte Detallado por Laboratorios');
$sheet->getStyle('E6')->getFont()->setBold(true)->setSize(12);
$sheet->getStyle('E6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Mostrar el logo
$drawing = new Drawing();
$drawing->setName('Logo');
$drawing->setDescription('Logo de Utec');
$drawing->setPath('img/logoUtec.jpg');
$drawing->setCoordinates('A1');
$drawing->setWidth(290); // Establecer el ancho de la imagen
$drawing->setHeight(140); // Establecer la altura de la imagen
$drawing->setWorksheet($sheet);

// Espacio para el logo
$sheet->mergeCells('A4:A6');

// Obtener la hora del servidor
$sql_horas2 = "SELECT NOW() AS 'Hora del Servidor'";
$result_horas2 = mysqli_query($conexion, $sql_horas2);
$row2 = mysqli_fetch_assoc($result_horas2);

if ($row2) {
    // Establecer la hora del servidor en una celda específica
    $sheet->setCellValue('I4', $row2['Hora del Servidor']);
    $sheet->getStyle('I4:J4')->getFont()->setBold(true);
}
 // Aplicar bordes a todas las celdas de la tabla
 $sheet->getStyle('I4:J4')->applyFromArray([
     'borders' => [
         'allBorders' => [
             'borderStyle' => Border::BORDER_THIN,
             'color' => ['argb' => 'FF000000'],
         ],
     ],
 ]);

// Obtener datos de la base de datos
$ciclo = $_GET['ciclo'];
$busqueda = $_GET['busqueda'];
$sql_labs = "SELECT DISTINCT Ciclo, Laboratorio FROM vista_horas_alumnos WHERE Laboratorio = '$busqueda' AND Ciclo = '$ciclo'";
$result_labs = mysqli_query($conexion, $sql_labs);

// Tabla 1: Establecer encabezados de columna
$sheet->setCellValue('F8', 'Ciclo');
$sheet->setCellValue('G8', 'Laboratorio');

// Aplicar estilos a los encabezados
$headerStyle = [
    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FFFFFFFF'],
        ],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FF000000'],
    ],
];
$sheet->getStyle('F8:G8')->applyFromArray($headerStyle);

if($result_labs->num_rows > 0) {
    $rowIndex = 9;
    while($row = $result_labs->fetch_assoc()) {
        $sheet->setCellValue('F'.$rowIndex, $row['Ciclo']);
        $sheet->setCellValue('G'.$rowIndex, $row['Laboratorio']);
        $rowIndex++; 
    }
    // Aplicar bordes a todas las celdas de la tabla
    $dataRange = 'F8:G' . ($rowIndex - 1);
    $sheet->getStyle($dataRange)->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
    ]);
}


// Tabla 2: Establecer encabezados de columna
$sheet->setCellValue('C11', 'Id');
$sheet->setCellValue('D11', 'Carnet');
$sheet->setCellValue('E11', 'Nombre');
$sheet->setCellValue('F11', 'Entrada');
$sheet->setCellValue('G11', 'Salida');
$sheet->setCellValue('H11', 'Actividad');
$sheet->setCellValue('I11', 'Encargado');
$sheet->setCellValue('J11', 'Horas');

// Aplicar estilos a los encabezados de tabla 2
$sheet->getStyle('C11:J11')->applyFromArray($headerStyle);

$sql_horas2 = "SELECT * FROM vista_horas_alumnos WHERE Laboratorio = '$busqueda' AND Ciclo = '$ciclo'";
$result_horas2 = mysqli_query($conexion, $sql_horas2);

if($result_horas2->num_rows > 0) {
    $rowIndex = 12;
    $contador = 1;
    while($row = $result_horas2->fetch_assoc()) {
        $sheet->setCellValue('C'.$rowIndex, $contador);
        $sheet->setCellValue('D'.$rowIndex, $row['Carnet']);
        $sheet->setCellValue('E'.$rowIndex, $row['Nombre']);
        $sheet->setCellValue('F'.$rowIndex, $row['Entrada']);
        $sheet->setCellValue('G'.$rowIndex, $row['Salida']);
        $sheet->setCellValue('H'.$rowIndex, $row['Actividad']);
        $sheet->setCellValue('I'.$rowIndex, $row['Encargado']);
        $sheet->setCellValue('J'.$rowIndex, $row['Horas']);
        $rowIndex++; 
        $contador++;
    }
    // Aplicar bordes a todas las celdas de la tabla 2
    $dataRange2 = 'C11:J' . ($rowIndex - 1);
    $sheet->getStyle($dataRange2)->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
    ]);
}

// Ajustar anchos de columna para la tabla 2

$sheet->getColumnDimension('A')->setWidth(12);
$sheet->getColumnDimension('C')->setWidth(5);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(30);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(40);
$sheet->getColumnDimension('I')->setWidth(15);

// Crear un objeto Writer
$writer = new Xlsx($spreadsheet);

// Definir cabeceras para descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporteLaboratorioDetalle.xlsx"');
header('Cache-Control: max-age=0');

// Salida del archivo Excel al navegador
$writer->save('php://output');
?>
