<?php
session_start();
require("../conexion.php");
$id_itinerario = $_POST['idcita']; // Se asegura de que el ID de la cita se pase desde el formulario

// Guardar los datos de Hematología en la sesión usando $idcita como clave
$_SESSION['hematologia'][$id_itinerario] = [
    'hemoglobina' => $_POST['hemoglobina'],
    'hematocritos' => $_POST['hematocritos'],
    'cuentas_blancas' => $_POST['cuentas_blancas'],
    'plaquetas' => $_POST['plaquetas'],
    'vsg' => $_POST['vsg'],
];

// Redirigir de vuelta al formulario o mostrar un mensaje de éxito
header("Location: formulario_lab.php?idcita=$idcita&exito=1");
exit();
?>