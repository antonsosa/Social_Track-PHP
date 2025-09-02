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
    $sheet->mergeCells('B4:F4');
    $sheet->setCellValue('B4', 'Universidad Tecnológica de El Salvador');
    $sheet->getStyle('B4')->getFont()->setBold(true)->setSize(15);
    $sheet->getStyle('B4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Segunda fila con el nombre del sistema
    $sheet->mergeCells('B5:F5');
    $sheet->setCellValue('B5', 'Sistema de Control de Servicio Social en los Laboratorios de Informática');
    $sheet->getStyle('B5')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('B5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Tercera fila con el tipo de reporte
    $sheet->mergeCells('B6:F6');
    $sheet->setCellValue('B6', 'Reporte por Alumno');
    $sheet->getStyle('B6')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('B6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

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
        $sheet->setCellValue('G4', $row2['Hora del Servidor']);
        $sheet->getStyle('G4:H4')->getFont()->setBold(true);
    }
     // Aplicar bordes a todas las celdas de la tabla
     $sheet->getStyle('G4:H4')->applyFromArray([
         'borders' => [
             'allBorders' => [
                 'borderStyle' => Border::BORDER_THIN,
                 'color' => ['argb' => 'FF000000'],
             ],
         ],
     ]);


    //TABLA 1
    // Establecer encabezados de columna 
    $sheet->setCellValue('C8', 'Carnet');
    $sheet->setCellValue('D8', 'Nombre');
    $sheet->setCellValue('E8', 'Ciclo');
    $sheet->setCellValue('F8', 'Total de Horas');

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
    $sheet->getStyle('C8:F8')->applyFromArray($headerStyle);

    // Obtener datos de la base de datos
    $ciclo = $_GET['ciclo'];
    $busqueda = $_GET['busqueda'];
    $sql_horas = "SELECT * FROM vista_horas_ciclo WHERE Carnet = '$busqueda' AND Ciclo = '$ciclo'";
    $result_horas = mysqli_query($conexion, $sql_horas);
    $contador = 1;
    if($result_horas->num_rows > 0) {
        $rowIndex = 9;
        while($row = $result_horas->fetch_assoc()) {
            $sheet->setCellValue('C'.$rowIndex, $row['Carnet']);
            $sheet->setCellValue('D'.$rowIndex, $row['Nombre']);
            $sheet->setCellValue('E'.$rowIndex, $row['Ciclo']);
            $sheet->setCellValue('F'.$rowIndex, $row['Total_Horas']);
            $rowIndex++; 
            $contador++;
        }

        // Aplicar bordes a todas las celdas de la tabla
        $dataRange = 'C8:F' . ($rowIndex - 1);
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);
    }

    // Ajustar anchos de columna
    $sheet->getColumnDimension('A')->setWidth(21);
    $sheet->getColumnDimension('B')->setWidth(5);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(25);
    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->getColumnDimension('G')->setWidth(15);

    //TABLA 2
    // Establecer encabezados de columna tabla 2
    $sheet->setCellValue('B11', 'Id');
    $sheet->setCellValue('C11', 'Entrada');
    $sheet->setCellValue('D11', 'Salida');
    $sheet->setCellValue('E11', 'Actividad');
    $sheet->setCellValue('F11', 'Laboratorio');
    $sheet->setCellValue('G11', 'Encargado');
    $sheet->setCellValue('H11', 'Horas');

    // Aplicar estilos a los encabezados de tabla 2
    $headerStyle2 = [
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
    $sheet->getStyle('B11:H11')->applyFromArray($headerStyle2);

    // Obtener datos de la base de datos para la tabla 2
    $ciclo = $_GET['ciclo'];
    $busqueda = $_GET['busqueda'];
    $sql_horas2 = "SELECT Entrada, Salida, Actividad, Laboratorio, Encargado, `Horas` FROM vista_horas_alumnos WHERE Carnet = '$busqueda' AND Ciclo = '$ciclo'";
    $result_horas2 = mysqli_query($conexion, $sql_horas2);
    $contador = 1;
    if($result_horas2->num_rows > 0) {
        $rowIndex = 12;
        while($row = $result_horas2->fetch_assoc()) {
            $sheet->setCellValue('B'.$rowIndex, $contador);
            $sheet->setCellValue('C'.$rowIndex, $row['Entrada']);
            $sheet->setCellValue('D'.$rowIndex, $row['Salida']);
            $sheet->setCellValue('E'.$rowIndex, $row['Actividad']);
            $sheet->setCellValue('F'.$rowIndex, $row['Laboratorio']);
            $sheet->setCellValue('G'.$rowIndex, $row['Encargado']);
            $sheet->setCellValue('H'.$rowIndex, $row['Horas']);
            $rowIndex++; 
            $contador++;
        }

        // Aplicar bordes a todas las celdas de la tabla 2
        $dataRange2 = 'B11:H' . ($rowIndex - 1);
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
    $sheet->getColumnDimension('B')->setWidth(5);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(25);
    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->getColumnDimension('G')->setWidth(15);
    $sheet->getColumnDimension('H')->setWidth(10);
 


    // Crear un objeto Writer
    $writer = new Xlsx($spreadsheet);

    // Definir cabeceras para descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporteAlumno.xlsx"');
    header('Cache-Control: max-age=0');

    // Salida del archivo Excel al navegador
    $writer->save('php://output');
?>
