<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "itinerario";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
if (!empty($_POST)) {
    $alert = "";
        $id = $_POST['id'];
        $idcliente = $_POST['idcliente'];
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $edad = $_POST['edad'];
        $genero = $_POST['genero'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $especialista = $_POST['especialista'];
		$descripcion = $_POST['descripcion'];
        $precios = $_POST['precios'];
        $observacion = '';
    if (!empty($_POST['accion'])) {
        $observacion = $_POST['observacion'];
    }
    if (empty($_POST['cedula']) || empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['edad']) || empty($_POST['genero']) || empty($_POST['cedula']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty( $_POST['descripcion']) ||  empty( $_POST['precios']) || empty($_POST['especialista']) || empty($_POST['idcliente'])) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        if (empty($id)) {
            $query = mysqli_query($conexion, "SELECT * FROM itinerario WHERE idcita = '$id'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El Paciente ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
             } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO itinerario(id_cliente,nombre,apellido,edad,genero,cedula,telefono,direccion,especialista,descripcion,precios,observacion) values ('$idcliente','$nombre', '$apellido','$edad', '$genero', '$cedula', '$telefono', '$direccion', '$especialista','$descripcion', '$precios', '$observacion' )");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Paciente registrado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                    Error al registrar el producto
                  </div>';
                }
            }
        } else {
            $query_update = mysqli_query($conexion, "UPDATE itinerario SET idcliente = '$idcliente', nombre = '$nombre', apellido = '$apellido', edad = '$edad', genero = '$genero', cedula = '$cedula', telefono = '$telefono',  direccion = '$direccion', especialista = '$especialista', descripcion = '$descripcion', precios = '$precios', observacion = '$observacion' WHERE idcita = $id");
            if ($query_update) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Paciente Modificado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
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
<div class="card shadow-lg">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        Agenda del Doctor
                    </div>
                    <div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php echo (isset($alert)) ? $alert : '' ; ?>
                <form action="" method="post" autocomplete="off" id="formulario">
                    <div class="row">
                    <div class="col-md-2">
                         <div class="form-group">
                                <label for="idcliente" class="text-dark font-weight-bold">Id del Paciente</label>
                                <input type="text" placeholder="Id del Paciente" name="idcliente" id="idcliente" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                         <div class="form-group">
                                <label for="cedula" class="text-dark font-weight-bold">Cedula</label>
                                <input type="text" placeholder="Ingrese Cedula" name="cedula" id="ced_cliente" class="form-control" required>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="form-group">
                                <label for="nombre" class="text-dark font-weight-bold">Primer Nombre</label>
                                <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nom_cliente" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                        <div class="form-group">
                                <label for="apellido" class="text-dark font-weight-bold">Primer Apellido</label>
                                <input type="text" placeholder="Ingrese Apellido" name="apellido" id="ape_cliente" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                        <div class="form-group">
                                <label for="edad" class="text-dark font-weight-bold">Edad</label>
                                <input type="text" placeholder="Ingrese Edad" name="edad" id="eda_cliente" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                        <div class="form-group">
                                <label for="genero" class="text-dark font-weight-bold">Genero</label>
                                <input type="text" placeholder="Ingrese Genero" name="genero" id="gen_cliente" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="telefono" class="text-dark font-weight-bold">Teléfono</label>
                                <input type="text" placeholder="Ingrese Teléfono" name="telefono" id="tel_cliente" class="form-control">
                                <input type="hidden" name="id" id="id">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="direccion" class="text-dark font-weight-bold">Dirección</label>
                                <input type="text" placeholder="Ingrese Direccion" name="direccion" id="dir_cliente" class="form-control">
                            </div>
                        </div>
						<div class="col-md-3">
                                    <div class="form-group">
                                         <label for="especialista" class=" text-dark font-weight-bold">Especialistas FDN</label>
                                        <input list="doc" id="doctor" class="form-control" name="especialista" placeholder="Ingrese el Especialista" required>
                                        <datalist id="doc">
                                            <?php
                                            $query_doctor = mysqli_query($conexion, "SELECT * FROM medicos ");
                                            while ($datos = mysqli_fetch_assoc($query_doctor)) { ?>
                                                <option value="<?php echo $datos['nombre'] ?>"></option>
                                            <?php } ?>
                                         </datalist>

                                    </div>
                                </div>
								<div class="col-md-4">
                                    <div class="form-group">
                                         <label for="descripcion" class=" text-dark font-weight-bold">Servicio</label>
                                        <input list="ser" id="descripcion" class="form-control" name="descripcion" placeholder="Ingrese el Especialista" required>
                                        <datalist id="ser">
                                            <?php
                                            $query_servicio = mysqli_query($conexion, "SELECT * FROM servicios ");
                                            while ($datos = mysqli_fetch_assoc($query_servicio)) { ?>
                                                <option value="<?php echo $datos['descripcion'] ?>"></option>
                                            <?php } ?>
                                         </datalist>

                                    </div>
                                </div>
                                 <div class="col-md-2">
                            <div class="form-group">
                                <label for="precios" class="text-dark font-weight-bold">Precios</label>
                                <input type="text" placeholder="Ingrese Precio del Servicio" name="precios" id="precios" class="form-control">
                            </div>
                        </div>
							 <div class="col-lg-2">
								 <div class="form-group">
                                        <input id="accion" class="form-check-input" type="checkbox" name="accion" value="si">
                                        <label for="observacion">Casos Especiales</label>
                                        <input id="observacion" class="form-control" list="obsr" name="observacion">
										<datalist id="obsr">
										<option>CASO ALDEA</option>
                                        <option>CASO ALCALDIA</option>
                                        <option>CASO FDN / Referido por: </option>
                                        <option>CASO SOCIAL</option>
                                        <option>CLINICA LAGUNILLAS</option>
                                        <option>EMANUEL</option>
                                        <option>MEDEMALAB</option>
                                        <option>NAZARENO</option>
                                        <option>SMO</option>
                                        <option>SANTA MONICA</option>
                                        <option>OCCIDENTE</option>
                                        <option>UNIOJEDA</option>
										</datalist>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                                    <input type="button" value="Nuevo" onclick="limpiar()" class="btn btn-success" id="btnNuevo">
                                     <a type="button" href="venta.php" class="btn btn-warning" >
                                    Facturacion
                                    </a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tbla">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cedula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Teléfono</th>
							<th>Especialista FDN</th>
							<th>Servicio</th>
                            <th>Fecha de Cit</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            include "../conexion.php";
                        date_default_timezone_set('America/Caracas');
                        $hoy = date('Y-m-d');
                        
                         $query = mysqli_query($conexion, "SELECT i.*, c.idcliente, c.cedula,c.nombre, c.apellido, c.telefono FROM itinerario i INNER JOIN cliente c ON i.id_cliente = c.idcliente WHERE fecha >='$hoy' " );
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                                 while ($data = mysqli_fetch_array($query)) { ?>
                                    <tr>
                                    
                                    <td><?php echo $data['cedula']; ?></td>
                                    <td><?php echo $data['nombre']; ?></td>
                                    <td><?php echo $data['apellido']; ?></td>
                                    <td><?php echo $data['telefono']; ?></td>
                                    <td><?php echo $data['especialista']; ?></td>
                                    <td><?php echo $data['descripcion']; ?></td>
                                    <td><?php echo $data['fecha']; ?></td>
                                    
                                        
                                        
                                        <td> 
                                            <form action="eliminar_itinerario.php?id=<?php echo $data['idcita']; ?>" method="post" class="confirmar d-inline">
                                                <button class="btn-sm btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                            </form>
                                            <button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#editChildresne<?php echo $data['idcita']; ?>"><i class='fas fa-edit'></i>
                         </button>
                                        </td>

                                    
                                     <?php  include('ModalEditare.php'); ?>
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
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>   
    <!--  Datatables JS-->
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>   
    <!-- SUM()  Datatables-->
    <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function(){
        var tabla = $("#tbla").DataTable({
               
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