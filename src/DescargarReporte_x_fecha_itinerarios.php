<?php
include('../conexion.php');

// Verifica si los índices están definidos antes de intentar acceder a ellos
if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
    $from_date = $_GET['from_date'];
    $to_date = $_GET['to_date'];

    // Consulta SQL para obtener los datos del itinerario en el rango de fechas seleccionado
    $query = "SELECT itinerario.idcita, itinerario.id_cliente, cliente.idcliente, cliente.cedula, cliente.nombre, cliente.apellido, cliente.edad, cliente.telefono, itinerario.especialidad, itinerario.descripcion,
            itinerario.precios, itinerario.fecha, itinerario.atencion, itinerario.observacion 
            FROM itinerario INNER JOIN cliente ON cliente.idcliente = itinerario.id_cliente 
            WHERE itinerario.fecha BETWEEN '$from_date' AND '$to_date' ";
    $query_run = mysqli_query($conexion, $query);

    // Creamos el archivo Excel
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Reporte_Itinerario.xls");


    // Creamos la tabla Excel
    echo "<table border='1'>";
    echo "<tr><th>Cédula</th><th>Nombre</th><th>Apellido</th><th>Doctor</th><th>Servicio</th><th>Precios</th><th>Fecha de Cita</th><th>Atención</th><th>Observación</th></tr>";

    // Iteramos sobre los resultados de la consulta y escribimos cada fila en el archivo Excel
    while ($row = mysqli_fetch_assoc($query_run)) {
        echo "<tr>";
        echo "<td>" . $row['cedula'] . "</td>";
        echo "<td>" . $row['nombre'] . "</td>";
        echo "<td>" . $row['apellido'] . "</td>";
        echo "<td>" . $row['especialidad'] . "</td>";
        echo "<td>" . $row['descripcion'] . "</td>";
        echo "<td>" . $row['precios'] . "</td>";
        echo "<td>" . $row['fecha'] . "</td>";
        echo "<td>" . $row['atencion'] . "</td>";
        echo "<td>" . $row['observacion'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    // Si los índices no están definidos, muestra un mensaje de error
    echo "Error: No se recibieron las fechas correctamente.";
}
?>
