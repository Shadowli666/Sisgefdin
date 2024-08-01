<?php
/**conexion a BD */
$usuario  = "root";
$password = "1234";
$servidor = "localhost";
$basededatos = "farmacia";
$con = mysqli_connect($servidor, $usuario, $password) or die("No se ha podido conectar al Servidor");
mysqli_query($con,"SET SESSION collation_connection ='utf8_unicode_ci'");
$db = mysqli_select_db($con, $basededatos) or die("Upps! Error en conectar a la Base de Datos");


?>