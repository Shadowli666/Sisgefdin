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
$fechaFin = date("Y-m-d", strtotime($_POST['f_fin']));

$sqlItine = ("SELECT i.*, c.idcliente, c.cedula,c.nombre, c.apellido, c.telefono,c.edad FROM itinerario i INNER JOIN cliente c ON i.id_cliente = c.idcliente WHERE  `fecha` BETWEEN '$fechaInit' AND '$fechaFin'  ORDER BY fecha ASC");
$query_run = mysqli_query($con, $sqlItine);
//print_r($sqlTrabajadores);


$total = mysqli_num_rows($query_run);
echo '<strong>Total: </strong> (' . $total . ')';

?>

<table class="table table-hover" id="example">
    <thead class="thead-dark">
        <tr>
            <th>cedula</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Teléfono</th>
            <th>Doctor</th>
            <th>Servicio</th>
            <th>Pago</th>
            <th>Fecha de Cit</th>
            <th>Observacion</th>
            <th>Atencion</th>
            <th>Accion</th>
        </tr>
    </thead>

    <tbody>
        <?php

        $i = 1;
        if ($total > 0) {
            if (mysqli_num_rows($query_run) > 0) {
                foreach ($query_run as $data) {
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
                            <?php echo $data['observacion']; ?>
                        </td>
                        <td>
                            <?php echo $data['atencion']; ?>
                        </td>
                        <td>
                            <button type="button" class="btn-sm btn-primary" data-toggle="modal"
                                data-target="#editChildresne<?php echo $data['idcita']; ?>"><i class='fas fa-edit'></i>
                            </button>
                            <form action="eliminar_itinerario.php?id=<?php echo $data['idcita']; ?>" method="post"
                                class="confirmar d-inline">
                                <button class="btn-sm btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                            </form>
                            <?php include('ModalEditare.php'); ?>

                        </td>

                    </tr>
                    <?php
                }
            } else {
                ?>

                <tr>
                    <td>
                <?php echo "No se encontraron resultados"; ?>
            </td>
        
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
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>




<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>
<!--  Datatables JS-->
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>
<!-- SUM()  Datatables-->
<script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>


< <script>
    $(document).ready(function(){
        var tabla = $("#example").DataTable({
            "pageLength": 50,
               
               "createdRow":function(row,data,index){                   
                   //pintar una celda
                   if(data[9] == "Atendido"){
                        $('td', row).eq(9).css({
                           'background-color':'#07850F',
                           'color':'white', 


                       }); 
                   
                  
                      }
                      if(data[9] == "En Espera"){
                        $('td', row).eq(9).css({
                           'background-color':'#FFD133',
                           'color':'white', 


                       }); 
                   
                  
                      }
                      if(data[9] == "No se Presento"){
                        $('td', row).eq(9).css({
                           'background-color':'#FA0909',
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