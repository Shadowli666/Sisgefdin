
<?php
include('configs.php');
$idRegistros = $_REQUEST['id'];
$atencion     = $_REQUEST['atencion'];


$update = ("UPDATE itinerario
	SET 
	atencion  ='" .$atencion. "'
	 

WHERE idcita='" .$idRegistros. "'
");
$result_update = mysqli_query($con, $update);

echo "<script type='text/javascript'>
        window.location='consulta_cita.php';
    </script>";

?>
