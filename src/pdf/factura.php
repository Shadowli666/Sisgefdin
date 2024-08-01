<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
$pdf = new FPDF('P', 'mm', array(110, 130));
$pdf->AddPage();
$pdf->SetMargins(5, 0, 0);
$pdf->SetTitle("Salida");
$pdf->SetFont('Arial', 'B', 12);
$id = $_GET['v'];
$idcliente = $_GET['cl'];
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
$datosC = mysqli_fetch_assoc($clientes);
$ventas = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_venta = $id");
$pdf->Cell(85, 10, utf8_decode($datos['nombre']), 0, 1, 'C');
$pdf->image("../../assets/img/logito.png", 70, 20, 30, 15, 'PNG');
$pdf->image("../../assets/img/qr-code.png", 80, 100, 20, 20, 'PNG');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 5, $datos['telefono'], 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 5, utf8_decode("Dirección: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 5, utf8_decode($datos['direccion']), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 5, "Correo: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 5, utf8_decode($datos['email']), 0, 1, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(100, 5, "Datos del cliente", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(20, 5, utf8_decode('Nombre'), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('Apellido'), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('Cedula'), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('Teléfono'), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('Dirección'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(20, 5, utf8_decode($datosC['nombre']), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode($datosC['apellido']), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode($datosC['cedula']), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode($datosC['telefono']), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode($datosC['direccion']), 0, 1, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(100, 5, "Detalle del servicio", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(20, 5, utf8_decode('Descripción'), 0, 0, 'L');
$pdf->Cell(10, 5, 'Cantidad', 0, 1, 'C');
$pdf->SetFont('Arial', '', 7);
$total = 0.00;
$desc = 0.00;
while ($row = mysqli_fetch_assoc($ventas)) {
    $pdf->Cell(20, 5, $row['descripcion'], 0, 0, 'L');
    $pdf->Cell(10, 5, $row['precio'], 0, 1, 'L');
    $sub_total = $row['total'];
    $total = $total + $sub_total;
    $desc = $desc + $row['descuento'];
  
}

$pdf->Output("Factura.pdf", "I");

?>