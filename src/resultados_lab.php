<?php
session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "salida";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
include_once "includes/header.php";
?>
<div class="card">
    <div class="card-header">
        Resultados de Laboratorio
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <a href="./resultados_lab.php ?>" class="btn btn-info" onclick="window.print()"><i class='fas fa-print'></i> </a>
            <table class="table table-light" id="tabla">
                <thead class="thead-dark">

                    <tr>
                        <th>#</th>
                        <th>Id Cliente</th>
                        <th>Cedula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>fecha</th>
                        <th></th>
                    </tr>
                </thead>

            </table>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>


<!--    Datatables-->
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>
<script>
    $(document).ready(function() {
        $("#tabla").DataTable({
            "processing": true,
            "serverSide": true,
            "sAjaxSource": "ServerSide/serversideresultados_lab.php",
            "columnDefs": [{
                "data": null,
                "targets": -1,
                "render": (a, b, row, d) => {
                    return `<a href="pdf/documento.php?cl=${row[1]}&v=${row[0]}" target="_blank" class="btn btn-success"><i class="fas fa-file-pdf"></i></a>`;
                }
            }]
        });
    });
</script>
<?php include_once "includes/footerlab.php"; ?>