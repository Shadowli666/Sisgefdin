<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!--  Datatables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css"/>  

    <style>
    .table thead, .table tfoot{
        background-color: #455a64;
        color:azure;
    }
    </style>
</head>
<body>
    
<?php
session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "consulta_med";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}

include_once "includes/header.php";
?>
    <div class="card">
        <div class="card-header">
            Consulta de Medicamentos 
        </div>
          <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="example">
                    <thead class="thead-dark">

                    <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Principio Activo</th>
                            <th>Presentacion</th>
                            <th>Utilidad</th>
                            <th>Ubicacion</th>
                            <th>Stock</th>
                            <th>Especialidad</th>
                            <th></th>
                    </tr>
                </thead>
                <tbody>
                      <?php
                            include "../conexion.php";
                            $query = mysqli_query($conexion, "SELECT * FROM producto");
                            $result = mysqli_num_rows($query);

                            if ($result > 0) {
                                while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                                        <td><?php echo $data['codigo']; ?></td>
                                        <td><?php echo $data['descripcion']; ?></td>
                                        <td><?php echo $data['id_tipo']; ?></td>
                                        <td><?php echo $data['id_presentacion']; ?></td>
                                        <td><?php echo $data['id_lab']; ?></td>
                                        <td><?php echo $data['ubi']; ?></td>
                                        <td><?php echo $data['existencia']; ?></td>
                                        <td><?php echo $data['espe']; ?></td>
                                        <td>
                                          
                                            <button class="btn btn-danger" style="display:none" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                            
                                        
                                    </td>

                                </tr>
                        <?php }
                        } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    <script src="../assets/js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<script src="../assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../assets/js/material-dashboard.js" type="text/javascript"></script>
<script src="../assets/js/bootstrap-notify.js"></script>
<script src="../assets/js/arrive.min.js"></script>
<script src="../assets/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="../assets/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="../assets/js/sweetalert2.all.min.js"></script>
<script src="../assets/js/jquery-ui/jquery-ui.min.js"></script>
<script src="../assets/js/chart.min.js"></script>
<script src="../assets/js/funcion.js"></script>
<script src="../assets/js/funciones.js"></script>   
    <!-- SUM()  Datatables-->
    <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>

    <script>
    $(document).ready(function(){
        var tabla = $("#example").DataTable({
               "createdRow":function(row,data,index){                   
                   //pintar una celda
                   if(data[6] == 0){
                        $('td', row).eq(6).css({
                           'background-color':'#ff5252',
                           'color':'white', 
                       }); 
                   
                  
                      }
                }
        });

        //1era forma para sum()
        //var tot = tabla.column(5).data().sum();
        //$("#total").text(tot);
    });
    </script>

<?php include_once "includes/footer.php"; ?>
</body>
</html>