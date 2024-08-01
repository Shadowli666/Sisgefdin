
<?php
include('configs.php');
$idRegistros = $_REQUEST['id'];
$atencion     = $_REQUEST['atencion'];
$ced_cliente    = $_REQUEST['id_cliente'];

$update = ("SELECT i.*, i.idcita, c.idcliente, c.cedula,c.nombre, c.apellido, c.telefono, c.edad FROM itinerario i INNER JOIN cliente c on i.id_cliente = c.idcliente 

	 

WHERE i.id_cliente='" .$idRegistros. "'
");
$result_update = mysqli_query($con, $update);

echo "<script type='text/javascript'>
        window.location='consulta_cita.php';
    </script>";

?>
