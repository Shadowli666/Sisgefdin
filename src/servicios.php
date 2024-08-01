

<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "baremo";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['descripcion']) || empty($_POST['precio']) || empty($_POST['codservicios']) || empty($_POST['especialidad'])) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorio
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        $id = $_POST['id'];
        $codservicios = $_POST['codservicios'];
        $descripcion = $_POST['descripcion'];
        $especialidad = $_POST['especialidad'];
        $precio = $_POST['precio'];
        $result = 0;
        if (empty($id)) {
            $query = mysqli_query($conexion, "SELECT * FROM servicios WHERE id = '$id'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Servicio ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO servicios(idservicios,descripcion,especialidad, precio) values ('$codservicios','$descripcion','$especialidad','$precio')");
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
            $sql_update = mysqli_query($conexion, "UPDATE servicios SET idservicios = '$codservicios', descripcion = '$descripcion', especialidad = '$especialidad', precio = '$precio'  WHERE id = '$id'");
            if ($sql_update) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Servicio Modificado
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
    mysqli_close($conexion);
}

include_once "includes/header.php";
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php echo (isset($alert)) ? $alert : ''; ?>
                <form action="" method="post" autocomplete="off" id="formulario">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="codservicios" class="text-dark font-weight-bold">Codigo del Servicio</label>
                                <input type="text" placeholder="Ingrese la Descripcion del Servicio" name="codservicios" id="codservicios" class="form-control">
                                <input type="hidden" name="id" id="id">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="descripcion" class="text-dark font-weight-bold">Descripcion del Servicio</label>
                                <input type="text" placeholder="Ingrese la Descripcion del Servicio" name="descripcion" id="descripcion" class="form-control">
                               
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="especialidad" class="text-dark font-weight-bold">Especialidad</label>
                                <input type="text" placeholder="Ingrese la Especialidad del Servicio" name="especialidad" id="especialidad" class="form-control">
                                
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                <label for="precio" class="text-dark font-weight-bold">Precio del Servicio</label>
                                <input type="text" placeholder="Ingrese la Precio del Servicio" name="precio" id="precio" class="form-control">
                                
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                            <input type="button" value="Nuevo" class="btn btn-success" id="btnNuevo" onclick="limpiar()">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tbl">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>servicios</th>
                                <th>Especialidad</th>
                                <th>precio</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "../conexion.php";

                            $query = mysqli_query($conexion, "SELECT * FROM servicios");
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                                while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                                        <td><?php echo $data['idservicios']; ?></td>
                                        <td><?php echo $data['descripcion']; ?></td>
                                        <td><?php echo $data['especialidad']; ?></td>
                                        <td><?php echo $data['precio']; ?></td>
                                        <td style="width: 200px;">
                                            <a href="#" onclick="editarServicio(<?php echo $data['id']; ?>)" class="btn btn-primary"><i class='fas fa-edit'></i></a>
                                            <form action="eliminar_baremo.php?id=<?php echo $data['id']; ?>" method="post" class="confirmar d-inline">
                                                <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include_once "includes/footer.php"; ?>