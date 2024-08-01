<?php
// Asegúrate de incluir la autoloader de PhpSpreadsheet
require '../assets/PhpSpreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Establecer encabezados de las columnas
$sheet->setCellValue('A1', '#');
$sheet->setCellValue('B1', 'Codigo');
$sheet->setCellValue('C1', 'Producto');
$sheet->setCellValue('D1', 'Principio Activo');
$sheet->setCellValue('E1', 'Presentacion');
$sheet->setCellValue('F1', 'Stock');
$sheet->setCellValue('G1', 'Especialidad');

include "../conexion.php";
$query = mysqli_query($conexion, "SELECT * FROM producto");
$result = mysqli_num_rows($query);

if ($result > 0) {
    $row = 2; // Comienza desde la fila 2 porque la 1 es para los encabezados
    while ($data = mysqli_fetch_assoc($query)) {
        $sheet->setCellValue('A' . $row, $data['codproducto']);
        $sheet->setCellValue('B' . $row, $data['codigo']);
        $sheet->setCellValue('C' . $row, $data['descripcion']);
        $sheet->setCellValue('D' . $row, $data['id_tipo']);
        $sheet->setCellValue('E' . $row, $data['id_presentacion']);
        $sheet->setCellValue('F' . $row, $data['existencia']);
        $sheet->setCellValue('G' . $row, $data['espe']);
        $row++;
    }
}

// Configuración de las cabeceras para la descarga del archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Control_de_los_Medicamentos.xlsx"');
header('Cache-Control: max-age=0');

// Escribir el archivo en formato XLSX
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>