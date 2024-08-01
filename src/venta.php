<?php
session_start();
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "nueva_factura";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
include_once "includes/header.php";
?>
<div class="row" style="background: #0000004a;border-radius: 25px;">
    <div class="col-lg-12">

        <div class="card" style="border-radius: 25px;">
            <div class="card-header bg-primary text-white text-center"
                style="border-top-left-radius: 25px;border-top-right-radius: 25px;">
                Datos del cliente
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="hidden" id="idcliente"  name="idcliente" required>
                                <label>Cedula</label>
                                <input type="text" name="ced_cliente" id="ced_cliente" class="form-control"
                                    placeholder="Ingrese Numero de Cedula" onkeyup="focusNextElement( event )" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" name="nom_cliente" id="nom_cliente" class="form-control" disabled
                                    required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Apellido</label>
                                <input type="text" name="ape_cliente" id="ape_cliente" class="form-control" disabled
                                    required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Edad</label>
                                <input type="text" name="eda_cliente" id="eda_cliente" class="form-control" disabled
                                    required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" name="tel_cliente" id="tel_cliente" class="form-control" disabled
                                    required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Dirreción</label>
                                <input type="text" name="dir_cliente" id="dir_cliente" class="form-control" disabled
                                    required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Parroquia</label>
                                <input type="text" name="par_cliente" id="par_cliente" class="form-control" disabled
                                    required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Municipio</label>
                                <input type="text" name="mun_cliente" id="mun_cliente" class="form-control" disabled
                                    required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Estado</label>
                                <input type="text" name="est_cliente" id="est_cliente" class="form-control" disabled
                                    required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <button type="button" class="btn btn-warning" data-toggle="modal"
                                data-target="#studentaddmodal">
                                Ingrese Paciente
                            </button>
                        </div>
                        <a type="button" href="itinerario.php" class="btn btn-success">
                            Itinerario
                        </a>
                    </div>
            </div>
            </form>
        </div>
    </div>
    <div class="card" style="border-radius: 25px;">
        <div class="card-header bg-primary text-white text-center"
            style="border-top-left-radius: 25px;border-top-right-radius: 25px;">
            Buscar Servicios
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="servicio">Código o Nombre</label>
                        <input id="servicio" class="form-control" type="text" name="servicio"
                            placeholder="Ingresa el código o nombre">
                        <input id="id" type="hidden" name="id">
                    </div>
                </div>
                <div class="col-md-3">
                                    <div class="form-group">
                                         <label for="doctor">Especialistas FDN</label>
                                        <select id="doctor" class="form-control" name="doctor" placeholder="Especialidad" required>
                                            <option>Seleccione un Especialista</option>
                                        </select>
                                    </div>
                                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="especialidad">Especialidad</label>
                        <input id="especialidad" class="form-control" type="text" name="especialidad" placeholder="Especialidad">
                    </div>
                </div>
                <div class="col-lg-1">
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input id="cantidad" class="form-control" type="text" name="cantidad" placeholder="Cantidad"
                            onkeyup="calcularPrecio(event)">
                    </div>
                </div>
                <div class="col-lg-1">
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input id="precio" class="form-control" type="number" step="0.01" name="precio" placeholder="precio">
                    </div>
                </div>
                <div class="col-lg-1">
                    <div class="form-group">
                        <label for="sub_total">Sub Total</label>
                        <input id="sub_total" class="form-control" type="text" name="sub_total" placeholder="Sub Total">
                    </div>
                </div>
                <div class="col-lg-1">
                    <div class="form-group">
                        <label for="sub_totalbs">Sub Total bs</label>
                        <input id="sub_totalbs" class="form-control" type="text" name="sub_totalbs"
                            placeholder="Sub Total bs" disabled>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover" id="tblDetalle">
            <thead class="thead-dark">
                <tr>
                    <th>Id</th>
                    <th>Descripción</th>
                    <th>Especialidad</th>
                    <th>Especialista FDN</th>
                    <th>Cantidad</th>
                    <th>BCV</th>
                    <th>Desc</th>
                    <th>Total ($)</th>
                    <th>Total (BS)</th>
                    <th>Accion</th>
                </tr>
            </thead>

            <tbody id="detalle_venta" style="background: rgba(255, 255, 255, 0.70)">

            </tbody>
            <tfoot style="background: rgba(255, 255, 255, 0.70);">
                <tr class="font-weight-bold">
                    <td colspan='10' id="total_pagar">Total Pagar</td>
            </tfoot>
        </table>
        <div class="card" style="border-radius: 25px;">
            <div class="card-header bg-primary text-white text-center"
                style="border-top-left-radius: 25px;border-top-right-radius: 25px;">
                Modalidad de Pago
            </div>
            <form id="pago" class="card-body" onsubmit="crearPago(event)">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="metodo_pago">Metodos de Pago</label>
                            <input id="metodo_pago" class="form-control" type="text" name="metodo_pago"
                                placeholder="Ingresa el Metodo de Pago" required>
                            <input id="id" type="hidden" name="id">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="referencia">Referencia</label>
                            <input id="referencia" class="form-control" type="text" name="referencia"
                                placeholder="Referencia">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="bolivares">Bolivares</label>
                            <input id="bolivares" class="form-control" type="text" name="bolivares"
                                placeholder="Bolivares" onkeyup="calcularPreciodiferido(event)">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="dolares">Dolares</label>
                            <input id="dolares" class="form-control" type="text" name="dolares" placeholder="Dolares">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="obser">Concepto</label>
                            <input id="obser" class="form-control" type="text" name="obser"
                                placeholder="Ingrese el Concepto de pago">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <button class="btn btn-primary">
                            Enviar
                        </button>
                    </div>
            </form>

        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" id="tblDetalle_pago">
            <thead class="thead-dark">
                <tr>
                    <th>Met. Pago</th>
                    <th>Referencia</th>
                    <th>Bolivares (BS)</th>
                    <th>Dolares ($)</th>
                    <th>Observacion</th>
                    <th>Accion</th>
                </tr>
            </thead>

            <tbody id="detalle_pago" style="background: rgba(255, 255, 255, 0.70);">

            </tbody>
        </table>
    </div>
</div>
<div class="col-md-6">
    <a href="#" class="btn btn-primary" id="btn_generar"><i class="fas fa-save"></i> Generar Venta</a>
</div>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="studentaddmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Registro De Paciente </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="insert.php" method="POST">

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
                                <label for="nombre" class="text-dark font-weight-bold">Nombre</label>
                                <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nombre" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                        <div class="form-group">
                                <label for="apellido" class="text-dark font-weight-bold">Apellido</label>
                                <input type="text" placeholder="Ingrese Apellido" name="apellido" id="apellido" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                        <div class="form-group">
                                <label for="edad" class="text-dark font-weight-bold">Edad</label>
                                <input type="text" placeholder="Ingrese Edad" name="edad" id="edad" class="form-control">
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
                                <label for="direccion" class="text-dark font-weight-bold">Dirección</label>
                                <input type="text" placeholder="Ingrese Direccion" name="direccion" id="direccion" class="form-control">
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
<script>
function validationSelect() {
    var select = document.getElementById("doctor");
    var seleccionado = select.value;
    if (seleccionado === "") {
        alert("Por favor, selecciona una opción");
        return false; // Evita que el formulario se envíe
    }
    return true; // Permite que el formulario se envíe si se ha seleccionado una opción
}
</script>
    
<?php include_once "includes/footero.php"; ?>