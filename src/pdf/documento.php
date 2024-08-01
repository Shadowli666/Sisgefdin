<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
$pdf = new FPDF('P', 'mm', 'letter');
$pdf->AddPage();
$pdf->SetMargins(5, 0, 0);
$pdf->SetTitle("Salida");
$pdf->SetFont('Arial', 'B', 12);

// Verificar si se reciben los parámetros 'v' y 'cl' en la URL
$id = $_GET['v'];
$idcliente = $_GET['cl'];
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);

$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");

if ($clientes) {
    $datosC = mysqli_fetch_assoc($clientes);
} else {
    die("Error en la consulta de cliente: " . mysqli_error($conexion));
}

$pdf->Cell(190, 10, utf8_decode('LABORATORIO FUNDACION DIVINO NIÑO'), 0, 1, 'C', 0);
$pdf->image("../../assets/img/laboratorio.png", 10, 10, 30, 20, 'PNG');

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(190, 5, utf8_decode("Av. 44 con calle Vargas. Ciudad Ojeda. Estado Zulia. Venezuela"), 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(190, 5, utf8_decode("WhatsApp 0424-6218634 / Instagram @fundaciondivinonino "), 0, 1, 'C');
$pdf->SetFont('Arial', '', 7);
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(153, 152, 150);
$pdf->SetTextColor(255, 255, 255 );
$pdf->Cell(205, 5, "DATOS DEL PACIENTE", 0, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(20, 5, utf8_decode('Nombre y Apellido:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 5, utf8_decode($datosC['nombre']), 0, 0, 'C');
$pdf->Cell(5, 5, utf8_decode($datosC['apellido']), 0, 0, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(90, 5, utf8_decode('Edad'), 0, 0, 'R');
$pdf->Cell(20, 5, utf8_decode($datosC['edad']), 0, 0, 'R');
$pdf->Cell(20, 5, utf8_decode('AÑOS'), 0, 1, 'R');
$pdf->Ln();

// Hematología
$sql = "SELECT hemoglobina, hematocritos, plaquetas, cuentas_blancas, vsg FROM hematologia WHERE id_venta = $id";
$resultado = $conexion->query($sql);
if (!$resultado) {
    die("Error al ejecutar la consulta de hematología: " . $conexion->error);
}

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(255, 255, 255 );
    $pdf->Cell(205, 5, utf8_decode('HEMATOLOGIA'), 0, 1, 'C', 1);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, utf8_decode('HEMOGLOBINA: '), 0, 0, 'L');
    $pdf->Cell(30, 5, $fila['hemoglobina'], 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode('g%'), 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode('PLAQUETAS: '), 0, 0, 'R');
    $pdf->Cell(30, 5, $fila['plaquetas'], 0, 0, 'R');
    $pdf->Cell(30, 5, utf8_decode('xmm3'), 0, 1, 'R');
    $pdf->Cell(30, 5, utf8_decode('HEMATROCITOS: '), 0, 0, 'L');
    $pdf->Cell(30, 5, $fila['hematocritos'], 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode('%'), 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode('VSG: '), 0, 0, 'R');
    $pdf->Cell(30, 5, $fila['vsg'], 0, 0, 'R');
    $pdf->Cell(30, 5, utf8_decode('mm x 1 hora'), 0, 1, 'R');
    $pdf->Cell(30, 5, utf8_decode('CUENTA BLANCA: '), 0, 0, 'L');
    $pdf->Cell(30, 5, $fila['cuentas_blancas'], 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode('xmm3'), 0, 1, 'L');
}

// Fórmula Leucocitaria
$sql = "SELECT seg, linf, eosin, monoc, basof, otros, total FROM formula_leuco WHERE id_venta = $id";
$resultado = $conexion->query($sql);
if (!$resultado) {
    die("Error al ejecutar la consulta de fórmula leucocitaria: " . $conexion->error);
}

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(255, 255, 255 );
    $pdf->Cell(205, 5, utf8_decode('FORMULA LEUCOCITARIA'), 0, 1, 'C', 1);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(29.3, 5, 'SEG ', 0, 0, 'C');
    $pdf->Cell(29.3, 5, 'LINF ', 0, 0, 'C');
    $pdf->Cell(29.3, 5, 'EOSIN ', 0, 0, 'C');
    $pdf->Cell(29.3, 5, 'MONOC ', 0, 0, 'C');
    $pdf->Cell(29.3, 5, 'BASOF ', 0, 0, 'C');
    $pdf->Cell(29.3, 5, 'OTROS ', 0, 0, 'C');
    $pdf->Cell(29.3, 5, 'TOTAL ', 0, 0, 'C');
    $pdf->Ln();
    $pdf->Cell(29.3, 5, $fila['seg'], 0, 0, 'C');
    $pdf->Cell(29.3, 5, $fila['linf'], 0, 0, 'C');
    $pdf->Cell(29.3, 5, $fila['eosin'], 0, 0, 'C');
    $pdf->Cell(29.3, 5, $fila['monoc'], 0, 0, 'C');
    $pdf->Cell(29.3, 5, $fila['basof'], 0, 0, 'C');
    $pdf->Cell(29.3, 5, $fila['otros'], 0, 0, 'C');
    $pdf->Cell(29.3, 5, $fila['total'], 0, 0, 'C');
}
// Reticulocitos
$sql = "SELECT reticulocitos, valor_unidad, valor_referencial FROM reticulocitos WHERE id_venta = $id";
$resultado = $conexion->query($sql);
if (!$resultado) {
    die("Error al ejecutar la consulta de hematología: " . $conexion->error);
}

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(205, 5, utf8_decode('HEMATOLOGIA'), 0, 1, 'C', 1);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(35, 5, utf8_decode('RETICULOCITOS: '), 0, 0, 'C');
    $pdf->Cell(35, 5, $fila['valor_unidad'], 0, 0, 'C');
    $pdf->Cell(35, 5, utf8_decode('RESULTADO   %'), 0, 0, 'C');
    $pdf->Cell(35, 5, $fila['valor_referencial'], 0, 0, 'L');
}
// Perfil de Coagulación
$sql = "SELECT tp, tpt, inr, fibrinogeno FROM tiempos WHERE id_venta = $id";
$resultado = $conexion->query($sql);
if (!$resultado) {
    die("Error al ejecutar la consulta de Perfil de coagulacion: " . $conexion->error);
}

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $mostrarPDF = false;
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(255, 255, 255 );
    $pdf->Cell(205, 5, utf8_decode('PERFIL DE COAGULACION'), 0, 1, 'C', 1);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 5, utf8_decode('TIEMPO DE PROTROMBINA: '), 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode('CONTROL:'), 0, 0, 'R');
    $pdf->Cell(30, 5, utf8_decode('12 Seg'), 0, 0, 'R');
    $pdf->Cell(30, 5, utf8_decode('PACIENTE:'), 0, 0, 'R');
    $pdf->Cell(25, 5, $fila['tp'], 0, 0, 'R');
    $pdf->Cell(20, 5, utf8_decode('Seg'), 0, 1, 'L');
    $pdf->Cell(50, 5, utf8_decode('TIEMPO PARCIAL DE TROMBOPLASTINA: '), 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode('CONTROL:'), 0, 0, 'R');
    $pdf->Cell(30, 5, utf8_decode('28 Seg'), 0, 0, 'R');
    $pdf->Cell(30, 5, utf8_decode('PACIENTE:'), 0, 0, 'R');
    $pdf->Cell(25, 5, $fila['tpt'], 0, 0, 'R');
    $pdf->Cell(20, 5, utf8_decode('Seg'), 0, 1, 'L');
    if (!empty($fila['inr'])) {
    $pdf->Cell(50, 5, utf8_decode('INR: '), 0, 0, 'L');
    $pdf->Cell(30, 5, $fila['inr'], 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode('(VR: 1.4 - 2.5)'), 0, 1, 'L');
    $mostrarPDF = true;
}
if (!empty($fila['fibrinogeno'])) {
    $pdf->Cell(50, 5, utf8_decode('FIBRINOGENO: '), 0, 0, 'L');
    $pdf->Cell(30, 5, $fila['fibrinogeno'], 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode('(V.R: 200-400mg/dl %)'), 0, 1, 'R');
    $mostrarPDF = true;
}

   
}
// Examen de Grupo Sanguineo
$sql = "SELECT gruposanguineo, factor FROM gruposanguineo  WHERE id_venta = $id";
$resultado = $conexion->query($sql);
if (!$resultado) {
    die("Error al ejecutar la consulta de orina: " . $conexion->error);
}
if ($resultado->num_rows > 0) {
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(255, 255, 255 );
$pdf->Cell(205, 5, utf8_decode('GRUPO SANGUINEO'), 0, 1, 'C', 1);
$columnCount = 0; // Contador para las columnas
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 8);
while ($fila = mysqli_fetch_assoc($resultado)) {
    if ($columnCount % 2 == 0 && $columnCount != 0) {
        $pdf->Ln(); // Saltar a la siguiente línea después de dos columnas
    }
    $pdf->Cell(50, 5, utf8_decode('GRUPO SANGUINEO: '), 0, 0, 'C');
    $pdf->Cell(25.6, 5, utf8_decode($fila['gruposanguineo']), 0, 0, 'C');
    $pdf->Cell(50, 5, utf8_decode('FACTOR RH: '), 0, 0, 'C');
    $pdf->Cell(25.6, 5, utf8_decode($fila['factor']), 0, 0, 'C');
    $columnCount++;
}
}
// Examen de Quimica
$sql = "SELECT  r.*, q.id, q.examen, q.valor_referencial1, q.valor_referencial2 FROM resul_quimica r INNER JOIN quimica q ON r.id_examen = q.id  WHERE id_venta = $id";
$resultado = $conexion->query($sql);
if (!$resultado) {
    die("Error al ejecutar la consulta de orina: " . $conexion->error);
}
if ($resultado->num_rows > 0) {
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(255, 255, 255 );
$pdf->Cell(205, 5, utf8_decode('QUIMICA'), 0, 1, 'C', 1);
$columnCount = 0; // Contador para las columnas
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 8);
while ($fila = mysqli_fetch_assoc($resultado)) {
    if ($columnCount % 2 == 0 && $columnCount != 0) {
        $pdf->Ln(); // Saltar a la siguiente línea después de dos columnas
    }
    $pdf->Cell(25.6, 5, $fila['examen'], 0, 0, 'C');
    $pdf->Cell(25.6, 5, $fila['valor_unidad'], 0, 0, 'C');
    $pdf->Cell(25.6, 5, $fila['valor_referencial1'], 0, 0, 'C');
    $pdf->Cell(25.6, 5, $fila['valor_referencial2'], 0, 0, 'C');
    $columnCount++;
}
}
// Examen de Prueba Especial
$sql = "SELECT  r.*,  e.id, e.examen, e.valor_referencial, e.valor_referencial2 FROM pruebaespecial r INNER JOIN examenpruebaespecial e ON r.id_examen = e.id  WHERE id_venta = $id";
$resultado = $conexion->query($sql);
if (!$resultado) {
    die("Error al ejecutar la consulta de orina: " . $conexion->error);
}
if ($resultado->num_rows > 0) {
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(255, 255, 255 );
$pdf->Cell(205, 5, utf8_decode('PRUEBA ESPECIAL'), 0, 1, 'C', 1);
$columnCount = 0; // Contador para las columnas
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 8);
while ($fila = mysqli_fetch_assoc($resultado)) {
    if ($columnCount % 2 == 0 && $columnCount != 0) {
        $pdf->Ln(); // Saltar a la siguiente línea después de dos columnas
    }
    $pdf->Cell(25.6, 5, $fila['examen'], 0, 0, 'C');
    $pdf->Cell(25.6, 5, $fila['valor_unidad'], 0, 0, 'C');
    $pdf->Cell(25.6, 5, $fila['valor_referencial'], 0, 0, 'C');
    $pdf->Cell(25.6, 5, $fila['valor_referencial2'], 0, 0, 'C');
    $columnCount++;
}
}
// Examen de Orina
$sql = "SELECT aspecto, densidad, ph, olor, color, nitritos, proteinas, glucosa, cetonas, urobilinogeno, bilirrubina, hemoglobina, pigmen_biliares, sales_biliares FROM orina WHERE id_venta = $id";

$resultado = $conexion->query($sql);
if (!$resultado) {
    die("Error al ejecutar la consulta de orina: " . $conexion->error);
}

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(255, 255, 255 );
    $pdf->Cell(205, 5, "EXAMEN DE ORINA", 0, 1, 'C', 1);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, utf8_decode('ASPECTO: '), 0, 0, 'L');
    $pdf->Cell(30, 5, $fila['aspecto'], 0, 0, 'L');
    $pdf->Cell(90, 5, utf8_decode('CETONAS: '), 0, 0, 'R');
    $pdf->Cell(30, 5, $fila['cetonas'], 0, 1, 'L');
    $pdf->Cell(30, 5, utf8_decode('DENSIDAD: '), 0, 0, 'L');
    $pdf->Cell(30, 5, $fila['densidad'], 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode('PH: '), 0, 0, 'R');
    $pdf->Cell(10, 5, $fila['ph'], 0, 0, 'L');
    $pdf->Cell(60, 5, utf8_decode('UROBILINOGENO: '), 0, 0, 'R');
    $pdf->Cell(30, 5, $fila['urobilinogeno'], 0, 1, 'L');
    $pdf->Cell(30, 5, utf8_decode('COLOR: '), 0, 0, 'L');
    $pdf->Cell(30, 5, $fila['color'], 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode('OLOR: '), 0, 0, 'R');
    $pdf->Cell(10, 5, $fila['olor'], 0, 0, 'L');
    $pdf->Cell(53, 5, utf8_decode('BILIRUBINA: '), 0, 0, 'R');
    $pdf->Cell(30, 5, $fila['bilirrubina'], 0, 1, 'L');
    $pdf->Cell(30, 5, utf8_decode('NITRITOS: '), 0, 0, 'L');
    $pdf->Cell(30, 5, $fila['nitritos'], 0, 0, 'L');
    $pdf->Cell(97, 5, utf8_decode('HEMOGLOBINA: '), 0, 0, 'R');
    $pdf->Cell(30, 5, $fila['hemoglobina'], 0, 1, 'L');
    $pdf->Cell(30, 5, utf8_decode('PROTEINAS: '), 0, 0, 'L');
    $pdf->Cell(30, 5, $fila['proteinas'], 0, 0, 'L');
    $pdf->Cell(105, 5, utf8_decode('PIGMENTOS BILIARES: '), 0, 0, 'R');
    $pdf->Cell(30, 5, $fila['pigmen_biliares'], 0, 1, 'L');
    $pdf->Cell(30, 5, utf8_decode('GLUCOSA: '), 0, 0, 'L');
    $pdf->Cell(30, 5, $fila['glucosa'], 0, 0, 'L');
    $pdf->Cell(98, 5, utf8_decode('SALES BILIARES: '), 0, 0, 'R');
    $pdf->Cell(30, 5, $fila['sales_biliares'], 0, 1, 'L');
}

// Examen Microscopio
$sql = "SELECT celulas_ep_planas, bacterias, leucocitos, hematies, mucina, celulas_renales, cristales, otros FROM detalle_exm_misc_ori WHERE id_venta = $id";
$resultado = $conexion->query($sql);
if (!$resultado) {
    die("Error al ejecutar la consulta de examen de microscopio: " . $conexion->error);
}

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(255, 255, 255 );
    $pdf->Cell(205, 5, "EXAMEN MICROSCOPICO", 0, 1, 'C', 1);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, utf8_decode('CELULAS EP PLANAS: '), 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode($fila['celulas_ep_planas']), 0, 0, 'L');
    $pdf->Cell(90, 5, utf8_decode('MUCINA: '), 0, 0, 'R');
    $pdf->Cell(30, 5, utf8_decode($fila['mucina']), 0, 1, 'L');
    $pdf->Cell(30, 5, utf8_decode('BACTERIAS: '), 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode($fila['bacterias']), 0, 0, 'L');
    $pdf->Cell(90, 5, utf8_decode('CELULAS RENALES: '), 0, 0, 'R');
    $pdf->Cell(30, 5, utf8_decode($fila['celulas_renales']), 0, 1, 'L');
    $pdf->Cell(30, 5, utf8_decode('LEUCOCITOS: '), 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode($fila['leucocitos']), 0, 0, 'L');
    $pdf->Cell(90, 5, utf8_decode('CRISTALES: '), 0, 0, 'R');
    $pdf->Cell(30, 5, utf8_decode($fila['cristales']), 0, 1, 'L');
    $pdf->Cell(30, 5, utf8_decode('HEMATIES: '), 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode($fila['hematies']), 0, 0, 'L');
    $pdf->Cell(90, 5, utf8_decode('OTROS: '), 0, 0, 'R');
    $pdf->Cell(30, 5, utf8_decode($fila['otros']), 0, 1, 'L');
}
// Examen de Heces
$sql = "SELECT aspecto, color, olor, consistencia, Reaccion, moco, sangre, ra, ph, azucares FROM heces WHERE id_venta = $id";

$resultado = $conexion->query($sql);
if (!$resultado) {
    die("Error al ejecutar la consulta de orina: " . $conexion->error);
}

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(205, 5, "EXAMEN DE HECES", 0, 1, 'C', 1);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, utf8_decode('ASPECTO: '), 0, 0, 'L');
    $pdf->Cell(10, 5, $fila['aspecto'], 0, 0, 'C');
    $pdf->Cell(120, 5, utf8_decode('MOCO: '), 0, 0, 'R');
    $pdf->Cell(30, 5, $fila['moco'], 0, 1, 'C');
    $pdf->Cell(30, 5, utf8_decode('COLOR: '), 0, 0, 'L');
    $pdf->Cell(10, 5, $fila['color'], 0, 0, 'C');
    $pdf->Cell(120, 5, utf8_decode('SANGRE: '), 0, 0, 'R');
    $pdf->Cell(30, 5, $fila['sangre'], 0, 1, 'C');
    $pdf->Cell(30, 5, utf8_decode('OLOR: '), 0, 0, 'L');
    $pdf->Cell(10, 5, $fila['olor'], 0, 0, 'C');
    $pdf->Cell(120, 5, utf8_decode('R.A: '), 0, 0, 'R');
    $pdf->Cell(30, 5, $fila['ra'], 0, 1, 'C');
    $pdf->Cell(30, 5, utf8_decode('CONSISTENCIA: '), 0, 0, 'L');
    $pdf->Cell(10, 5, $fila['consistencia'], 0, 0, 'C');
    $pdf->Cell(120, 5, utf8_decode('PH: '), 0, 0, 'R');
    $pdf->Cell(30, 5, $fila['ph'], 0, 1, 'C');
    $pdf->Cell(30, 5, utf8_decode('REACCION: '), 0, 0, 'L');
    $pdf->Cell(10, 5, $fila['Reaccion'], 0, 0, 'C');
    $pdf->Cell(120, 5, utf8_decode('AZUCARES: '), 0, 0, 'R');
    $pdf->Cell(30, 5, $fila['azucares'], 0, 1, 'C');


    
}
// Examen de Observacion Microscopico
$sql = "SELECT protozoarios, helmintos, otros FROM heces_misc WHERE id_venta = $id";

$resultado = $conexion->query($sql);
if (!$resultado) {
    die("Error al ejecutar la consulta de orina: " . $conexion->error);
}

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(205, 5, "OBSERVACION MICROSCOPICA", 0, 1, 'C', 1);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, utf8_decode('PROTOZOARIOS: '), 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode($fila['protozoarios']), 0, 1, 'L');
    $pdf->Cell(30, 5, utf8_decode('HELMINTOS: '), 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode($fila['helmintos']), 0, 1, 'L');
    $pdf->Cell(30, 5, utf8_decode('OTROS: '), 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode($fila['otros']), 0, 0, 'L');
   

    
}
// Examen de Inmunoserologia
$sql = "SELECT  r.*,  e.id, e.examen, e.valor_referencial FROM inmunoserologia r INNER JOIN exameninmunoserologia e ON r.id_examen = e.id  WHERE id_venta = $id";
$resultado = $conexion->query($sql);
if (!$resultado) {
    die("Error al ejecutar la consulta de orina: " . $conexion->error);
}
if ($resultado->num_rows > 0) {
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(255, 255, 255 );
$pdf->Cell(205, 5, utf8_decode('INMUNOSEROLOGIA'), 0, 1, 'C', 1);
$columnCount = 0; // Contador para las columnas
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 8);
while ($fila = mysqli_fetch_assoc($resultado)) {
    if ($columnCount % 2 == 0 && $columnCount != 0) {
        $pdf->Ln(); // Saltar a la siguiente línea después de dos columnas
    }
    $pdf->Cell(25.6, 5, utf8_decode($fila['examen']), 0, 0, 'L');
    $pdf->Cell(25.6, 5, utf8_decode($fila['valor_unidad']), 0, 0, 'C');
    $pdf->Cell(25.6, 5, utf8_decode($fila['valor_referencial']), 0, 1, 'C');
    $columnCount++;
}
}




$pdf->Output();
?>
