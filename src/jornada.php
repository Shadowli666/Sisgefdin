<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "censo";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['cedula']) || empty($_POST['nombre']) || empty($_POST['apellido']) ||  empty($_POST['edad']) || empty($_POST['genero']) || empty($_POST['telefono']) || empty($_POST['causa_consulta']) || empty($_POST['medicacion']) || empty($_POST['antecedente_per']) || empty($_POST['antecedente_fa']) || empty($_POST['habitos'])|| empty($_POST['observacion'])) {
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
        $apellido = $_POST['apellido'];
        $edad = $_POST['edad'];
		$genero = $_POST['genero'];
        $telefono = $_POST['telefono'];
        $causa_consulta = $_POST['causa_consulta'];
        $medicacion = $_POST['medicacion'];
        $antecedente_per = $_POST['antecedente_per'];
        $antecedente_fa = $_POST['antecedente_fa'];
        $habitos = $_POST['habitos'];
        $observacion = $_POST['observacion'];
        $result = 0;
        if (empty($id)) {
            $query = mysqli_query($conexion, "SELECT * FROM jornada WHERE id = '$id'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El cliente ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO jornada(cedula,nombre,apellido,edad,genero,telefono,causa_consulta,medicacion,antecedente_per,antecedente_fa,habitos,observacion) values ('$cedula', '$nombre', '$apellido',  '$edad', '$genero', '$telefono', '$causa_consulta','$medicacion','$antecedente_per', '$antecedente_fa', '$habitos','$observacion')");
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
            $sql_update = mysqli_query($conexion, "UPDATE jornada SET cedula = '$cedula', nombre = '$nombre', apellido = '$apellido', edad ='$edad', genero ='$genero', telefono = '$telefono', causa_consulta = '$causa_consulta', medicacion = '$medicacion', antecedente_fa = '$antecedente_fa' WHERE id = $id");
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
                                <input type="text" placeholder="Ingrese Cedula" name="cedula" id="ced_cliente" class="form-control" required>
								
                            </div>
							
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombre" class="text-dark font-weight-bold">Nombre</label>
                                <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nom_cliente" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                        <div class="form-group">
                                <label for="apellido" class="text-dark font-weight-bold">Apellido</label>
                                <input type="text" placeholder="Ingrese Apellido" name="apellido" id="ape_cliente" class="form-control">
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
                                <label for="telefono" class="text-dark font-weight-bold">Teléfono</label>
                                <input type="text" placeholder="Ingrese Teléfono" name="telefono" id="tel_cliente" class="form-control">
                                <input type="hidden" name="id" id="id">
                            </div>
                        </div>
						<div class="col-md-3">
                            <div class="form-group">
                                <label for="causa_consulta" class="text-dark font-weight-bold">Motivo de la Consulta</label>
                                <input type="text" placeholder="Ingrese Causa de la Consulta" name="causa_consulta" id="causa_consulta" class="form-control">
                            </div>
                        </div>
						<div class="col-md-3">
                            <div class="form-group">
                                <label for="medicacion" class="text-dark font-weight-bold">Medicacion</label>
                                <input type="text" placeholder="Ingrese Medicacion del Paciente" name="medicacion" id="medicacion" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="habitos" class="text-dark font-weight-bold">Habitos del Paciente</label>
                                <input list="hab" placeholder="Ingrese Antedentes Familiares del Paciente" name="habitos" id="habitos" class="form-control">
                                <datalist id= "hab">
                                    <option>Alcolhol</option>
                                    <option>Cafe</option>
                                    <option>tabaco</option>
                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="antecedente_per" class="text-dark font-weight-bold">Antecedentes Personales</label>
                                <input list="anp" placeholder="Ingrese Antecedentes Personales del Paciente" name="antecedente_per" id="antecedente_per" class="form-control">
                                <datalist id= "anp">
                                    <option>Asmatico</option>
                                    <option>Arritmia Cardiaca</option>
                                    <option>Bipolaridad</option>
                                    <option>Diabetico</option>
                                    <option>Depresion</option>
                                    <option>Esquizofrenia</option>
                                    <option>Hipretenso</option>
                                    <option>Hipotenso</option>


                                </datalist>
                            </div>
                        </div>
						<div class="col-md-4">
                            <div class="form-group">
                                <label for="antecedente_fa" class="text-dark font-weight-bold">Antecedentes Familiares</label>
                                <input type="text" placeholder="Ingrese Antedentes Familiares del Paciente" name="antecedente_fa" id="antecedente_fa" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="observacion" class="text-dark font-weight-bold">Responsable del Censo</label>
                                <input list="cen" placeholder="Ingrese Ingrese la comunidad que lo censo " name="observacion" id="observacion" class="form-control">
                                <datalist id= "cen">
                                    
                                    <option>Barrio Libertad</option>
                                    <option>Fudacion Divino Niño</option>
                                    <option>Sector La (L)</option>
                                    <option>Sector (Las Morochas)</option>
                                    <option>Sector (Las Morochas 2)</option>
                                    <option>Sector (Los Tiburones)</option>
                                    <option>sector (La Victoria)</option>

                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
						     <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#studentaddmodal">
									Ingrese Paciente
									</button>
                            <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                            <input type="button" value="Nuevo" class="btn btn-success" id="btnNuevo" onclick="limpiar()">
                        </div>
                    </div>
                </form>
            </div>
			<!-- Modal -->
 <div class="modal fade" id="studentaddmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Registro de Paciente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="insertcenso.php" method="POST">

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
                        <input type="text" class="form-control" placeholder="Ingrese Correo Electrónico" name="correo" id="correo">
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
                                     <option value = "Paraute">
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
                        <div class="col-md-3">
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
                                <input list="est"  placeholder="Ingrese Estado" name="estado" id="estado" class="form-control">
                                <datalist id="est">
                                    <option value = "Zulia">
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
                             <a href="./excel_jornada.php" class="btn btn-success"><i class='fas fa-file-excel'></i> </a>
                            <tr>
                                <th>Cedula</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Edad</th>
								<th>Genero</th>
                                <th>Telefono</th>
                                <th>Motivo de  Consulta</th>
                                <th>Medicacion</th>
                                <th>Habitos del Paciente</th>
                                <th>Antecedentes Personales</th>
                                
                                <th>Responsable del Censo</th>
                                <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "../conexion.php";

                            $query = mysqli_query($conexion, "SELECT * FROM jornada");
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                                while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                               
                                        <td><?php echo $data['cedula']; ?></td>
                                        <td><?php echo $data['nombre']; ?></td>
                                        <td><?php echo $data['apellido']; ?></td>
                                        <td><?php echo $data['edad']; ?></td>
										<td><?php echo $data['genero']; ?></td>
                                        <td><?php echo $data['telefono']; ?></td>
                                        <td><?php echo $data['Causa_consulta']; ?></td>
                                        <td><?php echo $data['medicacion']; ?></td>
                                        <td><?php echo $data['habitos']; ?></td>
                                        <td><?php echo $data['antecedente_per']; ?></td>
                                        
                                        <td><?php echo $data['observacion']; ?></td>
                                        <td>
                                            <form action="eliminar_jornada.php?id=<?php echo $data['id']; ?>" method="post" class="confirmar d-inline">
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