<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!--  Datatables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css"/>  
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.dataTables.min.css">
    
</head>
<body>
<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "pacientes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['cedula']) || empty($_POST['nombre']) || empty($_POST['segun_nombre']) || empty($_POST['apellido']) || empty($_POST['segun_apellido']) || empty($_POST['edad']) || empty($_POST['fecha_nac']) || empty($_POST['genero']) || empty($_POST['telefono']) || empty($_POST['correo']) || empty($_POST['direccion']) || empty($_POST['parroquia']) || empty($_POST['municipio']) || empty($_POST['estado'])) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorio
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        $id = $_POST['id'];
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $segun_nombre = $_POST['segun_nombre'];
        $apellido = $_POST['apellido'];
        $segun_apellido = $_POST['segun_apellido'];
        $edad = $_POST['edad'];
        $fecha_nac = $_POST['fecha_nac'];
        $genero = $_POST['genero'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $direccion = $_POST['direccion'];
        $parroquia = $_POST['parroquia'];
        $municipio = $_POST['municipio'];
        $estado = $_POST['estado'];
        $result = 0;
        if (empty($id)) {
            $query = mysqli_query($conexion, "SELECT * FROM cliente WHERE cedula = '$cedula'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El cliente ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO cliente(cedula,nombre,segun_nombre,apellido,segun_apellido,edad,fecha_nac,genero,telefono,correo,direccion,parroquia,municipio,estado) values ('$cedula', '$nombre', '$segun_nombre', '$apellido', '$segun_apellido', '$edad','$fecha_nac','$genero', '$telefono','$correo', '$direccion','$parroquia','$municipio','$estado')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Paciente registrado
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
        }else{
            $sql_update = mysqli_query($conexion, "UPDATE cliente SET cedula = '$cedula', nombre = '$nombre', segun_nombre = '$segun_nombre', apellido = '$apellido', segun_apellido = '$segun_apellido', edad ='$edad', fecha_nac = '$fecha_nac', genero = '$genero' , telefono = '$telefono', correo = '$correo', direccion = '$direccion', parroquia = '$parroquia', municipio = '$municipio', estado ='$estado' WHERE idcliente = $id");
            if ($sql_update) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Paciente Modificado
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
                <?php echo (isset($alert)) ? $alert : '' ; ?>
                <form action="" method="post" autocomplete="off" id="formulario">
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
                                <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nombre" class="form-control" required>
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                <label for="segun_nombre" class="text-dark font-weight-bold">Segundo Nombre</label>
                                <input type="text" placeholder="Ingrese Segundo Nombre" name="segun_nombre" id="segun_nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                        <div class="form-group">
                                <label for="apellido" class="text-dark font-weight-bold">Primer Apellido</label>
                                <input type="text" placeholder="Ingrese Apellido" name="apellido" id="apellido" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                        <div class="form-group">
                                <label for="segun_apellido" class="text-dark font-weight-bold">Segundo Apellido</label>
                                <input type="text" placeholder="Ingrese Segundo Apellido" name="segun_apellido" id="segun_apellido" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                        <div class="form-group">
                                <label for="edad" class="text-dark font-weight-bold">Edad</label>
                                <input type="text" placeholder="Ingrese Edad" name="edad" id="edad" class="form-control" required>
                            </div>
                        </div>
                         <div class="col-md-3">
                        <div class="form-group">
                                <label for="genero" class="text-dark font-weight-bold">Genero</label>
                                <input list="gen" placeholder="Ingrese Genero" name="genero" id="genero" class="form-control" required>
                                <datalist id="gen">
                                    <option>Femenino</option>
                                    <option>Masculino</option>
                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-3">
                        <div class="form-group">
                                <label for="fecha_nac" class="text-dark font-weight-bold">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nac" id="fecha_nac" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="telefono" class="text-dark font-weight-bold">Teléfono</label>
                                <input type="text" placeholder="Ingrese Teléfono" name="telefono" id="telefono" class="form-control" required>
                                <input type="hidden" name="id" id="id">
                            </div>
                        </div>
                         <div class="col-md-3">
                    <div class="form-group">
                        <label for="correo" class="text-dark font-weight-bold">Correo Electronico</label>
                        <input type="text" class="form-control" placeholder="Ingrese Correo Electrónico" name="correo" id="correo">
                    </div>

                </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="direccion" class="text-dark font-weight-bold">Dirección</label>
                                <input type="text" placeholder="Ingrese Direccion" name="direccion" id="direccion" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="parroquia" class="text-dark font-weight-bold">Parroquia</label>
                                <input list="parr" placeholder="Ingrese Parroquia" name="parroquia" id="parroquia" class="form-control" required>
                                <datalist id="parr">
                                     <option value = "Municipio Almirante Padilla">
                                     <option value = "Isla de Toas">
                                     <option value = "Monagas">
                                     <option value = "Municipio Baralt">
                                     <option value = "San Timoteo">
                                     <option value = "General Urdaneta">
                                     <option value = "Libertador">
                                     <option value = "Marcelino Briceño">
                                     <option value = "Nuevo">
                                     <option value = "Manuel Guanipa Matos">
                                     <option value = "Municipio Cabimas">
                                     <option value = " Ambrosio">
                                     <option value = "Carmen Herrera">
                                     <option value = "La Rosa">
                                     <option value = "German Rios Linares">
                                     <option value = "San Benito">
                                     <option value = "Romulo Betancourt">
                                     <option value = "Jorge Hernandez">
                                     <option value = "Punta Gorda">
                                     <option value = "Aristides Calvani">
                                     <option value = "Municipio Catatumbo">
                                     <option value = "Encontraos">
                                     <option value = "Udon Perez">
                                     <option value = "Municipio Colón">
                                     <option value = "Moralito">
                                     <option value = "San Carlos del Zulia">
                                     <option value = "Santa Cruz del Zulia">
                                     <option value = "Santa Barbara">
                                     <option value = "Urribarri">
                                     <option value = "Municipio Francisco Javier Pulgar">
                                     <option value = "Agustin Codazzi">
                                     <option value = "Carlos Quevedo">
                                     <option value = "Francisco Javier Pulgar">
                                     <option value = "Simón Rodríguez">
                                     <option value = "Municipio Jesús Enrique Lossada">
                                     <option value = "La Concepcion">
                                     <option value = "San Jose">
                                     <option value = "Mariano Parra Leon">
                                     <option value = "Jose Ramon Yepez">
                                     <option value = "Municipio Jesús María Semprún">
                                     <option value = "Jesús María Semprún">
                                     <option value = "Bari">
                                     <option value = "Municipio La Cañada de Urdaneta">
                                     <option value = "La Concepcion">
                                     <option value = "Andres Bello">
                                     <option value = "Chiquinquira">
                                     <option value = "Carmelo">
                                     <option value = "Porteritos">
                                     <option value = "Municipio Lagunillas">
                                     <option value = "Libertad">
                                     <option value = "Alonso de Ojeda">
                                     <option value = "Venezuela">
                                     <option value = "Eleazar Lopez Contreras">
                                     <option value = "Campo Lara">
                                     <option value = "Municipio Mara">
                                     <option value = "San Rafael">
                                     <option value = "La Sierrita">
                                     <option value = "Las Parcelas">
                                     <option value = "Luis de Vicente">
                                     <option value = "Monseñor Marcos Sergio Godoy">
                                     <option value = "Ricaurte">
                                     <option value = "Tamare">
                                     <option value = " Municipio Maracaibo">
                                     <option value = "Antonio Borjas Romero">
                                     <option value = "Bolivar">
                                     <option value = "Cacique Mara">
                                     <option value = "Carraccilo Parra Perez">
                                     <option value = "Cecilio Acosta">
                                     <option value = "Cristo Aranza">
                                     <option value = "Chiquinquira">
                                     <option value = "Coquivacoa">
                                     <option value = "Francisco Eugenio Bustamante">
                                     <option value = "Idelfonozo Vasquez">
                                     <option value = "Juana Avila">
                                     <option value = "Luis Hurtado Higuera">
                                     <option value = "Manuel Dagnino">
                                     <option value = "Olegario Villalobos">
                                     <option value = "Raul Leoni">
                                     <option value = "Santa Lucia">
                                     <option value = "San Isidro">
                                     <option value = "Venancio Pulgar">
                                     <option value = "Chiquinquira">
                                     <option value = "Municipio Miranda">
                                     <option value = "Altagracia">
                                     <option value = "Faria">
                                     <option value = "Ana Maria Campos">
                                     <option value = "San Antonio">
                                     <option value = "San Jose">
                                     <option value = "Municipio Guajira">
                                     <option value = "Alta Guaijira">
                                     <option value = "Sinamaica">
                                     <option value = "Elias Sanchez Rubio">
                                     <option value = "Guajira">
                                     <option value = "Municipio Rosario de Perijá">
                                     <option value = "Donaldo Garcia">
                                     <option value = "Rosario">
                                     <option value = "Sixto Sambrano">
                                     <option value = "Municipio San Francisco">
                                     <option value = "San Francisco">
                                     <option value = "El Bajo">
                                     <option value = "Domitila Flores">
                                     <option value = "Francisco Ochoa">
                                     <option value = "Los Cortijos">
                                     <option value = "Marcial Hernandez">
                                     <option value = "Jose Domingo Rus">
                                     <option value = "Municipio Santa Rita">
                                     <option value = "Santa Rita">
                                     <option value = "El Mene">
                                     <option value = "Pedro Lucas Urribarri">
                                     <option value = "Jose Cenobio Urribarri">
                                     <option value = "Simón Bolívar">
                                     <option value = "Rafael María Baralt">
                                     <option value = "Manuel Manrique">
                                     <option value = "Rafael Urdaneta">
                                     <option value = "Municipio Sucre">
                                     <option value = "Bobures">
                                     <option value = "Gibraltar">
                                     <option value = "Heras">
                                     <option value = "Monseñor Arturo Alvarez">
                                     <option value = "Romulo Gallegos">
                                     <option value = "El Batey">
                                     <option value = "Municipio Valmore Rodríguez">
                                     <option value = "Rafael Urdaneta">
                                     <option value = "La Victoria">
                                     <option value = "Raul Cuenca">
                                    </datalist>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="municipio" class="text-dark font-weight-bold">Municipio</label>
                                <input list="muni" placeholder="Ingrese Municipio" name="municipio" id="municipio" class="form-control">
                                 <datalist id="muni">
                                     <option value = "Almirante Padilla">
                                     <option value = "Baralt">
                                     <option value = "Cabimas">
                                     <option value = "Catatumbo">
                                     <option value = "Colón">
                                     <option value = "Francisco Javier Pulgar">
                                     <option value = "Jesús Enrique Lossada">
                                     <option value = "Jesús María Semprún">
                                     <option value = "La Cañada de Urdaneta">
                                     <option value = "Lagunillas">
                                     <option value = "Mara">
                                     <option value = "Maracaibo">
                                     <option value = "Miranda">
                                     <option value = "Guajira">
                                     <option value = "Rosario de Perijá">
                                     <option value = "San Francisco">
                                     <option value = "Santa Rita">
                                     <option value = "Simón Bolívar">
                                     <option value = "Sucre">
                                     <option value = "Valmore Rodríguez">
                                 </datalist>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="estado" class="text-dark font-weight-bold">Estado</label>
                                <input list="est"  placeholder="Ingrese Estado" name="estado" id="estado" class="form-control" required>
                                <datalist id="est">
                                    <option value = "Zulia">
                                </datalist>
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
                    <table class="table table-striped table-bordered" id="tbla">
                        <thead class="thead-dark">
                             <a href="./excel_clientes.php" class="btn btn-success"><i class='fas fa-file-excel'></i> </a>
                             <a  href="./clientes.php ?>" class="btn btn-info" onclick="window.print()"><i class='fas fa-print'></i> </a>
                            <tr>
                                <th>id</th>
                                <th>Cedula</th>
                                <th>Nombre</th>
                                <th>S.Nombre</th>
                                <th>Apellido</th>
                                <th>S.Apellido</th>
                                <th>Edad</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Parroquia</th>
                                <th>Municipio</th>
                                <th>Estado</th>
                                <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody>                            
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
           $("#tbla").DataTable({
              "processing": true,
              "serverSide": true,
              "sAjaxSource": "ServerSide/serversideUsuarios.php", 
              "columnDefs": [ {
                    "targets": -1,
                    "render": (a,b,row,d)=>{
                        return `<a href="#" onclick="editarCliente(${row[0]})" class="btn btn-primary"><i class='fas fa-edit'></i></a>
                        <form action="eliminar_cliente.php?id=${row[0]}" method="post" class="confirmar d-inline">
                            <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                        </form>`;
                    }
                } ]
           }); 
        });
    </script>  
<?php include_once "includes/footer.php"; ?>
</body>
</html>