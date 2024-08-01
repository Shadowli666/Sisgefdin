<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "vacunacion";
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
    $segun_nombre = $_POST['segun_nombre'];
    $apellido = $_POST['apellido'];
    $segun_apellido = $_POST['segun_apellido'];
    $edad = $_POST['edad'];
    $genero = $_POST['genero'];
    $fecha_nac = $_POST['fecha_nac'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $vacuna = '';
    $dosis = '';
    $vencimiento = '';
    $representante = '';
    if (!empty($_POST['accion'])) {
    
        $representante = $_POST['representante'];
        $vacuna = $_POST['vacuna'];
        $dosis = $_POST['dosis'];
    }
    if (empty($_POST['cedula']) || empty($_POST['nombre']) || empty($_POST['segun_nombre']) || empty($_POST['apellido']) || empty($_POST['segun_apellido']) || empty($_POST['edad']) || empty($_POST['genero']) || empty($_POST['fecha_nac']) || empty($_POST['cedula']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        if (empty($id)) {
            $query = mysqli_query($conexion, "SELECT * FROM vacunacion WHERE idvacuna = '$id'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El Paciente ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO vacunacion(nombre,segun_nombre,apellido,segun_apellido,edad,genero,fecha_nac,cedula,representante,telefono,direccion, vacuna,dosis) values ('$nombre', '$segun_nombre','$apellido','$segun_apellido', '$edad', '$genero', '$fecha_nac', '$cedula', '$representante', '$telefono', '$direccion','$vacuna','$dosis')");
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
            $query_update = mysqli_query($conexion, "UPDATE vacunacion SET nombre = '$nombre', segun_nombre = '$segun_nombre', apellido = '$apellido', segun_apellido = '$segun_apellido', edad = '$edad', genero = '$genero', fecha_nac = '$fecha_nac', cedula = '$cedula', representante ='$representante', telefono = '$telefono',  direccion = '$direccion', vacuna = '$vacuna', dosis = '$dosis' WHERE idvacuna = $id");
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
                        Programa de Vacunacion
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php echo (isset($alert)) ? $alert : ''; ?>
                                    <form action="" method="post" autocomplete="off" id="formPacientes">

                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="form-group">

                                                    <label for="cedula" class="text-dark font-weight-bold">Cedula</label>
                                                    <input type="text" placeholder="Ingrese Cedula" name="cedula" id="ced_cliente" class="form-control" required>

                                                </div>

                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="nombre" class="text-dark font-weight-bold">Primer Nombre</label>
                                                    <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nom_cliente" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="segun_nombre" class="text-dark font-weight-bold">Segundo Nombre</label>
                                                    <input type="text" placeholder="Ingrese Segundo Nombre" name="segun_nombre" id="segun_nombre_cliente" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="apellido" class="text-dark font-weight-bold">Primer Apellido</label>
                                                    <input type="text" placeholder="Ingrese Apellido" name="apellido" id="ape_cliente" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="segun_apellido" class="text-dark font-weight-bold">Segundo Apellido</label>
                                                    <input type="text" placeholder="Ingrese Segundo Apellido" name="segun_apellido" id="segun_apellido_cliente" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edad" class="text-dark font-weight-bold">Edad</label>
                                                    <input type="number" placeholder="Ingrese Edad" name="edad" id="eda_cliente" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="genero" class="text-dark font-weight-bold">Genero</label>
                                                    <input list="gen" placeholder="Ingrese Genero" name="genero" id="gen_cliente" class="form-control">
                                                    <datalist id="gen">
                                                        <option>Femenino</option>
                                                        <option>Masculino</option>
                                                    </datalist>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="fecha_nac" class="text-dark font-weight-bold">Fecha de Nacimiento</label>
                                                    <input type="date" name="fecha_nac" id="fecha_nac_cliente" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input id="accion" class="form-check-input" type="checkbox" name="accion" value="si">
                                                    <label for="representante">Representante</label>
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
                                                    <input id="accion" class="form-check-input" type="checkbox" name="accion" value="si">
                                                    <label for="vacuna" class="text-dark font-weight-bold">Vacuna</label>
                                                    <input list="vac" placeholder="Ingrese Vacuna" name="vacuna" id="vacuna" class="form-control">
                                                    <datalist id="vac">
                                                        <option>BCG </option>
                                                        <option>SRP</option>
                                                        <option>Fiebre Amarilla </option>
                                                        <option>Hepatitis B </option>
                                                        <option>Influenza Estacional</option>
                                                        <option>Rotavirus </option>
                                                        <option>Meningocococica B-C</option>
                                                        <option>Neumococo Conjugada</option>
                                                        <option>Neumococo Polisacarida</option>
                                                        <option>Neumo 13 Valente</option>
                                                        <option>Pentavalaente </option>
                                                        <option>Polio Oral </option>
                                                        <option>Polio Inactivada</option>
                                                        <option>Polio Inyectado </option>
                                                        <option>Rabia Humana</option>
                                                        <option>Sarampion / Rubeola / Paroditis</option>
                                                        <option>Toxoide Tetanico Difterico</option>
                                                        <option>Pentavalente</option>
                                                        <option>COVID-19</option>
                                                        <option>Antigripal mayores de 60 años y con patologia hipertension, diabetes</option>
                                                    </datalist>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input id="accion" class="form-check-input" type="checkbox" name="accion" value="si">
                                                <label for="dosis" class="text-dark font-weight-bold">Dosis</label>
                                                <input list="dos" placeholder="Ingrese La Dosis" name="dosis" id="dosis" class="form-control">
                                                <datalist id="dos">
                                                    <option> I Dosis</option>
                                                    <option> II Dosis</option>
                                                    <option> III Dosis</option>
                                                    <option> IV Dosis</option>
                                                    <option> V Dosis</option>
                                                    <option> Dosis Estacional</option>
                                                    <option> Dosis Unica</option>
                                                    <option> 1° Refuerzo</option>
                                                    <option> 2° Refuerzo</option>
                                                </datalist>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#studentaddmodal">
                                                Ingrese Paciente
                                            </button>
                                            <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                                            <input type="button" value="Nuevo" onclick="limpiar()" class="btn btn-success" id="btnNuevo">

                                        </div>
                                </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="studentaddmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add Student Data </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form action="insertcode.php" method="POST">

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="cedula" class="text-dark font-weight-bold">Cedula</label>
                                                <input list="ced" placeholder="Ingrese Cedula" name="cedula" id="cedula" class="form-control">
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
                                                <label for="nombre" class="text-dark font-weight-bold">Primer Nombre</label>
                                                <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nombre" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="segun_nombre" class="text-dark font-weight-bold">Segundo Nombre</label>
                                                <input type="text" placeholder="Ingrese Segundo Nombre" name="segun_nombre" id="segun_nombre" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="apellido" class="text-dark font-weight-bold">Primer Apellido</label>
                                                <input type="text" placeholder="Ingrese Apellido" name="apellido" id="apellido" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="segun_apellido" class="text-dark font-weight-bold">Segundo Apellido</label>
                                                <input type="text" placeholder="Ingrese Segundo Apellido" name="segun_apellido" id="segun_apellido" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="edad" class="text-dark font-weight-bold">Edad</label>
                                                <input type="number" placeholder="Ingrese Edad" name="edad" id="edad" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="genero" class="text-dark font-weight-bold">Genero</label>
                                                <input list="gen" placeholder="Ingrese Genero" name="genero" id="genero" class="form-control">
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
                                                <input type="text" placeholder="Ingrese Teléfono" name="telefono" id="telefono" class="form-control">
                                                <input type="hidden" name="id" id="id">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="correo" class="text-dark font-weight-bold">Correo Electronico</label>
                                                <input type="email" class="form-control" placeholder="Ingrese Correo Electrónico" name="correo" id="correo">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="direccion" class="text-dark font-weight-bold">Dirección</label>
                                                <input type="text" placeholder="Ingrese Direccion" name="direccion" id="direccion" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="parroquia" class="text-dark font-weight-bold">Parroquia</label>
                                                <input list="parr" placeholder="Ingrese Parroquia" name="parroquia" id="parroquia" class="form-control">
                                                <datalist id="parr">
                                                    <option value="Municipio Almirante Padilla">
                                                    <option value="Isla de Toas">
                                                    <option value="Monagas">
                                                    <option value="Municipio Baralt">
                                                    <option value="San Timoteo">
                                                    <option value="General Urdaneta">
                                                    <option value="Libertador">
                                                    <option value="Marcelino Briceño">
                                                    <option value="Nuevo">
                                                    <option value="Manuel Guanipa Matos">
                                                    <option value="Municipio Cabimas">
                                                    <option value=" Ambrosio">
                                                    <option value="Carmen Herrera">
                                                    <option value="La Rosa">
                                                    <option value="German Rios Linares">
                                                    <option value="San Benito">
                                                    <option value="Romulo Betancourt">
                                                    <option value="Jorge Hernandez">
                                                    <option value="Punta Gorda">
                                                    <option value="Aristides Calvani">
                                                    <option value="Municipio Catatumbo">
                                                    <option value="Encontraos">
                                                    <option value="Udon Perez">
                                                    <option value="Municipio Colón">
                                                    <option value="Moralito">
                                                    <option value="San Carlos del Zulia">
                                                    <option value="Santa Cruz del Zulia">
                                                    <option value="Santa Barbara">
                                                    <option value="Urribarri">
                                                    <option value="Municipio Francisco Javier Pulgar">
                                                    <option value="Agustin Codazzi">
                                                    <option value="Carlos Quevedo">
                                                    <option value="Francisco Javier Pulgar">
                                                    <option value="Simón Rodríguez">
                                                    <option value="Municipio Jesús Enrique Lossada">
                                                    <option value="La Concepcion">
                                                    <option value="San Jose">
                                                    <option value="Mariano Parra Leon">
                                                    <option value="Jose Ramon Yepez">
                                                    <option value="Municipio Jesús María Semprún">
                                                    <option value="Jesús María Semprún">
                                                    <option value="Bari">
                                                    <option value="Municipio La Cañada de Urdaneta">
                                                    <option value="La Concepcion">
                                                    <option value="Andres Bello">
                                                    <option value="Chiquinquira">
                                                    <option value="Carmelo">
                                                    <option value="Porteritos">
                                                    <option value="Municipio Lagunillas">
                                                    <option value="Libertad">
                                                    <option value="Paraute">
                                                    <option value="Venezuela">
                                                    <option value="Eleazar Lopez Contreras">
                                                    <option value="Campo Lara">
                                                    <option value="Municipio Mara">
                                                    <option value="San Rafael">
                                                    <option value="La Sierrita">
                                                    <option value="Las Parcelas">
                                                    <option value="Luis de Vicente">
                                                    <option value="Monseñor Marcos Sergio Godoy">
                                                    <option value="Ricaurte">
                                                    <option value="Tamare">
                                                    <option value=" Municipio Maracaibo">
                                                    <option value="Antonio Borjas Romero">
                                                    <option value="Bolivar">
                                                    <option value="Cacique Mara">
                                                    <option value="Carraccilo Parra Perez">
                                                    <option value="Cecilio Acosta">
                                                    <option value="Cristo Aranza">
                                                    <option value="Chiquinquira">
                                                    <option value="Coquivacoa">
                                                    <option value="Francisco Eugenio Bustamante">
                                                    <option value="Idelfonozo Vasquez">
                                                    <option value="Juana Avila">
                                                    <option value="Luis Hurtado Higuera">
                                                    <option value="Manuel Dagnino">
                                                    <option value="Olegario Villalobos">
                                                    <option value="Raul Leoni">
                                                    <option value="Santa Lucia">
                                                    <option value="San Isidro">
                                                    <option value="Venancio Pulgar">
                                                    <option value="Chiquinquira">
                                                    <option value="Municipio Miranda">
                                                    <option value="Altagracia">
                                                    <option value="Faria">
                                                    <option value="Ana Maria Campos">
                                                    <option value="San Antonio">
                                                    <option value="San Jose">
                                                    <option value="Municipio Guajira">
                                                    <option value="Alta Guaijira">
                                                    <option value="Sinamaica">
                                                    <option value="Elias Sanchez Rubio">
                                                    <option value="Guajira">
                                                    <option value="Municipio Rosario de Perijá">
                                                    <option value="Donaldo Garcia">
                                                    <option value="Rosario">
                                                    <option value="Sixto Sambrano">
                                                    <option value="Municipio San Francisco">
                                                    <option value="San Francisco">
                                                    <option value="El Bajo">
                                                    <option value="Domitila Flores">
                                                    <option value="Francisco Ochoa">
                                                    <option value="Los Cortijos">
                                                    <option value="Marcial Hernandez">
                                                    <option value="Jose Domingo Rus">
                                                    <option value="Municipio Santa Rita">
                                                    <option value="Santa Rita">
                                                    <option value="El Mene">
                                                    <option value="Pedro Lucas Urribarri">
                                                    <option value="Jose Cenobio Urribarri">
                                                    <option value="Simón Bolívar">
                                                    <option value="Rafael María Baralt">
                                                    <option value="Manuel Manrique">
                                                    <option value="Rafael Urdaneta">
                                                    <option value="Municipio Sucre">
                                                    <option value="Bobures">
                                                    <option value="Gibraltar">
                                                    <option value="Heras">
                                                    <option value="Monseñor Arturo Alvarez">
                                                    <option value="Romulo Gallegos">
                                                    <option value="El Batey">
                                                    <option value="Municipio Valmore Rodríguez">
                                                    <option value="Rafael Urdaneta">
                                                    <option value="La Victoria">
                                                    <option value="Raul Cuenca">
                                                </datalist>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="municipio" class="text-dark font-weight-bold">Municipio</label>
                                                <input list="muni" placeholder="Ingrese Municipio" name="municipio" id="municipio" class="form-control">
                                                <datalist id="muni">
                                                    <option value="Almirante Padilla">
                                                    <option value="Baralt">
                                                    <option value="Cabimas">
                                                    <option value="Catatumbo">
                                                    <option value="Colón">
                                                    <option value="Francisco Javier Pulgar">
                                                    <option value="Jesús Enrique Lossada">
                                                    <option value="Jesús María Semprún">
                                                    <option value="La Cañada de Urdaneta">
                                                    <option value="Lagunillas">
                                                    <option value="Mara">
                                                    <option value="Maracaibo">
                                                    <option value="Miranda">
                                                    <option value="Guajira">
                                                    <option value="Rosario de Perijá">
                                                    <option value="San Francisco">
                                                    <option value="Santa Rita">
                                                    <option value="Simón Bolívar">
                                                    <option value="Sucre">
                                                    <option value="Valmore Rodríguez">
                                                </datalist>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="estado" class="text-dark font-weight-bold">Estado</label>
                                                <input list="est" placeholder="Ingrese Estado" name="estado" id="estado" class="form-control">
                                                <datalist id="est">
                                                    <option value="Zulia">
                                                </datalist>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" name="insertdata" class="btn btn-success">Guardar</button>
                                    </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>





            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tbl">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Cedula</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Fech. Nacimiento</th>
                                <th>Fech. Vacunacion</th>
                                <th>Vacuna</th>
                                <th>Dosis</th>
                                <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "../conexion.php";

                            $query = mysqli_query($conexion, "SELECT * FROM vacunacion");
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                                while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                                        <td><?php echo $data['idvacuna']; ?></td>
                                        <td><?php echo $data['cedula']; ?></td>
                                        <td><?php echo $data['nombre']; ?></td>
                                        <td><?php echo $data['apellido']; ?></td>
                                        <td><?php echo $data['telefono']; ?></td>
                                        <td><?php echo $data['direccion']; ?></td>
                                        <td><?php echo $data['fecha_nac']; ?></td>
                                        <td><?php echo $data['fecha']; ?></td>
                                        <td><?php echo $data['vacuna']; ?></td>
                                        <td><?php echo $data['dosis']; ?></td>
                                        <td>
                                            <form action="eliminar_vacuna.php?id=<?php echo $data['idvacuna']; ?>" method="post" class="confirmar d-inline">
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
<?php include_once "includes/footero.php"; ?>