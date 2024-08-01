<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "ninos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
if (!empty($_POST)) {
    $alert = "";
        $id = $_POST['id'];
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $edad = $_POST['edad'];
        $genero = $_POST['genero'];
        $fecha_nac = $_POST['fecha_nac'];
        $representante = $_POST['representante'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $diagnostico_medico = $_POST['diagnostico_medico'];
        $pediatra = $_POST['pediatra'];
        $vencimiento = '';
        $temperatura = '';
        $talla = '';
        $peso = '';
    if (!empty($_POST['accion'])) {
        $vencimiento = $_POST['vencimiento'];
        $temperatura = $_POST['temperatura'];
        $peso = $_POST['peso'];
        $talla = $_POST['talla'];
    }
    if (empty($_POST['cedula']) || empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['edad']) || empty($_POST['genero']) || empty($_POST['fecha_nac']) || empty($_POST['cedula']) || empty($_POST['representante']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['diagnostico_medico']) || empty($_POST['pediatra'])) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        if (empty($id)) {
            $query = mysqli_query($conexion, "SELECT * FROM ninos WHERE idninos = '$id'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El Paciente ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO ninos(nombre,apellido,edad,genero,fecha_nac,cedula,representante,telefono,direccion,diagnostico_medico,temperatura,peso,talla,pediatra,vencimiento) values ('$nombre','$apellido','$edad', '$genero', '$fecha_nac', '$cedula', '$representante', '$telefono', '$direccion', '$diagnostico_medico','$temperatura','$peso','$talla','$pediatra', '$vencimiento')");
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
            $query_update = mysqli_query($conexion, "UPDATE ninos SET nombre = '$nombre', segun_nombre = '$segun_nombre', apellido = '$apellido', segun_apellido = '$segun_apellido', edad = '$edad', genero = '$genero', fecha_nac = '$fecha_nac', cedula = '$cedula', representante ='$representante', telefono = '$telefono',  direccion = '$direccion', diagnostico_medico = '$diagnostico_medico', fecha_cita = '$fecha_cita', vencimiento = '$vencimiento' WHERE idninos = $id");
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
                        Pediatria
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
                                <label for="cedula" class="text-dark font-weight-bold">Cedula</label>
                                <input type="text" placeholder="Ingrese Cedula" name="cedula" id="ced_cliente" class="form-control" required>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="form-group">
                                <label for="nombre" class="text-dark font-weight-bold">Nombre</label>
                                <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nom_cliente" class="form-control">
                            </div>
                        </div>
                         
                        <div class="col-md-2">
                        <div class="form-group">
                                <label for="apellido" class="text-dark font-weight-bold">Apellido</label>
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
                                <label for="fecha_nac" class="text-dark font-weight-bold">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nac" id="fecha_nac_cliente" class="form-control">
                            </div>
                        </div>
                      <div class="col-md-3">
                        <div class="form-group">
                                <label for="representante"class="text-dark font-weight-bold">Representante</label>
                                <input list="pac" placeholder="Ingrese Representante" name="representante" id="representante" class="form-control">
                                <datalist id="pac">
                                    <?php
                                            $query_tipo = mysqli_query($conexion, "SELECT * FROM cliente ");
                                            while ($datos = mysqli_fetch_assoc($query_tipo)) { ?>
                                                <option value="<?php echo $datos['nombre'] ?>"><?php echo $datos['cedula'] ?></option>
                                            <?php } ?>
                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="telefono" class="text-dark font-weight-bold">Teléfono</label>
                                <input type="number" placeholder="Ingrese Teléfono" name="telefono" id="tel_cliente" class="form-control">
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
                                <label for="diagnostico_medico" class="text-dark font-weight-bold">Diagnostico Medico</label>
                                <input type="text" placeholder="Ingrese Diagnostico Medico" name="diagnostico_medico" id="diagnostico_medico" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                        <div class="form-group">
                                <label for="pediatra" class="text-dark font-weight-bold">Pediatra</label>
                                <input list="ped" placeholder="Ingrese al Pediatra" name="pediatra" id="pediatra" class="form-control">
                                <datalist id="ped">
                                                 <?php
                                               $query_doctor = mysqli_query($conexion, "SELECT * FROM medicos WHERE especialidad = 'Pediatria' ");
                                            while ($datos = mysqli_fetch_assoc($query_doctor)) { ?>
                                                <option value="<?php echo $datos['nombre'] ?>"></option>
                                            <?php } ?>
                                         </datalist>

                                    </div>
                                </div>

                        <div class="col-md-2">
                                    <div class="form-group">
                                        <input id="accion" class="form-check-input" type="checkbox" name="accion" value="si">
                                        <label for="vencimiento">Inasistencia</label>
                                        <input id="vencimiento" class="form-control" type="date" name="vencimiento">
                                    </div>
                                </div>
                                 <div class="col-md-2">
                                    <div class="form-group">
                                        <input id="accion" class="form-check-input" type="checkbox" name="accion" value="si">
                                        <label for="peso">Peso</label>
                                        <input id="peso" class="form-control" type="text" name="peso">
                                    </div>
                                    </div>
                                   <div class="col-md-2">
                                    <div class="form-group">
                                        <input id="accion" class="form-check-input" type="checkbox" name="accion" value="si">
                                        <label for="talla">Talla</label>
                                        <input id="talla" class="form-control" type="text" name="talla">
                                    </div>
                                </div>
                                 <div class="col-md-2">
                                    <div class="form-group">
                                        <input id="accion" class="form-check-input" type="checkbox" name="accion" value="si">
                                        <label for="temperatura">Temperatura</label>
                                        <input id="temperatura" class="form-control" type="text" name="temperatura">
                                    </div>
                                    </div>
                                <div class="col-md-6">
                                    <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                                    <input type="button" value="Nuevo" onclick="limpiar()" class="btn btn-success" id="btnNuevo">
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#ninos">
									Ingrese Paciente
									</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="ninos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Registro De Paciente </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="insertninos.php" method="POST">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cedula" class="text-dark font-weight-bold">Cedula</label>
                                <input list="ced" placeholder="Ingrese Cedula" name="cedula" id="cedula"
                                    class="form-control">
                                <datalist id="ced">
                                    <option value="V-"></option>
                                    <option value="E-"></option>
                                    <option value="J-"></option>
                                    <option value="H1-"></option>
                                    <option value="H2-"></option>
                                    <option value="H3-"></option>
                                    <option value="H4-"></option>
                                    <option value="H5-"></option>

                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombre" class="text-dark font-weight-bold">Nombre</label>
                                <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nombre"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="apellido" class="text-dark font-weight-bold">Apellido</label>
                                <input type="text" placeholder="Ingrese Apellido" name="apellido" id="apellido"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="edad" class="text-dark font-weight-bold">Edad</label>
                                <input type="text" placeholder="Ingrese Edad" name="edad" id="edad"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="genero" class="text-dark font-weight-bold">Genero</label>
                                <input list="gen" placeholder="Ingrese Genero" name="genero" id="genero"
                                    class="form-control">
                                <datalist id="gen">
                                    <option>Femenino</option>
                                    <option>Masculino</option>
                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_nac" class="text-dark font-weight-bold">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nac" id="fecha_nac" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="telefono" class="text-dark font-weight-bold">Teléfono</label>
                                <input type="text" placeholder="Ingrese Teléfono" name="telefono" id="telefono"
                                    class="form-control">
                                <input type="hidden" name="id" id="id">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="direccion" class="text-dark font-weight-bold">Dirección</label>
                                <input type="text" placeholder="Ingrese Direccion" name="direccion" id="direccion"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
               
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="submit" name="insertdata" class="btn btn-success">Guardar</button>
            </div>
        </div>
      </form>
    </div>
</div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tbl">
                    <thead class="thead-dark">
                        <tr>
                            
                            <th>Cedula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Representante</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Diagnostico</th>
                            <th>Peso</th>
                            <th>Talla</th>
                            <th>Temperatura</th>
                            <th>Pediatra</th>
                            <th>Fecha de Cit</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         include "../conexion.php";
                        date_default_timezone_set('America/Caracas');
                        $hoy = date('Y-m-d');
                        
                         $query = mysqli_query($conexion, "SELECT * FROM ninos WHERE fecha_cita >='$hoy' " );
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                                 while ($data = mysqli_fetch_array($query)) { ?>
                                    <tr>
                                       
                                        <td><?php echo $data['cedula']; ?></td>
                                        <td><?php echo $data['nombre']; ?></td>
                                        <td><?php echo $data['apellido']; ?></td>
                                        <td><?php echo $data['representante']; ?></td>
                                        <td><?php echo $data['telefono']; ?></td>
                                        <td><?php echo $data['direccion']; ?></td>
                                        <td><?php echo $data['diagnostico_medico']; ?></td>
                                        <td><?php echo $data['peso']; ?></td>
                                        <td><?php echo $data['talla']; ?></td>
                                        <td><?php echo $data['temperatura']; ?></td>
                                        <td><?php echo $data['pediatra']; ?></td>
                                        <td><?php echo $data['fecha_cita']; ?></td>
                                        <td>
                                             <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pdnj<?php echo $data['idninos']; ?>"><i class='fas fa-edit'></i></button>
                                            <form action="eliminar_nino.php?id=<?php echo $data['idninos']; ?>" method="post" class="confirmar d-inline">
                                                <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                            </form>
                                     </td>
                                        
                                    </tr>
                                     <?php  include('ModalEditarninos.php'); ?>
                             <?php }
                            } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
<?php include_once "includes/footero.php"; ?>