<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!--  Datatables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.dataTables.min.css">

</head>

<body>

    <?php
    session_start();
    require_once "../conexion.php";
    $id_user = $_SESSION['idUser'];
    $permiso = "consulta_cita";
    $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
    $existe = mysqli_fetch_all($sql);
    if (empty($existe) && $id_user != 1) {
        header('Location: permisos.php');
    }
    include_once "includes/header.php";
    ?>

    <div class="card">
        <div class="card-header">
            Pacientes de Laboratorio
        </div>

        <div class="col-md-12 text-center mt-5">
            <span id="loaderFiltro"> </span>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                <div class="table-responsive resultadoFiltro">
                    <table class="table table-light" id="tbla" cellspacing="0" style="width:100%">
                        <thead class="thead-dark">
                            <a href="./excel_cita.php" class="btn btn-success"><i class='fas fa-file-excel'></i> </a>
                            <tr>
                                <th>cedula</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Edad</th>
                                <th>Teléfono</th>
                                <th>Especialista FDN</th>
                                <th>Servicio</th>
                                <th>Precio</th>
                                <th>Fecha de Cit</th>
                                <th>Atencion</th>
                                <th>Observacion</th>
                                <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "../conexion.php";
                            date_default_timezone_set('America/Caracas');
                            $hoy = date('Y-m-d');

                            $query = mysqli_query($conexion, "SELECT i.*, c.idcliente, c.cedula,c.nombre, c.apellido, c.telefono, c.edad FROM itinerario i INNER JOIN cliente c ON i.id_cliente = c.idcliente  WHERE especialista = 'Lcda. Neida Villavicencio / Laboratorio' AND fecha >='$hoy'  ");
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                                while ($data = mysqli_fetch_array($query)) { 
                                    $idcita = $data['idcita']; // Definir la variable $idcita
                                    ?> 
                                    <tr>

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
                                            <?php echo $data['especialista']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['descripcion']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['precios']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['fecha']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['atencion']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['observacion']; ?>
                                        </td>
                                        <td>
                                           
                                        <a href="formulario_lab.php?idcita=<?php echo $idcita; ?>">Enlace al formulario</a>
                                </a>



                                        </td>

                                    </tr>
                                    
                                <?php }
                            } ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
            integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>

        <!--  Datatables JS-->
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>
        <!-- SUM()  Datatables-->
        <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>

        <script>
            $(document).ready(function () {
                var tabla = $("#tbla").DataTable({
                    "pageLength": 50,

                    "createdRow": function (row, data, index) {
                        //pintar una celda
                        if (data[9] == "Atendido") {
                            $('td', row).eq(9).css({
                                'background-color': '#07850F',
                                'color': 'white',


                            });


                        }
                        if (data[9] == "En Espera") {
                            $('td', row).eq(9).css({
                                'background-color': '#FFD133',
                                'color': 'white',


                            });


                        }
                        if (data[9] == "No se Presento") {
                            $('td', row).eq(9).css({
                                'background-color': '#FA0909',
                                'color': 'white',


                            });


                        }
                    }
                });

                //1era forma para sum()
                //var tot = tabla.column(5).data().sum();
                //$("#total").text(tot);
            });
        </script>
        <?php include_once "includes/footero.php"; ?>
</body>

</html>