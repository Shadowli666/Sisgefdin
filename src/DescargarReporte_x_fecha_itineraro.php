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

$sqlTrabajadores = ("SELECT * FROM itinerario  WHERE (fecha>='$fechaInit' and fecha<='$fechaFin') ORDER BY fecha ASC");
$query = mysqli_query($con, $sqlTrabajadores);
?>


<table style="text-align: center;" border='1' cellpadding=1 cellspacing=1>
<thead>
    <tr style="background: #D0CDCD;">
    <th>#</th>
    <th class="color">Nombre</th>
    <th class="color">Apellido</th>
    <th class="color">Doctor</th>
    <th class="color">Espe.</th>
    <th class="color">Servicio</th>
    <th class="color">Pago</th>
	<th class="color">Fecha de Cit<th>
    </tr>
	<th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido</th>
                    <th scope="col">Doctor</th>
					<th scope="col">Espe.</th>
					<th scope="col">Servicio</th>
					<th scope="col">Pago</th>
                    <th scope="col">Fecha de Cit</th>
					</th>
                            
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
										<td><?php echo $row['doctor']; ?></td>
										<td><?php echo $row['especialidad']; ?></td>
										<td><?php echo $row['descripcion']; ?></td>
										<td><?php echo $row['precios']; ?></td>
                                        <td><?php echo $row['fecha']; ?></td>
							
                </tr>
                

<?php } ?>
</table>

</body>
</html>