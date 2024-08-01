<?php
require 'serverside.php';
$table_data->get('cliente','idcliente',array( 'idcliente', 'cedula', 'nombre','segun_nombre','apellido','segun_apellido','edad','telefono','direccion', 'parroquia', 'municipio', 'estado'));

?>
 