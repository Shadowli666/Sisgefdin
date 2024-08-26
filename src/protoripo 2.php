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
<body>
    
<?php
session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "administracion";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
include_once "includes/header.php";
?>

     <div class="card">
        <div class="card-header">
           Administracion
        </div>

               <div class="col-md-12 text-center mt-5">     
                <span id="loaderFiltro">  </span>
              </div>
              <div class="card-body">
          <div class="col-md-12">
    
		  <form action="" method="GET">
    
                            <div class="row">
                                
                                <div class="col-md-3">
                                    
                                    <div class="form-group">
                                        <label><b>Del Dia</b></label>
                                        <input type="date" name="from_date" value="<?php if(isset($_GET['from_date'])){ echo $_GET['from_date']; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><b> Hasta  el Dia</b></label>
                                        <input type="date" name="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><b> Especialista FDN</b></label>
                                        <input list="doctor" name="especialista" value="<?php if(isset($_GET['especialista'])){ echo $_GET['especialista']; } ?>" class="form-control">
                                        <datalist id="doctor">
                                        <?php
                                            $query_doctor = mysqli_query($conexion, "SELECT medicos.nombre, medicos.especialidad FROM medicos  ");
                                            while ($datos = mysqli_fetch_assoc($query_doctor)) { ?>
                                                <option value="<?php echo $datos['nombre'] ?> / <?php echo $datos['especialidad'] ?>"></option>
                                            <?php } ?>
                                         </datalist>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><b></b></label> <br>
                                      <button type="submit" class="btn btn-primary">Buscar</button>
                                      <a  href="./DescargarReporte_x_fecha_itinerarios.php" class="btn btn-danger mb-2">Reporte</a>
                                    </div>
                                    
                </div>
                                </div>

                            
                            <br>
                        </form>
 <div class="table-responsive">
            <table class="table table-light" id="tabla"  cellspacing="0"style="width:100%">
                <thead class="thead-dark">
                    <a href="./excel_administracion.php" class="btn btn-success"><i class='fas fa-file-excel'></i> </a>
                    <tr>
					        <th>cedula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
							<th>Doctor</th>
							<th>Servicio</th>
                            <th>Precios</th>
                            <th>Fecha de Cit</th>
                            <th>Atencion</th>
                            <th>Observacion</th>
        
                        </tr>
                    </thead>
                    <tbody>
                        <?php
						 $conexion=mysqli_connect("localhost","farmacia_test","","farmacia"); 

                                if(isset($_GET['from_date']) && isset($_GET['to_date']) && isset($_GET['especialista']))
                                {
                                    $from_date = $_GET['from_date'];
                                    $to_date = $_GET['to_date'];
                                    $especialista = $_GET['especialista'];

                                    $query = "SELECT itinerario.idcita, itinerario.cedula, itinerario.nombre, itinerario.apellido, itinerario.edad, itinerario.telefono, itinerario.doctor, itinerario.descripcion,
                                    itinerario.precios, itinerario.fecha, itinerario.atencion, itinerario.observacion FROM itinerario
                                     WHERE itinerario.doctor = '$especialista' AND itinerario.fecha BETWEEN '$from_date' AND '$to_date' ";
                                    $query_run = mysqli_query($conexion, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        foreach($query_run as $filas)
                                        {
                                            ?>
                                            <tr>
                                        <td><?php echo $filas['cedula']; ?></td>
                                        <td><?php echo $filas['nombre']; ?></td>
                                        <td><?php echo $filas['apellido']; ?></td>
										<td><?php echo $filas['doctor']; ?></td>
										<td><?php echo $filas['descripcion']; ?></td>
                                        <td><?php echo $filas['precios']; ?></td>
                                        <td><?php echo $filas['fecha']; ?></td>
                                        <td><?php echo $filas['atencion']; ?></td>
                                        <td><?php echo $filas['observacion']; ?></td>
										
                                            
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
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    
                                </tr>
                            </tfoot>
            </table>
        </div>
    </div>
	</div>
   
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        
    <!--  Datatables JS-->
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>   
    <!-- SUM()  Datatables-->
    <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>

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
    $(document).ready(function($){
        var tabla = $("#tabla").DataTable({
            "pageLength": 200,
               
               "createdRow":function(row,data,index){                   
                   //pintar una celda
                   if(data[7] == "Atendido"){
                        $('td', row).eq(7).css({
                           'background-color':'#07850F',
                           'color':'white', 


                       }); 
                   
                  
                      }
                },
        
                "drawCallback":function(){

                      //alert("La tabla se está recargando"); 
                      var api = this.api();
                      
                      $(api.column(0).footer()).html(
                          'Total $: '+api.column(5, {page:'current'}).data().sum().toLocaleString(undefined, {minimumFractionDigits:2})
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