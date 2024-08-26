<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!--  Datatables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css"/> 
     
</head>
<body>
<?php
session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "historial_pago";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}

if (!empty($_POST)) {
    $alert = "";
    if ( empty($_POST['metodo_pagos']) || empty($_POST['bolivares']) || empty($_POST['dolares']) || empty($_POST['obser'])) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorio
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        $id = $_POST['id'];
        $metodo_pagos = $_POST['metodo_pagos'];
        $bolivares = $_POST['bolivares'];
        $dolares = $_POST['dolares'];
        $obser = $_POST['obser'];
        $result = 0;
        if (empty($id)) {
            $query = mysqli_query($conexion, "SELECT * FROM detalle_pago WHERE id_pago = '$id'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Servicio ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO detalle_pago( bolivares, dolares, obser) values ('$bolivares', '$dolares', '$obser')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Servicio Registrado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error al registrar
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                }
            }
        } else {
            $sql_update = mysqli_query($conexion, "UPDATE detalle_pago SET  metodo_pago = '$metodo_pagos', bolivares = '$bolivares', dolares = '$dolares', obser = '$obser' WHERE id_pago = $id");
            if ($sql_update) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Pago Modificado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error al modificar
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
           }
        }
    }
   
}
   
include_once "includes/header.php";
?>
<br>
<div class="card">
    <div class="card-header">
        Arqueo de Caja

    </div>
<div class="card-body">

<form action="" method="GET">
    <div class="col-md-12">
                            <div class="row">
                                
                                <div class="col-md-4">
                                    
                                    <div class="form-group">
                                        <label><b>Del Dia</b></label>
                                        
                                        <input type="date" name="from_date" value="<?php echo date("Y-m-d"); if(isset($_GET['from_date'])){ echo $_GET['from_date']; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><b> Hasta  el Dia</b></label>
                                        <input type="date" name="to_date" value="<?php echo date("Y-m-d"); if(isset($_GET['to_date'])){ echo $_GET['to_date']; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><b></b></label> <br>
                                      <button type="submit" class="btn btn-warning">Buscar</button>

                                    </div>
                                </div>

                            </div>
                        </div>
                          </form>
                 
                                
</div>
                            </div>

<div class="table-responsive">
            <table class="table table-light" id="tabla">
                <thead class="thead-dark">
                    <tr>
                       
                       
                        <th>Metodo de Pago</th>
                       
                        <th>Bolivares</th>
                        <th>Dolares</th>
                        
                        
                    </tr>
                </thead>
                <tbody>
                           

<?php 
                       $conexion=mysqli_connect("localhost","farmacia_test","1234","farmacia"); 

                                if(isset($_GET['from_date']) && isset($_GET['to_date']))
                                {
                                    $from_date = $_GET['from_date'];
                                    $to_date = $_GET['to_date'];

                                    $query =  "SELECT metodo_pago, SUM(bolivares) AS mtotalbs, SUM(dolares) AS mtotald FROM  detalle_pago  INNER JOIN cliente c ON detalle_pago.id_cliente = c.idcliente WHERE fecha BETWEEN '$from_date' AND '$to_date' GROUP BY metodo_pago";
                                    $query_run = mysqli_query($conexion, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        foreach($query_run as $data)
                                        {
                                            ?>
                                            <tr>
                                           
                            
                            <td><?php echo $data['metodo_pago']; ?></td>
                            
                            <td><?php echo $data['mtotalbs']; ?></td>
                            <td><?php echo $data['mtotald']; ?></td>
                           
                            
                                            
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                      
                                         <tr>
                                         <td><?php  echo "No se encontraron resultados"; ?></td>
                                  <?php
                                    }
                                }
                            ?>
                             </tr>
                            
                            </tbody>
                            <tfoot class="thead-dark">
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    
                                </tr>
                            </tfoot>
            </table>
        </div>
    </div>
</div>
<<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        
    <!--  Datatables JS-->
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>   
    <!-- SUM()  Datatables-->
    <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>

    

<script type="text/javascript">
    $(document).ready(function($){
        var tabla = $("#tabla").DataTable({
               "pageLength": 50,
               "order":[[1, 'Desc']],
               "createdRow":function(row,data,index){                   
                   //pintar una celda
                   if(data[4] >= 1000){
                       /* $('td', row).eq(5).css({
                           'background-color':'#ff5252',
                           'color':'white', 
                       }); */
                   
                    //pintar una fila
                    $('td', row).css({
                           'background-color':'#ff5252',
                           'color':'white',
                           'border-style':'solid',
                           'border-color':'#bdbdbd' 
                       });
                   }
               }, 
                "drawCallback":function(){

                      //alert("La tabla se está recargando"); 
                      var api = this.api();
                      
                      $(api.column(1).footer()).html(
                          'Total Bs: '+api.column(1, {page:'current'}).data().sum().toLocaleString(undefined, {minimumFractionDigits:2})
                      )
					   $(api.column(2).footer()).html(
                          'Total $: '+api.column(2, {page:'current'}).data().sum().toLocaleString(undefined, {minimumFractionDigits:2})
                      )
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

<?php include_once "includes/footer.php"; ?>