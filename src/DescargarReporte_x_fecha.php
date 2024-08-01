<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> <!--Importante--->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descargar</title>
    <style>
    .color{
        background-color: #9BB;  
    }
</style>
</head>
<body>
    
<?php
include('configs.php');
$fecha = date("d_m_Y");


/**PARA FORZAR LA DESCARGA DEL EXCEL */
header("Content-Type: text/html;charset=utf-8");
header("Content-Type: application/vnd.ms-excel charset=iso-8859-1");
$filename = "ReporteExcel_" .$fecha. ".xls";
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=" . $filename . "");


/***RECIBIENDO LAS VARIABLE DE LA FECHA */
$fechaInit = date("Y-m-d", strtotime($_POST['fecha_ingreso']));
$fechaFin  = date("Y-m-d", strtotime($_POST['fechaFin']));


/*
$sqlTrabajadores = ("select * from trabajadores where (fecha_ingreso>='$fechaInit' and fecha_ingreso<='$fechaFin') order by fecha_ingreso desc");
$sqlTrabajadores = ("SELECT * FROM trabajadores WHERE fecha_ingreso BETWEEN '$fechaInit' AND '$fechaFin' order by fecha_ingreso desc
$sqlTrabajadores = ("SELECT * FROM `trabajadores` WHERE fecha_ingreso BETWEEN '$fechaInit' AND '$fechaFin'
$sqlTrabajadores = ("select * from trabajadores where fecha_ingreso >= '$fechaInit' and fecha_ingreso < '$fechaFin';
$sqlTrabajadores = ("SELECT * FROM trabajadores WHERE fecha_ingreso BETWEEN '$fechaInit' AND '$fecha2' ORDER BY fecha_ingreso DESC
*/                       

$sqlTrabajadores = ("SELECT d.*, c.idcliente, c.nombre, c.apellido FROM detalle_pago d INNER JOIN cliente c ON d.id_cliente = c.idcliente WHERE (fecha>='$fechaInit' and fecha<='$fechaFin') ORDER BY fecha ASC");
$query = mysqli_query($con, $sqlTrabajadores);
?>


<table style="text-align: center;" border='1' cellpadding=1 cellspacing=1>
<thead>
    <tr style="background: #D0CDCD;">
    <th>#</th>
    <th class="color">Nombre</th>
    <th class="color">Apellido</th>
    <th class="color">Metodo de Pago</th>
    <th class="color">Referencia</th>
    <th class="color">Bolivares</th>
    <th class="color">Dolares</th>>
    <th class="color">Concepto</th>
	<th class="color">Fecha<th>
    </tr>
	<th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido</th>
                    <th scope="col">Metodo de Pago</th>
                    <th scope="col">Referencia</th>
                    <th scope="col">Bolivares</th>
					<th scope="col">Dolares</th>
					<th scope="col">Concepto</th>
                    <th scope="col">Fecha</th>
        </tr>
    </thead>
    <?php
    $i = 1;
    while ($row = mysqli_fetch_array($query)) { ?>
        <tbody>
                  <tr>
                            <td><?php echo $i++; ?></td>
							<td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['apellido']; ?></td>
                            <td><?php echo $row['metodo_pago']; ?></td>
                            <td><?php echo $row['referencia']; ?></td>
							<td><?php echo $row['bolivares']; ?></td>
							<td><?php echo $row['dolares']; ?></td>
							<td><?php echo $row['obser']; ?></td>
							<td><?php echo $row['fecha']; ?></td>
							
                </tr>
                

<?php } ?>
</table>


</body>
</html>