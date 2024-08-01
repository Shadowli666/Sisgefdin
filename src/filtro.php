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
sleep(1);
include('configs.php');

/**
 * Nota: Es recomendable guardar la fecha en formato año - mes y dia (2022-08-25)
 * No es tan importante que el tipo de fecha sea date, puede ser varchar
 * La funcion strtotime:sirve para cambiar el forma a una fecha,
 * esta espera que se proporcione una cadena que contenga un formato de fecha en Inglés US,
 * es decir año-mes-dia e intentará convertir ese formato a una fecha Unix dia - mes - año.
*/

$fechaInit = date("Y-m-d", strtotime($_POST['f_ingreso']));
$fechaFin  = date("Y-m-d", strtotime($_POST['f_fin']));

$sqlTrabajadores = ("SELECT d.*, c.idcliente, c.nombre FROM detalle_pago d INNER JOIN cliente c ON d.id_cliente = c.idcliente WHERE  `fecha` BETWEEN '$fechaInit' AND '$fechaFin' ORDER BY fecha ASC");
$query = mysqli_query($con, $sqlTrabajadores);
//print_r($sqlTrabajadores);
$total   = mysqli_num_rows($query);
echo '<strong>Total: </strong> ('. $total .')';
?>

<table class="table table-hover" id="example">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Metodo de Pago</th>
                    <th scope="col">Referencia</th>
                    <th scope="col">Bolivares</th>
					<th scope="col">Dolares</th>
					<th scope="col">Concepto</th>
                    <th scope="col">Fecha</th>
					<th scope="col">Accion</th>
        </tr>
    </thead>
    
        <tbody>
		<?php
    $i = 1;
    while ($row = mysqli_fetch_array($query)) { ?>
                  <tr>
                            <td><?php echo $i++; ?></td>
							<td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['metodo_pago']; ?></td>
                            <td><?php echo $row['referencia']; ?></td>
							<td><?php echo $row['bolivares']; ?></td>
							<td><?php echo $row['dolares']; ?></td>
							<td><?php echo $row['obser']; ?></td>
							<td><?php echo $row['fecha']; ?></td>
							<td></td>
							
                </tr>
				<?php } 
				?>
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
                                </tr>
                            </tfoot>
</table>
       



     <<!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        
    <!--  Datatables JS-->
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>   
    <!-- SUM()  Datatables-->
    <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>


    <script>
    $(document).ready(function($){
        var tabla = $("#example").DataTable({
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
                      $(api.column(4).footer()).html(
                          'Total Bs: '+api.column(4, {page:'current'}).data().sum().toFixed(2)
                      )
					   $(api.column(5).footer()).html(
                          'Total $: '+api.column(5, {page:'current'}).data().sum().toLocaleString(undefined, {minimumFractionDigits:2})
                      )
                }
        });

        //1era forma para sum()
        //var tot = tabla.column(5).data().sum();
        //$("#total").text(tot);
    });
    </script>
	

