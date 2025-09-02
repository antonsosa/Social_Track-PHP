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
    $sheet->setCellValue('B6', 'Reporte por Ciclo');
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

    // Establecer encabezados de columna
    $sheet->setCellValue('B8', 'Id');
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
    $sheet->getStyle('B8:F8')->applyFromArray($headerStyle);

    $ciclo = $_GET['ciclo'];
    // Obtener datos de la base de datos
    $sql_horas = "SELECT * FROM vista_horas_ciclo WHERE Ciclo = '$ciclo'";
    $result_horas = mysqli_query($conexion, $sql_horas);
    $contador = 1;
    if($result_horas->num_rows > 0) {
        $rowIndex = 9;
        while($row = $result_horas->fetch_assoc()) {
            $sheet->setCellValue('B'.$rowIndex, $contador);
            $sheet->setCellValue('C'.$rowIndex, $row['Carnet']);
            $sheet->setCellValue('D'.$rowIndex, $row['Nombre']);
            $sheet->setCellValue('E'.$rowIndex, $row['Ciclo']);
            $sheet->setCellValue('F'.$rowIndex, $row['Total_Horas']);
            $rowIndex++; 
            $contador++;
        }

        // Aplicar bordes a todas las celdas de la tabla
        $dataRange = 'B8:F' . ($rowIndex - 1);
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
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(25);
    $sheet->getColumnDimension('E')->setWidth(20);
    $sheet->getColumnDimension('F')->setWidth(15);

    // Crear un objeto Writer
    $writer = new Xlsx($spreadsheet);

    // Definir cabeceras para descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporteCiclo.xlsx"');
    header('Cache-Control: max-age=0');

    // Salida del archivo Excel al navegador
    $writer->save('php://output');
?>
