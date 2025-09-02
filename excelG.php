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
    $sheet->setCellValue('B6', 'Reporte por General');
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
        $sheet->getStyle('G4')->getFont()->setBold(true);
    }
     // Aplicar bordes a todas las celdas de la tabla
     $sheet->getStyle('G4')->applyFromArray([
         'borders' => [
             'allBorders' => [
                 'borderStyle' => Border::BORDER_THIN,
                 'color' => ['argb' => 'FF000000'],
             ],
         ],
         'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
     ]);


    //TABLA 1
    // Establecer encabezados de columna 
    $sheet->setCellValue('C8', 'Carnet');
    $sheet->setCellValue('D8', 'Nombre');
    $sheet->setCellValue('E8', 'Ciclo');

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
    $busqueda = $_GET['busqueda'];
    $sql_horas = "SELECT * FROM vista_alumnos WHERE Carnet = '$busqueda'";
    $result_horas = mysqli_query($conexion, $sql_horas);
    $contador = 1;
    if($result_horas->num_rows > 0) {
        $rowIndex = 9;
        while($row = $result_horas->fetch_assoc()) {
            $sheet->setCellValue('C'.$rowIndex, $row['Carnet']);
            $sheet->setCellValue('D'.$rowIndex, $row['Nombre']);
            $sheet->setCellValue('E'.$rowIndex, $row['Carrera']);
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
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
    }

    // Ajustar anchos de columna
    $sheet->getColumnDimension('A')->setWidth(21);
    $sheet->getColumnDimension('B')->setWidth(5);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(34);
    $sheet->getColumnDimension('E')->setWidth(50);
    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->getColumnDimension('G')->setWidth(20);

    //TABLA 2
    // Establecer encabezados de columna tabla 2
    $sheet->setCellValue('F8', 'Total General');

    // Obtener datos de la base de datos para la tabla 2
    $busqueda = $_GET['busqueda'];
    $sql_horas2 = "SELECT Carnet, Total_General FROM vista_horas_final WHERE Carnet = '$busqueda' ORDER BY Carnet LIMIT 1";
    $result_horas2 = mysqli_query($conexion, $sql_horas2);
    if($result_horas2->num_rows > 0) {
        $rowIndex = 9;
        while($row = $result_horas2->fetch_assoc()) {
            $sheet->setCellValue('F'.$rowIndex, $row['Total_General']);
            $rowIndex++; 
        }
    }

    //TABLA 3
    // Establecer encabezados de columna tabla 3
    $sheet->setCellValue('C11', 'Id');
    $sheet->setCellValue('D11', 'Ciclo');
    $sheet->setCellValue('E11', 'Total de horas');

    // Obtener datos de la base de datos para la tabla 3
    $sql_horas3 = "SELECT Ciclo, Total_Horas FROM vista_horas_final WHERE Carnet = '$busqueda'";
    $result_horas3 = mysqli_query($conexion, $sql_horas3);
    if($result_horas3->num_rows > 0) {
        $rowIndex = 12;
        $contador = 1;
        while($row = $result_horas3->fetch_assoc()) {
            $sheet->setCellValue('C'.$rowIndex, $contador);
            $sheet->setCellValue('D'.$rowIndex, $row['Ciclo']);
            $sheet->setCellValue('E'.$rowIndex, $row['Total_Horas']);
            $rowIndex++; 
            $contador++;
        }

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
        $sheet->getStyle('C11:E11')->applyFromArray($headerStyle2);

        // Aplicar bordes a todas las celdas de la tabla 2
        $dataRange2 = 'C11:E' . ($rowIndex - 1);
        $sheet->getStyle($dataRange2)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

    }

    // Ajustar anchos de columna para la tabla 2
    $sheet->getColumnDimension('B')->setWidth(5);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(34);
    $sheet->getColumnDimension('E')->setWidth(50);
    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->getColumnDimension('G')->setWidth(20);
 


    // Crear un objeto Writer
    $writer = new Xlsx($spreadsheet);

    // Definir cabeceras para descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporteGeneral.xlsx"');
    header('Cache-Control: max-age=0');

    // Salida del archivo Excel al navegador
    $writer->save('php://output');
?>
