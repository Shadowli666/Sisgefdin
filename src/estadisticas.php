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
$permiso = "estadisticas";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
include_once "includes/header.php";
?>

     <div class="card">
        <div class="card-header">
          Estadisticas de Pacientes Atendidos
        </div>

               <div class="col-md-12 text-center mt-5">     
                <span id="loaderFiltro">  </span>
              </div>
              <div class="card-body">
          <div class="col-md-12">
		  <form action="" method="GET">
    
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
                                        <input type="date" name="to_date" value="<?php  echo date( "Y-m-d",mktime(0, 0, 0, date("m"),date("d")+1, date("Y"))); if(isset($_GET['to_date'])){ echo $_GET['to_date']; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><b></b></label> <br>
                                      <button type="submit" class="btn btn-primary">Buscar</button>
                                      <a href="./DescargarReporte_x_fecha_estadisticas.php"class="btn btn-danger mb-2" >Descargar Reporte</a>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </form>
 <div class="table-responsive">
            <table class="table table-light" id="example"  cellspacing="0"style="width:100%">
                <thead class="thead-dark">
                    <a href="./excel_administracion.php" class="btn btn-success"><i class='fas fa-file-excel'></i> </a>
                    <tr>
					        
							<th>Especialista FDN</th>
							<th>Cantidad</th>
                            
                            
                            
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
						 $conexion=mysqli_connect("localhost","root","1234","farmacia"); 

                                if(isset($_GET['from_date']) && isset($_GET['to_date']))
                                {
                                    $from_date = $_GET['from_date'];
                                    $to_date = $_GET['to_date'];
                                    

                                    $query = "SELECT itinerario.especialista,itinerario.fecha,COUNT(id_cliente) FROM itinerario WHERE itinerario.fecha BETWEEN '$from_date' AND '$to_date' GROUP BY itinerario.especialista";
                                    $query_run = mysqli_query($conexion, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        foreach($query_run as $filas)
                                        {
                                            ?>
                                            <tr>
                                        
										<td><?php echo $filas['especialista']; ?></td>
										<td><?php echo $filas['COUNT(id_cliente)']; ?></td>
										 
                                        
    
    

                                            
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
<script>
    	   $(document).ready(function($){
        var tabla = $("#example").DataTable({
            "pageLength": 50,
            "order":[[1, 'desc']],

               "createdRow":function(row,data,index){                   
                   //pintar una celda
                   if(data[1] >= 1000){
                       /* $('td', row).eq(5).css({
                           'background-color':'#ff5252',
                           'color':'white', 
                       }); */
                   
                    //pintar una fila
                  /*  $('td', row).css({
                           'background-color':'#ff5252',
                           'color':'white',
                           'border-style':'solid',
                           'border-color':'#bdbdbd' 
                       });*/
                   }
               }, 
                "drawCallback":function(){
                      //alert("La tabla se está recargando"); 
                      var api = this.api();
                      $(api.column(1).footer()).html(
                          'Total: '+api.column(1, {page:'current'}).data().sum()
                      )
                }
        });
        

        //1era forma para sum()
        //var tot = tabla.column(5).data().sum();
        //$("#total").text(tot);
    });

</script>
<script>
  $(function() {
      setTimeout(function(){
        $('body').addClass('loaded');
      }, 1000);


//FILTRANDO REGISTROS
$("#filtro").on("click", function(e){ 
  e.preventDefault();
  

  var f_ingreso = $('input[name=from_date]').val();
  var f_fin = $('input[name=to_date]').val();
  console.log(f_ingreso + '' + f_fin);

  if(f_ingreso !="" && f_fin !=""){
    $.post("filtro.php", {f_ingreso, f_fin}, function (data) {
      $("#example").hide();
      $(".resultadoFiltro").html(data);
      loaderF(false);
    });  
  }else{
    $("#loaderFiltro").html('<p style="color:red;  font-weight:bold;">Debe seleccionar ambas fechas</p>');
  }
} );

});
</script>
<?php include_once "includes/footer.php"; ?>
</body>
</html>
<?php include_once "includes/footer.php"; ?>