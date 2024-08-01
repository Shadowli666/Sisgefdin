
<?php
include('configs.php');
$idRegistros = $_REQUEST['id'];
$pediatra    = $_REQUEST['pediatra'];
$diagnostico_medico    = $_REQUEST['diagnostico_medico'];
$peso    = $_REQUEST['peso'];
$talla    = $_REQUEST['talla'];
$temperatura   = $_REQUEST['temperatura'];




$update = ("UPDATE ninos
	SET 
        pediatra  ='" .$pediatra. "',
      diagnostico_medico  ='" .$diagnostico_medico. "',
      peso  ='" .$peso. "',
      talla  ='" .$talla. "',
      temperatura  ='" .$temperatura. "'
	 

WHERE idninos='" .$idRegistros. "'
");
$result_update = mysqli_query($con, $update);

echo "<script type='text/javascript'>
        window.location='niños.php';
    </script>";

?>
