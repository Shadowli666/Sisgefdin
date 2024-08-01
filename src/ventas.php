<?php
session_start();
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "nueva_salida";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
include_once "includes/header.php";
?>
<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <div class="card-header bg-primary text-white text-center">
            <h4 class="text-center">Datos del Cliente</h4>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="hidden" id="idcliente" value="1" name="idcliente" required>
                                <label>Cedula</label>
                                <input type="text" name="ced_cliente" id="ced_cliente" class="form-control"  placeholder="Ingrese Numero de Cedula"   required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" name="nom_cliente" id="nom_cliente" class="form-control"  disabled required>
                            </div>
                        </div>
                            <div class="col-md-3">
                               <div class="form-group">
                                <label>Apellido</label>
                                <input type="text" name="ape_cliente" id="ape_cliente" class="form-control"  disabled required>
                            </div>
                     </div>
                          <div class="col-md-3">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="number" name="tel_cliente" id="tel_cliente" class="form-control" disabled required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Dirreción</label>
                                <input type="text" name="dir_cliente" id="dir_cliente" class="form-control" disabled required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Parroquia</label>
                                <input type="text" name="par_cliente" id="par_cliente" class="form-control" disabled required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Municipio</label>
                                <input type="text" name="mun_cliente" id="mun_cliente" class="form-control" disabled required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Estado</label>
                                <input type="text" name="est_cliente" id="est_cliente" class="form-control" disabled required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                         <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#studentaddmodal">
                                    Ingrese Paciente
                                    </button>
                                    </div>
                       </div>
                    </div>

                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                Buscar Medicamentos
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label for="producto">Código o Nombre</label>
                            <input id="producto" class="form-control" type="text"name="producto" placeholder="Ingresa el código o nombre">
                            <input id="id" type="hidden" name="id">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input id="cantidad" class="form-control" type="text" name="cantidad" placeholder="Cantidad" onkeyup="calcularPrecio(event)">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="precio"></label>
                            <input id="precio" class="form-control" style= "display: none" type="text" name="precio" placeholder="precio" >
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="sub_total"></label>
                            <input id="sub_total" class="form-control" style= "display: none" type="text" name="sub_total" placeholder="Sub Total" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- Modal -->
 <div class="modal fade" id="studentaddmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Registro De Paciente </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="insertsalida.php" method="POST">

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
  <div class="table-responsive">
            <table class="table table-hover" id="tblDetalle">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody id="detalle_venta">

                </tbody>
                <tfoot>
                    <tr class="font-weight-bold">
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
    <div class="col-md-6">
        <a href="#" class="btn btn-primary" id="btn_generar"><i class="fas fa-save"></i> Generar Salida</a>
    </div>

</div>
<?php include_once "includes/footer.php"; ?>