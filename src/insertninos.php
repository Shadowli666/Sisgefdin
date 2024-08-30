<?php

$connection = mysqli_connect("localhost","farmacia_test","");
$db = mysqli_select_db($connection, 'farmacia');

if(isset($_POST['insertdata']))
{
        
      $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $segun_nombre = $_POST['segun_nombre'];
        $apellido = $_POST['apellido'];
        $segun_apellido = $_POST['segun_apellido'];
        $edad = $_POST['edad'];
        $fecha_nac = $_POST['fecha_nac'];
        $genero = $_POST['genero'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $direccion = $_POST['direccion'];
        $parroquia = $_POST['parroquia'];
        $municipio = $_POST['municipio'];
        $estado = $_POST['estado'];

    $query = "INSERT INTO cliente (`cedula`,`nombre`,`segun_nombre`,`apellido`,`segun_apellido`,`edad`,`fecha_nac`,`genero`,`telefono`,`correo`,`direccion`,`parroquia`,`municipio`,`estado`) VALUES ('$cedula', '$nombre', '$segun_nombre', '$apellido', '$segun_apellido', '$edad','$fecha_nac','$genero', '$telefono','$correo', '$direccion','$parroquia','$municipio','$estado')";
    $query_run = mysqli_query($connection, $query);
    if($query_run)
    {
        echo '<script> alert("Data Saved"); </script>';
        header('Location: ventas.php');
    }
    else
    {
        echo '<script> alert("Data Not Saved"); </script>';
    }
}

?>