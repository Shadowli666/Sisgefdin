<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="../assets/css/material-dashboard.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/js/jquery-ui/jquery-ui.min.css">
    <script src="../assets/js/all.min.js" crossorigin="anonymous"></script>
</head>
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
        Historial de Pagos

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
                 
                                <form action="" method="post" autocomplete="off" id="formulario">
                                    <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                            <div class="form-group">
                                <label for="metodo_pagos" class="text-dark font-weight-bold">Metodo de Pago</label>
                                <input list="pago" placeholder="Ingrese Metodo de Pago" name="metodo_pagos" id="metodo_pagos" class="form-control">
                                <input type="hidden" name="id" id="id">
                                <datalist id="pago">
                                <?php
                                            $query_pago = mysqli_query($conexion, "SELECT * FROM metodo_pago");
                                            while ($datos = mysqli_fetch_assoc($query_pago)) { ?>
                                                <option value="<?php echo $datos['metodo_pago'] ?>"></option>
                                            <?php } ?>
                                            </datalist>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="bolivares" class="text-dark font-weight-bold">Bolivares</label>
                                <input type="text" placeholder="Monto (Bs)" name="bolivares" id="bolivares" class="form-control">
                                
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="dolares" class="text-dark font-weight-bold">Dolares</label>
                                <input type="text" placeholder="Monto ($)" name="dolares" id="dolares" class="form-control">
                                
                            </div>
                        </div>
                         <div class="col-md-5">
                            <div class="form-group">
                                <label for="obser" class="text-dark font-weight-bold">Concepto de Pago</label>
                                <input type="text" placeholder="Ingrese la Concepto de Pago" name="obser" id="obser" class="form-control">
                                
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <input type="submit" value="Editar" class="btn btn-primary" id="btnAccion">
                           
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
                       
                        <th width="10%">Cedula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Metodo de Pago</th>
                        <th>Referencia</th>
                        <th>Bolivares</th>
                        <th>Dolares</th>
                        <th>Concepto</th>
                        <th>Fecha</th>
                        <th width="10%">Accion</th>
                    </tr>
                </thead>
                <tbody>
                           

<?php 
                       $conexion=mysqli_connect("localhost","farmacia_test","1234","farmacia"); 

                                if(isset($_GET['from_date']) && isset($_GET['to_date']))
                                {
                                    $from_date = $_GET['from_date'];
                                    $to_date = $_GET['to_date'];

                                    $query =  "SELECT d.*, c.idcliente, c.cedula, c.nombre, c.apellido FROM detalle_pago d INNER JOIN cliente c ON d.id_cliente = c.idcliente WHERE fecha BETWEEN '$from_date' AND '$to_date' ";
                                    $query_run = mysqli_query($conexion, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        foreach($query_run as $data)
                                        {
                                            ?>
                                            <tr>
                                           
                            <td><?php echo $data['cedula']; ?></td>
                            <td><?php echo $data['nombre']; ?></td>
                            <td><?php echo $data['apellido']; ?></td>
                            <td><?php echo $data['metodo_pago']; ?></td>
                            <td><?php echo $data['referencia']; ?></td>
                            <td><?php echo $data['bolivares']; ?></td>
                            <td><?php echo $data['dolares']; ?></td>
                            <td><?php echo $data['obser']; ?></td>
                            <td><?php echo $data['fecha']; ?></td>
                            <td>
                                <a href="#" onclick="editarPago(<?php echo $data['id_pago']; ?>)" class="btn btn-primary"><i class='fas fa-edit'></i></a>

                                 <form action="eliminar_pago.php?id=<?php echo $data['id_pago']; ?>" method="post" class="confirmar d-inline">
                                                <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                            </form>
                            </td>
                                            
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
            </table>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
    <!--  Datatables JS-->
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>   
    <!-- SUM()  Datatables-->
    <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
    <script src="../assets/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="../assets/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    
<script type="text/javascript" >
    function editarPago(id) {
    const action = "editarPago";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarPago: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            $('#metodo_pagos').val(datos.metodo_pago);
            $('#bolivares').val(datos.bolivares);
            $('#dolares').val(datos.dolares);
            $('#obser').val(datos.obser);
            $('#id').val(datos.id_pago);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}
</script>
<script type="text/javascript">
$(document).ready( function() {
  initTable();
  tableActions();
} );
 
function initTable () {
  return $('#tabla').dataTable( {
    
    "retrieve": true
  } );
}
 
function tableActions () {
  var table = initTable();
 
  // perform API operations with `table`
  // ...
}
</script>



 
</body>
</html>
<?php include_once "includes/footer.php"; ?>
