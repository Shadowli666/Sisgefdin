<?php
include('configs.php');
$idRegistros = $_REQUEST['id'];
$atencion     = $_REQUEST['atencion'];
$doctor     = $_REQUEST['doctor'];
$descripcion     = $_REQUEST['descripcion'];
$precios     = $_REQUEST['precios'];
$observacion    = $_REQUEST['observacion'];

$update = ("UPDATE itinerario
	SET 
	atencion  ='" .$atencion. "',
	especialista  ='" .$doctor. "',
	descripcion  ='" .$descripcion. "',
	precios  ='" .$precios. "',
	observacion  ='" .$observacion. "'
	 

WHERE idcita='" .$idRegistros. "'
");
$result_update = mysqli_query($con, $update);

echo "<script type='text/javascript'>
        window.location='itinerario.php';
    </script>";

?>