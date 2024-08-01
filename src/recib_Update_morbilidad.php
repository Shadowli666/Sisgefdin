
<?php
include('configs.php');
$idRegistros = $_REQUEST['id'];
$motivo_consulta    = $_REQUEST['motivo_consulta'];
$diagnostico   = $_REQUEST['diagnostico'];




$update = ("UPDATE itinerario
	SET 
        motivo_consulta  ='" .$motivo_consulta. "',
        diagnostico  ='" .$diagnostico. "'
      
	 

WHERE idcita='" .$idRegistros. "'
");
$result_update = mysqli_query($con, $update);

echo "<script type='text/javascript'>
        window.location='morbilidad.php';
    </script>";

?>
