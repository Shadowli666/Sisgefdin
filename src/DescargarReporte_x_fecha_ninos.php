<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <!--Importante--->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descargar</title>
    <style>
        .color {
            background-color: #9BB;
        }
    </style>
</head>

<body>

    <?php
    include('configs.php');
    $fecha = date("d_m_Y");


    /**PARA FORZAR LA DESCARGA DEL EXCEL */
    header("Content-Type: text/html;charset=utf-8");
    header("Content-Type: application/vnd.ms-excel charset=iso-8859-1");
    $filename = "ReporteExcel_" . $fecha . ".xls";
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Disposition: attachment; filename=" . $filename . "");


    /***RECIBIENDO LAS VARIABLE DE LA FECHA */
    $fechaInit = date("Y-m-d", strtotime($_POST['fecha_ingreso']));
    $fechaFin = date("Y-m-d", strtotime($_POST['fechaFin']));

    /*
    $sqlTrabajadores = ("select * from trabajadores where (fecha_ingreso>='$fechaInit' and fecha_ingreso<='$fechaFin') order by fecha_ingreso desc");
    $sqlTrabajadores = ("SELECT * FROM trabajadores WHERE fecha_ingreso BETWEEN '$fechaInit' AND '$fechaFin' order by fecha_ingreso desc
    $sqlTrabajadores = ("SELECT * FROM `trabajadores` WHERE fecha_ingreso BETWEEN '$fechaInit' AND '$fechaFin'
    $sqlTrabajadores = ("select * from trabajadores where fecha_ingreso >= '$fechaInit' and fecha_ingreso < '$fechaFin';
    $sqlTrabajadores = ("SELECT * FROM trabajadores WHERE fecha_ingreso BETWEEN '$fechaInit' AND '$fecha2' ORDER BY fecha_ingreso DESC
    */

    $sqlTrabajadores = ("SELECT * FROM ninos WHERE (fecha_cita>='$fechaInit' and fecha_cita<='$fechaFin') ORDER BY fecha_cita ASC");
    $query = mysqli_query($con, $sqlTrabajadores);
    ?>


    <table style="text-align: center;" border='1' cellpadding=1 cellspacing=1>
        <thead>
            <tr style="background: #D0CDCD;">
                <th>#</th>
                <th class="color">Cedula</th>
                <th class="color">Nombre</th>
                <th class="color">Apellido</th>
                <th class="color">Edad</th>
                <th class="color">Telefono</th>
                <th class="color">Direccion</th>
                <th class="color">Diagnotico Medico</th>
                <th class="color">Peso</th>
                <th class="color">Talla</th>
                <th class="color">TMP</th>
                <th class="color">Pediatra</th>
                <th class="color">Fecha de Cit</th>
                <th scope="color">Atencion</th>
            </tr>
            <th scope="col">#</th>
            <th scope="col">Cedula</th>
            <th scope="col">Nombre</th>
            <th scope="col">Apellido</th>
            <th scope="col">Edad</th>
            <th scope="col">Telefono</th>
            <th scope="col">Direccion</th>
            <th scope="col">Diagnotico Medico</th>
            <th scope="col">Peso</th>
            <th scope="col">Talla</th>
            <th scope="col">TMP</th>
            <th scope="col">Pediatra</th>
            <th scope="col">Fecha de Cit</th>
            <th scope="col">Atencion</th>
            </th>

            </tr>
        </thead>
        <?php
        $i = 1;
        while ($data = mysqli_fetch_array($query)) { ?>
        <tbody>
            <tr>
                <td>
                    <?php echo $i++; ?>
                </td>
                <td>
                            <?php echo $data['cedula']; ?>
                        </td>
                        <td>
                            <?php echo $data['nombre']; ?>
                        </td>
                        <td>
                            <?php echo $data['apellido']; ?>
                        </td>
                        <td>
                            <?php echo $data['edad']; ?>
                        </td>
                        <td>
                            <?php echo $data['telefono']; ?>
                        </td>
                        <td>
                            <?php echo $data['representante']; ?>
                        </td>
                        <td>
                            <?php echo $data['diagnostico_medico']; ?>
                        </td>
                        <td>
                            <?php echo $data['peso']; ?>
                        </td>
                        <td>
                            <?php echo $data['talla']; ?>
                        </td>

                        <td>
                            <?php echo $data['temperatura']; ?>
                        </td>
                        <td>
                            <?php echo $data['pediatra']; ?>
                        </td>
                        <td>
                            <?php echo $data['fecha_cita']; ?>
                        </td>
                        <td>
                            <?php echo $data['atencion']; ?>
                        </td>
            </tr>


            <?php } ?>
    </table>

</body>

</html>