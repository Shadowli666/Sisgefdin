
<?php
include('configs.php');
$idRegistros = $_REQUEST['id'];
$atencion     = $_REQUEST['atencion'];


$update = ("UPDATE ninos
	SET 
	atencion  ='" .$atencion. "'
	 

WHERE idninos='" .$idRegistros. "'
");
$result_update = mysqli_query($con, $update);

echo "<script type='text/javascript'>
        window.location='consulta_ninos.php';
    </script>";

?>
