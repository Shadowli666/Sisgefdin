
<?php
include('configs.php');
$idRegistros = $_REQUEST['id'];
$atencion     = $_REQUEST['atencion'];


$update = ("UPDATE vacunacion
	SET 
	atencion  ='" .$atencion. "'
	 

WHERE idvacuna='" .$idRegistros. "'
");
$result_update = mysqli_query($con, $update);

echo "<script type='text/javascript'>
        window.location='consulta_vacunacion.php';
    </script>";

?>
