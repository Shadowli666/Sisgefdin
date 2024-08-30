<?php
session_start();
require("../conexion.php");
$id_itinerario = $_POST['idcita']; // Se asegura de que el ID de la cita se pase desde el formulario

// Guardar los datos de Fórmula Leucocitaria en la sesión usando $idcita como clave
$_SESSION['leucocitos'][$id_itinerario] = [
    'seg' => $_POST['seg'],
    'linf' => $_POST['linf'],
    'eosin' => $_POST['eosin'],
    'monoc' => $_POST['monoc'],
    'basof' => $_POST['basof'],
    'otros' => $_POST['otros'],
    'total' => $_POST['total'],
];

// Redirigir de vuelta al formulario o mostrar un mensaje de éxito
header("Location: formulario.php?idcita=$idcita&exito=1");
exit();
?>