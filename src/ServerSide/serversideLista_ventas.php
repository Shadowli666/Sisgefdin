<?php
require 'serverside.php';
$table_data->get('vista_ventas','id',array( 'id','id_cliente', 'cedula', 'nombre','apellido','total','totalbs' ,'taza' ,'fecha'));