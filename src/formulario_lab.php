<?php
session_start();
require("../conexion.php");

// Obtén el ID del usuario de la sesión
$id_user = $_SESSION['idUser'] ?? null;
$permiso = "consulta_cita";

// Prepara la consulta SQL para verificar los permisos
$sql = "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = ? AND p.nombre = ?";
$stmt = $conexion->prepare($sql);

if ($stmt === false) {
    error_log("Error preparando la consulta: " . $conexion->error);
    die("Error preparando la consulta.");
}

$stmt->bind_param("is", $id_user, $permiso);
$stmt->execute();
$stmt->store_result();

// Verifica si el usuario tiene el permiso
if ($stmt->num_rows === 0 && $id_user != 1) {
    // Registra el intento de acceso sin permiso
    error_log("Access denied for user $id_user to permiso $permiso");
    header('Location: permisos.php');
    exit();
}

$stmt->close();

include_once "includes/header.php";

// Verifica si se ha recibido el parámetro idcita
if (isset($_GET['idcita'])) {
    $id_itinerario = $_GET['idcita'];
} else {
    // Maneja el caso en que idcita no está definido
    echo "El parámetro idcita no está definido en la solicitud GET.";
    exit(); // Detiene la ejecución del script si no se ha definido idcita
}

$consulta_id_cliente = "SELECT id_cliente FROM itinerario WHERE idcita = ?";
$stmt = $conexion->prepare($consulta_id_cliente);
$stmt->bind_param("i", $id_itinerario);
$stmt->execute();
$stmt->bind_result($id_cliente);
$stmt->fetch();
$stmt->close();

if ($id_cliente) {
    $consulta_datos_cliente = "SELECT c.cedula, c.nombre, c.apellido, c.telefono FROM cliente c JOIN itinerario i ON c.idcliente = i.id_cliente WHERE i.idcita = ?";
    $stmt_cliente = $conexion->prepare($consulta_datos_cliente);
    $stmt_cliente->bind_param("i", $id_itinerario);
    $stmt_cliente->execute();
    $stmt_cliente->bind_result($cedula, $nombre, $apellido, $telefono);
    $stmt_cliente->fetch();
    $stmt_cliente->close();
} else {
    echo "No se encontró el ID de cliente asociado al itinerario.";
    exit();
}

$conexion->close();
?>

<div class="card">
    <div class="card-header bg-primary text-white text-center">
        DATOS DEL PACIENTE
    </div>
    <div class="card-body">
        <form method="post">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="hidden" id="idcliente" value="<?php echo $id_cliente; ?>" name="idcliente" required>
                        <label>Cedula</label>
                        <input type="text" name="ced_cliente" id="ced_cliente" class="form-control" placeholder="Ingrese Numero de Cedula" value="<?php echo $cedula; ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nom_cliente" id="nom_cliente" class="form-control" value="<?php echo $nombre; ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Apellido</label>
                        <input type="text" name="ape_cliente" id="ape_cliente" class="form-control" value="<?php echo $apellido; ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="number" name="tel_cliente" id="tel_cliente" class="form-control" value="<?php echo $telefono; ?>">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php

include('configs.php');
$idcita = htmlspecialchars($_GET["idcita"]);
$sqlTrabajadores = ("SELECT i.*, c.idcliente, c.cedula,c.nombre, c.apellido, c.telefono,c.edad FROM itinerario i INNER JOIN cliente c ON i.id_cliente = c.idcliente WHERE i.idcita = '$idcita'");
$query = mysqli_query($con, $sqlTrabajadores);

foreach ($query as $servicios) {
    $descripcion = explode(',', $servicios['descripcion']);
    $data_servicios = '"' . implode('", "', $descripcion) . '"';
    $query_servicios = "SELECT s.* FROM servicios s WHERE s.descripcion IN ($data_servicios) GROUP BY categoria";
    $resultado_servicios = mysqli_query($con, $query_servicios);

    if (!$resultado_servicios) {
        die("Error en la consulta SQL: " . mysqli_error($con));
    }

    foreach ($resultado_servicios as $form_section) {

        if ($form_section['categoria'] == 'HEMATOLOGIA') {
?>
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    HEMATOLOGIA
                </div>
                <form onsubmit="crearHema(event)">
                    <div class="card-body">
                        <div class="row">
                        <input type="hidden" name="idcita" id="idcita" value="<?php echo $idcita;?>">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="hemoglobina">HEMOGLOBINA</label>
                                    <input id="hemoglobina" class="form-control" type="text" name="hemoglobina" placeholder="">
                                    <input id="id" type="hidden" name="id">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="hematocritos">HEMATROCITOS</label>
                                    <input id="hematocritos" class="form-control" type="text" name="hematocritos" placeholder="">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="cuentas_blancas">CUENTAS BLANCAS</label>
                                    <input id="cuentas_blancas" class="form-control" type="text" name="cuentas_blancas" placeholder="">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="plaquetas">PLAQUETAS</label>
                                    <input id="plaquetas" class="form-control" type="text" name="plaquetas" placeholder="">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="vsg">VSG</label>
                                    <input id="vsg" class="form-control" type="text" name="vsg" placeholder="">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <button class="btn btn-primary">
                                    Enviar
                                </button>
                            </div>
                        </div>
                </form>
            </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover" id="tblDetalle">
                    <thead class="thead-dark">
                        <tr>
                            <th>Id</th>
                            <th>Hemoglobina</th>
                            <th>Hematrocitos</th>
                            <th>Cuentas Blancas</th>
                            <th>Plaquetas</th>
                            <th>VSG</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody id="detalle_hematologia" style="background: rgba(255, 255, 255, 0.70)">

                    </tbody>
                </table>

            </div>



            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    FORMULA LEUCOCITARIA
                </div>
                <form onsubmit="crearLeuco(event)">
                    <div class="card-body">
                        <div class="row" style="text-align:center;">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="idcita" id="idcita" value="<?php echo $idcita;?>">
                                    <label for="seg">SEG</label>
                                    <input id="seg" class="form-control amt" type="text" name="seg">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="linf">LINF</label>
                                    <input id="linf" class="form-control amt" type="text" name="linf">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="eosin">EOSIN</label>
                                    <input id="eosin" class="form-control amt" type="text" name="eosin">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="monoc">MONOC</label>
                                    <input id="monoc" class="form-control amt" type="text" name="monoc">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="basof">BASOF</label>
                                    <input id="basof" class="form-control amt" type="text" name="basof">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="otros">OTROS</label>
                                    <input id="otros" class="form-control amt" type="text" name="otros">

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="total">TOTAL</label>
                                    <input id="total" action="post" class="form-control" type="text" name="total">

                                </div>
                            </div>

                            <div class="col-lg-4">
                                <button class="btn btn-primary">
                                    Enviar
                                </button>
                            </div>

                        </div>
                    </div>

            </div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover" id="tblDetalle">
                    <thead class="thead-dark">
                        <tr>
                            <th>Id</th>
                            <th>SEG</th>
                            <th>LINF</th>
                            <th>EOSIN</th>
                            <th>MONOC</th>
                            <th>BASOF</th>
                            <th>OTROS</th>
                            <th>TOTAL</th>
                            <th>Accion</th>
                        </tr>
                    </thead>

                    <tbody id="detalle_leuco" style="background: rgba(255, 255, 255, 0.70)">

                    </tbody>
                </table>

            <?php
        } elseif ($form_section['categoria'] == 'RETICULOCITOS') {
            ?>
                <div class="card">
                    <div class="col-12 card-header bg-primary text-white text-center">
                        HEMATOLOGIA
                    </div>
                    <form onsubmit="crearReticulocitos(event)">
                        <div class="card-body">
                            <div class="row">


                                <div class="col-md-4">
                                    <div class="form-group"> <input id="id" type="hidden" name="id">
                                        <label>RETICULOCITOS</label>
                                        <input id="reticulocitos" class="form-control" type="text" name="reticulocitos" value="RETICULOCITOS">


                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>VALOR UNIDAD</label>
                                        <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>VALOR REFERNCIAL</label>
                                        <input id="valor_referencial" class="form-control" type="text" name="valor_referencial" value="VALOR REFERENCIAL   ADULTOS  0.5 - 2.0 %">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <button class="btn btn-primary">
                                        Enviar
                                    </button>
                                </div>
                            </div>
                        </div>
                </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover" id="tblDetalle">
                        <thead class="thead-dark">
                            <tr>
                                <th>Id</th>
                                <th>EXAMEN</th>
                                <th>VALOR UNIDAD</th>
                                <th>VALOR REFERENCIAL</th>
                                <th>ACCION</th>
                            </tr>
                        </thead>

                        <tbody id="detalle_reticulocitos" style="background: rgba(255, 255, 255, 0.70)">

                        </tbody>
                    </table>

                <?php
            } elseif ($form_section['categoria'] == 'TIEMPOS') {
                ?>
                    <div class="card">
                        <div class="col-12 card-header bg-primary text-white text-center">
                            PERFIL DE COAGULACION
                        </div>
                        <form onsubmit="crearTiempos(event)">
                            <div class="card-body">
                                <div class="row">


                                    <div class="col-md-4">
                                        <div class="form-group"> <input id="id" type="hidden" name="id">
                                            <label>TIEMPO DE PROTROMBINA:</label>
                                            <input id="tp" class="form-control" type="text" name="tp">


                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>TIEMPO PARCIAL DE TROMBOPLASTINA:</label>
                                            <input id="tpt" class="form-control" type="text" name="tpt">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>INR</label>
                                            <input id="inr" class="form-control" type="text" name="inr">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>FIBRINOGENO</label>
                                            <input id="fibrinogeno" class="form-control" type="text" name="fibrinogeno">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <button class="btn btn-primary">
                                            Enviar
                                        </button>
                                    </div>
                                </div>
                            </div>
                    </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover" id="tblDetalle">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Id</th>
                                    <th>TP</th>
                                    <th>TPT</th>
                                    <th>INR</th>
                                    <th>FIBRINOGENO</th>
                                    <th>ACCION</th>
                                </tr>
                            </thead>

                            <tbody id="detalle_tiempos" style="background: rgba(255, 255, 255, 0.70)">

                            </tbody>
                        </table>

                    <?php
                } elseif ($form_section['categoria'] == 'PRUEBA ESPECIAL') {
                    ?>
                        <div class="card">
                            <div class="col-12 card-header bg-primary text-white text-center">
                                PRUEBA ESPECIAL
                            </div>
                            <form onsubmit="crearPrueba(event)">
                                <div class="card-body">
                                    <div class="row">


                                        <div class="col-md-3">
                                            <div class="form-group"> <input id="id" type="hidden" name="id">
                                                <label>EXAMEN</label>
                                                <input id="especial" class="form-control" type="text" name="especial">


                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label>VALOR UNIDAD</label>
                                                <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label>VALOR REFERNCIAL</label>
                                                <input id="valor_referencial" class="form-control" type="text" name="valor_referencial">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label>VALOR REFERENCIAL</label>
                                                <input id="valor_referencialprueba" class="form-control" type="text" name="valor_referencialprueba">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <button class="btn btn-primary">
                                                Enviar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-hover" id="tblDetalle">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Id</th>
                                        <th>EXAMEN</th>
                                        <th>VALOR UNIDAD</th>
                                        <th>VALOR REFERENCIAL</th>
                                        <th>VALOR REFERENCIAL</th>
                                        <th>ACCION</th>
                                    </tr>
                                </thead>

                                <tbody id="detalle_prueba" style="background: rgba(255, 255, 255, 0.70)">

                                </tbody>
                            </table>

                        <?php
                    } elseif ($form_section['categoria'] == 'QUIMICA') {
                        ?>
                            <div class="card">
                                <div class="col-12 card-header bg-primary text-white text-center">
                                    QUIMICA
                                </div>
                                <form onsubmit="crearQuimi(event)">
                                    <div class="card-body">
                                        <div class="row">


                                            <div class="col-md-3">
                                                <div class="form-group"> <input id="id" type="hidden" name="id">
                                                    <label>EXAMEN</label>
                                                    <input id="examen" class="form-control" type="text" name="examen">


                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label>VALOR UNIDAD</label>
                                                    <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label>VALOR REFERNCIAL</label>
                                                    <input id="valor_referencial1" class="form-control" type="text" name="valor_referencial1">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label>VALOR REFERENCIAL</label>
                                                    <input id="valor_referencial2" class="form-control" type="text" name="valor_referencial2">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <button class="btn btn-primary">
                                                    Enviar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-hover" id="tblDetalle">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Id</th>
                                            <th>EXAMEN</th>
                                            <th>VALOR UNIDAD</th>
                                            <th>VALOR REFERENCIAL</th>
                                            <th>VALOR REFERENCIAL</th>
                                            <th>ACCION</th>
                                        </tr>
                                    </thead>

                                    <tbody id="detalle_quimica" style="background: rgba(255, 255, 255, 0.70)">

                                    </tbody>
                                </table>

                            <?php
                        } elseif ($form_section['categoria'] == 'ORINA') {
                            ?>
                                <div class="card">
                                    <div class="card-header bg-primary text-white text-center">
                                        EXAMEN DE ORINA
                                    </div>
                                    <form onsubmit="crearOrina(event)">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="aspecto">ASPECTO</label>
                                                        <select id="aspecto" class="form-control" name="aspecto" value="Lig.Turbio">
                                                            <option>Lig. Turbio</option>
                                                            <option>Turbio</option>
                                                            <option>Purulento</option>
                                                        </select>
                                                        <input id="id" type="hidden" name="id">

                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="densidad">DENSIDAD</label>
                                                        <select id="densidad" class="form-control" name="densidad" value="1.000">
                                                            <option>1.000</option>
                                                            <option>1.005</option>
                                                            <option>1.010</option>
                                                            <option>1.015</option>
                                                            <option>1.020</option>
                                                            <option>1.025</option>
                                                            <option>1.030</option>
                                                        </select>
                                                        <input id="id" type="hidden" name="id">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="ph">P.H</label>
                                                        <select id="ph" class="form-control" name="ph" value="6.0">
                                                            <option>5.0</option>
                                                            <option>6.0</option>
                                                            <option>6.5</option>
                                                            <option>7.0</option>
                                                            <option>7.5</option>
                                                            <option>8.0</option>
                                                            <option>8.5</option>
                                                            <option>9.0</option>
                                                        </select>
                                                        <input id="id" type="hidden" name="id">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="olor">OLOR</label>
                                                        <select id="olor" class="form-control" name="olor" value="S.G">
                                                            <option>S.G.</option>
                                                            <option>Amoniacal.</option>

                                                        </select>
                                                        <input id="id" type="hidden" name="id">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="color">COLOR</label>
                                                        <select id="color" class="form-control" name="color" value="Amarillo">
                                                            <option>Amarillo.</option>
                                                            <option>Amarillo intenso.</option>
                                                            <option>Ambar.</option>
                                                            <option>Rojiza.</option>
                                                            <option>Medicamentoza.</option>

                                                        </select>
                                                        <input id="id" type="hidden" name="id">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="nitritos">NITRITOS</label>
                                                        <select id="nitritos" class="form-control" name="nitritos" value="Neg.">
                                                            <option>Neg.</option>
                                                            <option>Trazas.</option>
                                                            <option>Positivo.</option>

                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="proteinas">PROTEINAS</label>
                                                        <select id="proteinas" class="form-control" name="proteinas" value="Neg.">
                                                            <option>Neg.</option>
                                                            <option>Trazas.</option>
                                                            <option>Positivo +</option>
                                                            <option>Positivo ++</option>
                                                            <option>Positivo +++</option>

                                                        </select>

                                                        <input id="id" type="hidden" name="id">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="glucosa">GLUCOSA</label>
                                                        <select id="glucosa" class="form-control" name="glucosa" value="Neg.">
                                                            <option>Neg.</option>
                                                            <option>Trazas.</option>
                                                            <option>Positivo +</option>
                                                            <option>Positivo ++</option>
                                                            <option>Positivo +++</option>

                                                        </select>
                                                        <input id="id" type="hidden" name="id">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="cetonas">CETONAS</label>
                                                        <select id="cetonas" class="form-control" name="cetonas" value="Neg.">
                                                            <option>Neg.</option>
                                                            <option>Trazas.</option>
                                                            <option>Positivo.</option>

                                                        </select>
                                                        <input id="id" type="hidden" name="id">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="urobilinogeno">UROBILINOGENO</label>
                                                        <select id="urobilinogeno" class="form-control" name="urobilinogeno" value="Neg.">
                                                            <option>Neg.</option>
                                                            <option>Trazas.</option>
                                                            <option>Positivo +</option>
                                                            <option>Positivo ++</option>
                                                            <option>Positivo +++</option>

                                                        </select>
                                                        <input id="id" type="hidden" name="id">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="bilirrubina">BILIRRUBINA</label>
                                                        <select id="bilirrubina" class="form-control" name="bilirrubina" value="Neg.">
                                                            <option>Neg.</option>
                                                            <option>Trazas.</option>
                                                            <option>Positivo +</option>
                                                            <option>Positivo ++</option>
                                                            <option>Positivo +++</option>

                                                        </select>
                                                        <input id="id" type="hidden" name="id">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="hemoglobina">HEMOGLOBINA</label>
                                                        <select id="hemoglobina" class="form-control" name="hemoglobina" value="Neg.">
                                                            <option>Neg.</option>
                                                            <option>Trazas.</option>
                                                            <option>Positivo +</option>
                                                            <option>Positivo ++</option>
                                                            <option>Positivo +++</option>

                                                        </select>
                                                        <input id="id" type="hidden" name="id">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="pigmen_biliares">PIGMENTOS BILIARES</label>
                                                        <select id="pigmen_biliares" class="form-control" name="pigmen_biliares" value="Neg.">
                                                            <option>Neg.</option>
                                                            <option>Trazas.</option>
                                                            <option>Positivo +</option>
                                                            <option>Positivo ++</option>
                                                            <option>Positivo +++</option>

                                                        </select>
                                                        <input id="id" type="hidden" name="id">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="sales_biliares">SALES BILIARES</label>
                                                        <select id="sales_biliares" class="form-control" name="sales_biliares" value="Neg.">
                                                            <option>Neg.</option>
                                                            <option>Trazas.</option>
                                                            <option>Positivo +</option>
                                                            <option>Positivo ++</option>
                                                            <option>Positivo +++</option>

                                                        </select>
                                                        <input id="id" type="hidden" name="id">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <button class="btn btn-primary">
                                                        Enviar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                </form>
                                <div class="table-responsive">
                                    <table class="table table-hover" id="tblDetalle">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Id</th>
                                                <th>ASP.</th>
                                                <th>DEN.</th>
                                                <th>PH.</th>
                                                <th>OLOR.</th>
                                                <th>COL.</th>
                                                <th>NIT.</th>
                                                <th>PRO.</th>
                                                <th>GLU.</th>
                                                <th>CET.</th>
                                                <th>UROB.</th>
                                                <th>BILI.</th>
                                                <th>HEMO.</th>
                                                <th>PIG B.</th>
                                                <th>SAL B.</th>
                                                <th>ACCION</th>
                                            </tr>
                                        </thead>

                                        <tbody id="detalle_orina" style="background: rgba(255, 255, 255, 0.70)">

                                        </tbody>
                                    </table>
                                    <div class="card">
                                        <div class="card-header bg-primary text-white text-center">
                                            EXAMEN DE MICROSCOPIO
                                        </div>
                                        <form onsubmit="crearExamisc(event)">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="celulas_ep_planas">CELULAS EP PLANAS</label>
                                                            <select id="celulas_ep_planas" class="form-control" name="celulas_ep_planas">
                                                                <option>ESCASAS</option>
                                                                <option>MODERADAS</option>
                                                                <option>ABUNDANTES</option>

                                                            </select>
                                                            <input id="id" type="hidden" name="id">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="bacterias">BACTERIAS</label>
                                                            <select id="bacterias" class="form-control" name="bacterias">
                                                                <option>ESCASAS</option>
                                                                <option>MODERADAS</option>
                                                                <option>ABUNDANTES</option>
                                                                <input id="id" type="hidden" name="id">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="leucocitos">LEUCOCITOS</label>
                                                            <input id="leucocitos" class="form-control" type="text" name="leucocitos">
                                                            <input id="id" type="hidden" name="id">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="hematies">HEMATIES</label>
                                                            <input id="hematies" class="form-control" type="text" name="hematies">
                                                            <input id="id" type="hidden" name="id">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="mucina">MUCINA</label>
                                                            <input id="mucina" class="form-control" type="text" name="mucina">
                                                            <input id="id" type="hidden" name="id">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="celulas_renales">CELULAS RENALES</label>
                                                            <input id="celulas_renales" class="form-control" type="text" name="celulas_renales">
                                                            <input id="id" type="hidden" name="id">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="cristales">CRISTALES</label>
                                                            <input id="cristales" class="form-control" type="text" name="cristales">
                                                            <input id="id" type="hidden" name="id">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="otros">OTROS</label>
                                                            <input id="otros" class="form-control" type="text" name="otros">
                                                            <input id="id" type="hidden" name="id">
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-primary">
                                                        Enviar
                                                    </button>
                                                </div>
                                            </div>
                                    </div>
                                    </form>
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="tblDetalle">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Id</th>
                                                    <th>CELULAS EP PLANAS.</th>
                                                    <th>BACTERIAS.</th>
                                                    <th>LEUCOCITOS.</th>
                                                    <th>HEMATIES.</th>
                                                    <th>MUCINA.</th>
                                                    <th>CELULAS RENALES.</th>
                                                    <th>CRISTALES.</th>
                                                    <th>OTROS.</th>
                                                    <th>ACCION</th>
                                                </tr>
                                            </thead>

                                            <tbody id="detalle_orinamisc" style="background: rgba(255, 255, 255, 0.70)">

                                            </tbody>
                                        </table>
                                    <?php
                                } elseif ($form_section['categoria'] == 'HECES') {
                                    ?>
                                        <div class="card">
                                            <div class="card-header bg-primary text-white text-center">
                                                EXAMEN DE HECES
                                            </div>
                                            <form onsubmit="crearHeces(event)">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="aspecto">ASPECTO</label>
                                                                <select id="aspecto" class="form-control" name="aspecto" value="HETEROGENEO">
                                                                    <option>HETEROGENEO</option>
                                                                    <option>HOMOGENEO</option>

                                                                </select>
                                                                <input id="id" type="hidden" name="id">

                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="color">COLOR</label>
                                                                <select id="color" class="form-control" name="color" value="MARRON">
                                                                    <option>MARRON</option>
                                                                    <option>AMARILLA</option>
                                                                    <option>NEGRO</option>
                                                                    <option>ROJO</option>
                                                                    <option>VERDE</option>
                                                                    <option>BLANCA</option>
                                                                </select>
                                                                <input id="id" type="hidden" name="id">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="olor">OLOR</label>
                                                                <select id="olor" class="form-control" name="olor" value="FETIDA">
                                                                    <option>FETIDA</option>
                                                                    <option>FECAL</option>
                                                                </select>
                                                                <input id="id" type="hidden" name="id">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="consistencia">CONSISTENCIA</label>
                                                                <select id="consistencia" class="form-control" name="consistencia" value="BLANDA">
                                                                    <option>BLANDA</option>
                                                                    <option>DURA</option>
                                                                    <option>DIARREICA</option>
                                                                    <option>LIQUIDA</option>
                                                                </select>
                                                                <input id="id" type="hidden" name="id">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="reaccion">REACCION</label>
                                                                <select id="reaccion" class="form-control" name="reaccion" value="ACIDA">
                                                                    <option>ACIDA</option>
                                                                    <option>ALCALINA</option>


                                                                </select>

                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="moco">MOCO</label>
                                                                <select id="moco" class="form-control" name="moco" value="AUSENTE">
                                                                    <option>AUSENTE</option>
                                                                    <option>PRESENTE</option>

                                                                </select>

                                                                <input id="id" type="hidden" name="id">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="sangre">SANGRE</label>
                                                                <select id="sangre" class="form-control" name="sangre" value="AUSENTE">
                                                                    <option>AUSENTE</option>
                                                                    <option>PRESENTE</option>
                                                                </select>
                                                                <input id="id" type="hidden" name="id">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="ra">R.A</label>
                                                                <select id="ra" class="form-control" name="ra" value="PRESENTE">
                                                                    <option>AUSENTE</option>
                                                                    <option>PRESENTE</option>

                                                                </select>
                                                                <input id="id" type="hidden" name="id">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="ph">P.H</label>
                                                                <input id="ph" class="form-control" type="text" name="ph">

                                                                <input id="id" type="hidden" name="id">
                                                            </div>
                                                        </div>



                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="azucares">AZUCARES REDUCTORES</label>
                                                                <input id="azucares" class="form-control" type="text" name="azucares">
                                                                <input id="id" type="hidden" name="id">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4">
                                                            <button class="btn btn-primary">
                                                                Enviar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        </form>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="tblDetalle">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>ASP.</th>
                                                        <th>COL.</th>
                                                        <th>OLOR.</th>
                                                        <th>CONS.</th>
                                                        <th>REAC.</th>
                                                        <th>MOCO.</th>
                                                        <th>SANGRE.</th>
                                                        <th>R.A.</th>
                                                        <th>PH.</th>
                                                        <th>AZUCARES REDUC.</th>
                                                        <th>ACCION</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="detalle_heces" style="background: rgba(255, 255, 255, 0.70)">

                                                </tbody>
                                            </table>
                                            <div class="card">
                                                <div class="card-header bg-primary text-white text-center">
                                                    OBSERVACION MICROSCOPICA
                                                </div>
                                                <form onsubmit="crearHecesmisc(event)">
                                                    <div class="card-body">
                                                        <div class="row">


                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="protozoarios">PROTOZOARIOS</label>
                                                                    <input id="protozoarios" class="form-control" type="text" name="protozoarios" value=" No se observó forma evolutiva parasitaria">
                                                                    <input id="id" type="hidden" name="id">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="helmintos">HELMINTOS</label>
                                                                    <input id="helmintos" class="form-control" type="text" name="helmintos" value=" No se observó forma evolutiva parasitaria">
                                                                    <input id="id" type="hidden" name="id">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="otros">OTROS</label>
                                                                    <input id="otros" class="form-control" type="text" name="otros">
                                                                    <input id="id" type="hidden" name="id">
                                                                </div>
                                                            </div>
                                                            <button class="btn btn-primary">
                                                                Enviar
                                                            </button>
                                                        </div>
                                                    </div>
                                            </div>
                                            </form>
                                            <div class="table-responsive">
                                                <table class="table table-hover" id="tblDetalle">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>Id</th>
                                                            <th>PROTOZOARIOS.</th>
                                                            <th>HELMINTOS.</th>
                                                            <th>OTROS.</th>
                                                            <th>ACCION</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="detalle_hecesmisc" style="background: rgba(255, 255, 255, 0.70)">

                                                    </tbody>
                                                </table>

                                            <?php
                                        } elseif ($form_section['categoria'] == 'INMUNOSEROLOGIA') {
                                            ?>
                                                <div class="card">
                                                    <div class="col-12 card-header bg-primary text-white text-center">
                                                        INMUNOSEROLOGIA
                                                    </div>
                                                    <form onsubmit="crearInmunoserologia(event)">
                                                        <div class="card-body">
                                                            <div class="row">


                                                                <div class="col-md-4">
                                                                    <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                        <label>EXAMEN</label>
                                                                        <input id="inmunoserologia" class="form-control" type="text" name="inmunoserologia">


                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="form-group">
                                                                        <label>VALOR UNIDAD</label>
                                                                        <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-4">
                                                                    <div class="form-group">
                                                                        <label>VALOR REFERNCIAL</label>
                                                                        <input id="valor_referencialinmu" class="form-control" type="text" name="valor_referencialinmu">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <button class="btn btn-primary">
                                                                        Enviar
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                </form>
                                                <div class="table-responsive">
                                                    <table class="table table-hover" id="tblDetalle">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>Id</th>
                                                                <th>EXAMEN</th>
                                                                <th>VALOR UNIDAD</th>
                                                                <th>VALOR REFERENCIAL</th>
                                                                <th>ACCION</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="detalle_inmunoserologia" style="background: rgba(255, 255, 255, 0.70)">

                                                        </tbody>
                                                    </table>

                                                <?php
                                            } elseif ($form_section['categoria'] == 'RUTINA') {
                                                ?>
                                                    <div class="card">
                                                        <div class="card-header bg-primary text-white text-center">
                                                            HEMATOLOGIA
                                                        </div>
                                                        <form onsubmit="crearHema(event)">
                                                            <div class="card-body">
                                                                <div class="row">

                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="hemoglobina">HEMOGLOBINA</label>
                                                                            <input id="hemoglobina" class="form-control" type="text" name="hemoglobina" placeholder="">
                                                                            <input id="id" type="hidden" name="id">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <div class="form-group">
                                                                            <label for="hematocritos">HEMATROCITOS</label>
                                                                            <input id="hematocritos" class="form-control" type="text" name="hematocritos" placeholder="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <div class="form-group">
                                                                            <label for="cuentas_blancas">CUENTAS BLANCAS</label>
                                                                            <input id="cuentas_blancas" class="form-control" type="text" name="cuentas_blancas" placeholder="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <div class="form-group">
                                                                            <label for="plaquetas">PLAQUETAS</label>
                                                                            <input id="plaquetas" class="form-control" type="text" name="plaquetas" placeholder="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <div class="form-group">
                                                                            <label for="vsg">VSG</label>
                                                                            <input id="vsg" class="form-control" type="text" name="vsg" placeholder="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <button class="btn btn-primary">
                                                                            Enviar
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-hover" id="tblDetalle">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>Id</th>
                                                                <th>Hemoglobina</th>
                                                                <th>Hematrocitos</th>
                                                                <th>Cuentas Blancas</th>
                                                                <th>Plaquetas</th>
                                                                <th>VSG</th>
                                                                <th>Accion</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="detalle_hematologia" style="background: rgba(255, 255, 255, 0.70)">

                                                        </tbody>
                                                    </table>

                                                </div>



                                                <div class="card">
                                                    <div class="card-header bg-primary text-white text-center">
                                                        FORMULA LEUCOCITARIA
                                                    </div>
                                                    <form onsubmit="crearLeuco(event)">
                                                        <div class="card-body">
                                                            <div class="row" style="text-align:center;">
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="seg">SEG</label>
                                                                        <input id="seg" class="form-control amt" type="text" name="seg">

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="linf">LINF</label>
                                                                        <input id="linf" class="form-control amt" type="text" name="linf">

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="eosin">EOSIN</label>
                                                                        <input id="eosin" class="form-control amt" type="text" name="eosin">

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="monoc">MONOC</label>
                                                                        <input id="monoc" class="form-control amt" type="text" name="monoc">

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="basof">BASOF</label>
                                                                        <input id="basof" class="form-control amt" type="text" name="basof">

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="otros">OTROS</label>
                                                                        <input id="otros" class="form-control amt" type="text" name="otros">

                                                                    </div>
                                                                </div>

                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="total">TOTAL</label>
                                                                        <input id="total" action="post" class="form-control" type="text" name="total">

                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-4">
                                                                    <button class="btn btn-primary">
                                                                        Enviar
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>

                                                </div>
                                                </form>
                                                <div class="table-responsive">
                                                    <table class="table table-hover" id="tblDetalle">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>Id</th>
                                                                <th>SEG</th>
                                                                <th>LINF</th>
                                                                <th>EOSIN</th>
                                                                <th>MONOC</th>
                                                                <th>BASOF</th>
                                                                <th>OTROS</th>
                                                                <th>TOTAL</th>
                                                                <th>Accion</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="detalle_leuco" style="background: rgba(255, 255, 255, 0.70)">

                                                        </tbody>
                                                    </table>

                                                </div>
                                                <div class="card">
                                                    <div class="col-12 card-header bg-primary text-white text-center">
                                                        QUIMICA
                                                    </div>
                                                    <form onsubmit="crearQuimi(event)">
                                                        <div class="card-body">
                                                            <div class="row">


                                                                <div class="col-md-3">
                                                                    <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                        <label>EXAMEN</label>
                                                                        <input id="examen" class="form-control" type="text" name="examen">


                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label>VALOR UNIDAD</label>
                                                                        <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label>VALOR REFERNCIAL</label>
                                                                        <input id="valor_referencial1" class="form-control" type="text" name="valor_referencial1">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label>VALOR REFERENCIAL</label>
                                                                        <input id="valor_referencial2" class="form-control" type="text" name="valor_referencial2">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <button class="btn btn-primary">
                                                                        Enviar
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                </form>
                                                <div class="table-responsive">
                                                    <table class="table table-hover" id="tblDetalle">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>Id</th>
                                                                <th>EXAMEN</th>
                                                                <th>VALOR UNIDAD</th>
                                                                <th>VALOR REFERENCIAL</th>
                                                                <th>VALOR REFERENCIAL</th>
                                                                <th>ACCION</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="detalle_quimica" style="background: rgba(255, 255, 255, 0.70)">

                                                        </tbody>
                                                    </table>

                                                </div>
                                                <div class="card">
                                                    <div class="card-header bg-primary text-white text-center">
                                                        EXAMEN DE ORINA
                                                    </div>
                                                    <form onsubmit="crearOrina(event)">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="aspecto">ASPECTO</label>
                                                                        <select id="aspecto" class="form-control" name="aspecto" value="Lig.Turbio">
                                                                            <option>Lig. Turbio</option>
                                                                            <option>Turbio</option>
                                                                            <option>Purulento</option>
                                                                        </select>
                                                                        <input id="id" type="hidden" name="id">

                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="densidad">DENSIDAD</label>
                                                                        <select id="densidad" class="form-control" name="densidad" value="1.000">
                                                                            <option>1.000</option>
                                                                            <option>1.005</option>
                                                                            <option>1.010</option>
                                                                            <option>1.015</option>
                                                                            <option>1.020</option>
                                                                            <option>1.025</option>
                                                                            <option>1.030</option>
                                                                        </select>
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="ph">P.H</label>
                                                                        <select id="ph" class="form-control" name="ph" value="6.0">
                                                                            <option>5.0</option>
                                                                            <option>6.0</option>
                                                                            <option>6.5</option>
                                                                            <option>7.0</option>
                                                                            <option>7.5</option>
                                                                            <option>8.0</option>
                                                                            <option>8.5</option>
                                                                            <option>9.0</option>
                                                                        </select>
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="olor">OLOR</label>
                                                                        <select id="olor" class="form-control" name="olor" value="S.G">
                                                                            <option>S.G.</option>
                                                                            <option>Amoniacal.</option>

                                                                        </select>
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="color">COLOR</label>
                                                                        <select id="color" class="form-control" name="color" value="Amarillo">
                                                                            <option>Amarillo.</option>
                                                                            <option>Amarillo intenso.</option>
                                                                            <option>Ambar.</option>
                                                                            <option>Rojiza.</option>
                                                                            <option>Medicamentoza.</option>

                                                                        </select>
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="nitritos">NITRITOS</label>
                                                                        <select id="nitritos" class="form-control" name="nitritos" value="Neg.">
                                                                            <option>Neg.</option>
                                                                            <option>Trazas.</option>
                                                                            <option>Positivo.</option>

                                                                        </select>

                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="proteinas">PROTEINAS</label>
                                                                        <select id="proteinas" class="form-control" name="proteinas" value="Neg.">
                                                                            <option>Neg.</option>
                                                                            <option>Trazas.</option>
                                                                            <option>Positivo +</option>
                                                                            <option>Positivo ++</option>
                                                                            <option>Positivo +++</option>

                                                                        </select>

                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="glucosa">GLUCOSA</label>
                                                                        <select id="glucosa" class="form-control" name="glucosa" value="Neg.">
                                                                            <option>Neg.</option>
                                                                            <option>Trazas.</option>
                                                                            <option>Positivo +</option>
                                                                            <option>Positivo ++</option>
                                                                            <option>Positivo +++</option>

                                                                        </select>
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="cetonas">CETONAS</label>
                                                                        <select id="cetonas" class="form-control" name="cetonas" value="Neg.">
                                                                            <option>Neg.</option>
                                                                            <option>Trazas.</option>
                                                                            <option>Positivo.</option>

                                                                        </select>
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="urobilinogeno">UROBILINOGENO</label>
                                                                        <select id="urobilinogeno" class="form-control" name="urobilinogeno" value="Neg.">
                                                                            <option>Neg.</option>
                                                                            <option>Trazas.</option>
                                                                            <option>Positivo +</option>
                                                                            <option>Positivo ++</option>
                                                                            <option>Positivo +++</option>

                                                                        </select>
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="bilirrubina">BILIRRUBINA</label>
                                                                        <select id="bilirrubina" class="form-control" name="bilirrubina" value="Neg.">
                                                                            <option>Neg.</option>
                                                                            <option>Trazas.</option>
                                                                            <option>Positivo +</option>
                                                                            <option>Positivo ++</option>
                                                                            <option>Positivo +++</option>

                                                                        </select>
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="hemoglobina">HEMOGLOBINA</label>
                                                                        <select id="hemoglobina" class="form-control" name="hemoglobina" value="Neg.">
                                                                            <option>Neg.</option>
                                                                            <option>Trazas.</option>
                                                                            <option>Positivo +</option>
                                                                            <option>Positivo ++</option>
                                                                            <option>Positivo +++</option>

                                                                        </select>
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="pigmen_biliares">PIGMENTOS BILIARES</label>
                                                                        <select id="pigmen_biliares" class="form-control" name="pigmen_biliares" value="Neg.">
                                                                            <option>Neg.</option>
                                                                            <option>Trazas.</option>
                                                                            <option>Positivo +</option>
                                                                            <option>Positivo ++</option>
                                                                            <option>Positivo +++</option>

                                                                        </select>
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="sales_biliares">SALES BILIARES</label>
                                                                        <select id="sales_biliares" class="form-control" name="sales_biliares" value="Neg.">
                                                                            <option>Neg.</option>
                                                                            <option>Trazas.</option>
                                                                            <option>Positivo +</option>
                                                                            <option>Positivo ++</option>
                                                                            <option>Positivo +++</option>

                                                                        </select>
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <button class="btn btn-primary">
                                                                        Enviar
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                </form>
                                                <div class="table-responsive">
                                                    <table class="table table-hover" id="tblDetalle">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>Id</th>
                                                                <th>ASP.</th>
                                                                <th>DEN.</th>
                                                                <th>PH.</th>
                                                                <th>OLOR.</th>
                                                                <th>COL.</th>
                                                                <th>NIT.</th>
                                                                <th>PRO.</th>
                                                                <th>GLU.</th>
                                                                <th>CET.</th>
                                                                <th>UROB.</th>
                                                                <th>BILI.</th>
                                                                <th>HEMO.</th>
                                                                <th>PIG B.</th>
                                                                <th>SAL B.</th>
                                                                <th>ACCION</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="detalle_orina" style="background: rgba(255, 255, 255, 0.70)">

                                                        </tbody>
                                                    </table>

                                                </div>

                                                <div class="card">
                                                    <div class="card-header bg-primary text-white text-center">
                                                        EXAMEN DE MICROSCOPIO
                                                    </div>
                                                    <form onsubmit="crearExamisc(event)">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="celulas_ep_planas">CELULAS EP PLANAS</label>
                                                                        <select id="celulas_ep_planas" class="form-control" name="celulas_ep_planas">
                                                                            <option>ESCASAS</option>
                                                                            <option>MODERADAS</option>
                                                                            <option>ABUNDANTES</option>

                                                                        </select>
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="bacterias">BACTERIAS</label>
                                                                        <select id="bacterias" class="form-control" name="bacterias">
                                                                            <option>ESCASAS</option>
                                                                            <option>MODERADAS</option>
                                                                            <option>ABUNDANTES</option>
                                                                            <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="leucocitos">LEUCOCITOS</label>
                                                                        <input id="leucocitos" class="form-control" type="text" name="leucocitos">
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="hematies">HEMATIES</label>
                                                                        <input id="hematies" class="form-control" type="text" name="hematies">
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="mucina">MUCINA</label>
                                                                        <input id="mucina" class="form-control" type="text" name="mucina">
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="celulas_renales">CELULAS RENALES</label>
                                                                        <input id="celulas_renales" class="form-control" type="text" name="celulas_renales">
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="cristales">CRISTALES</label>
                                                                        <input id="cristales" class="form-control" type="text" name="cristales">
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="otros">OTROS</label>
                                                                        <input id="otros" class="form-control" type="text" name="otros">
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <button class="btn btn-primary">
                                                                    Enviar
                                                                </button>
                                                            </div>
                                                        </div>
                                                </div>
                                                </form>
                                                <div class="table-responsive">
                                                    <table class="table table-hover" id="tblDetalle">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>Id</th>
                                                                <th>CELULAS EP PLANAS.</th>
                                                                <th>BACTERIAS.</th>
                                                                <th>LEUCOCITOS.</th>
                                                                <th>HEMATIES.</th>
                                                                <th>MUCINA.</th>
                                                                <th>CELULAS RENALES.</th>
                                                                <th>CRISTALES.</th>
                                                                <th>OTROS.</th>
                                                                <th>ACCION</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="detalle_orinamisc" style="background: rgba(255, 255, 255, 0.70)">

                                                        </tbody>
                                                    </table>
                                                </div>

                                            <?php

                                            } elseif ($form_section['categoria'] == 'GRUPO SANGUINEO') {
                                            ?>
                                                <div class="card">
                                                    <div class="col-12 card-header bg-primary text-white text-center">
                                                        GRUPO SANGUINEO
                                                    </div>
                                                    <form onsubmit="crearGruposanguineo(event)">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <input id="id" type="hidden" name="id">
                                                                        <label>GRUPO SANGUINEO</label>
                                                                        <input id="grupo" class="form-control" type="text" name="grupo">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="form-group">
                                                                        <label>FACTOR RH</label>
                                                                        <input id="factor" class="form-control" type="text" name="factor">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <button class="btn btn-primary">
                                                                        Enviar
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-hover" id="tblDetalle">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>Id</th>
                                                                <th>GRUPO SANGUINEO</th>
                                                                <th>FACTOR RH</th>
                                                                <th>ACCION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="detalle_gruposanguineo" style="background: rgba(255, 255, 255, 0.70)">
                                                        </tbody>
                                                    </table>
                                                </div>

                                            <?php
                                            } elseif ($form_section['categoria'] == 'PRE-OPERATORIO') {
                                            ?>
                                                <div class="card">
                                                    <div class="card-header bg-primary text-white text-center">
                                                        HEMATOLOGIA
                                                    </div>
                                                    <form onsubmit="crearHema(event)">
                                                        <div class="card-body">
                                                            <div class="row">

                                                                <div class="col-lg-4">
                                                                    <div class="form-group">
                                                                        <label for="hemoglobina">HEMOGLOBINA</label>
                                                                        <input id="hemoglobina" class="form-control" type="text" name="hemoglobina" placeholder="">
                                                                        <input id="id" type="hidden" name="id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="hematocritos">HEMATROCITOS</label>
                                                                        <input id="hematocritos" class="form-control" type="text" name="hematocritos" placeholder="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="cuentas_blancas">CUENTAS BLANCAS</label>
                                                                        <input id="cuentas_blancas" class="form-control" type="text" name="cuentas_blancas" placeholder="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="plaquetas">PLAQUETAS</label>
                                                                        <input id="plaquetas" class="form-control" type="text" name="plaquetas" placeholder="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                        <label for="vsg">VSG</label>
                                                                        <input id="vsg" class="form-control" type="text" name="vsg" placeholder="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <button class="btn btn-primary">
                                                                        Enviar
                                                                    </button>
                                                                </div>
                                                            </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover" id="tblDetalle">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>Id</th>
                                                            <th>Hemoglobina</th>
                                                            <th>Hematrocitos</th>
                                                            <th>Cuentas Blancas</th>
                                                            <th>Plaquetas</th>
                                                            <th>VSG</th>
                                                            <th>Accion</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="detalle_hematologia" style="background: rgba(255, 255, 255, 0.70)">

                                                    </tbody>
                                                </table>

                                            </div>



                                            <div class="card">
                                                <div class="card-header bg-primary text-white text-center">
                                                    FORMULA LEUCOCITARIA
                                                </div>
                                                <form onsubmit="crearLeuco(event)">
                                                    <div class="card-body">
                                                        <div class="row" style="text-align:center;">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="seg">SEG</label>
                                                                    <input id="seg" class="form-control amt" type="text" name="seg">

                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="linf">LINF</label>
                                                                    <input id="linf" class="form-control amt" type="text" name="linf">

                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="eosin">EOSIN</label>
                                                                    <input id="eosin" class="form-control amt" type="text" name="eosin">

                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="monoc">MONOC</label>
                                                                    <input id="monoc" class="form-control amt" type="text" name="monoc">

                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="basof">BASOF</label>
                                                                    <input id="basof" class="form-control amt" type="text" name="basof">

                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="otros">OTROS</label>
                                                                    <input id="otros" class="form-control amt" type="text" name="otros">

                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="total">TOTAL</label>
                                                                    <input id="total" action="post" class="form-control" type="text" name="total">

                                                                </div>
                                                            </div>

                                                            <div class="col-lg-4">
                                                                <button class="btn btn-primary">
                                                                    Enviar
                                                                </button>
                                                            </div>

                                                        </div>
                                                    </div>

                                            </div>
                                            </form>
                                            <div class="table-responsive">
                                                <table class="table table-hover" id="tblDetalle">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>Id</th>
                                                            <th>SEG</th>
                                                            <th>LINF</th>
                                                            <th>EOSIN</th>
                                                            <th>MONOC</th>
                                                            <th>BASOF</th>
                                                            <th>OTROS</th>
                                                            <th>TOTAL</th>
                                                            <th>Accion</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="detalle_leuco" style="background: rgba(255, 255, 255, 0.70)">

                                                    </tbody>
                                                </table>
                                                <div class="card">
                                                    <div class="col-12 card-header bg-primary text-white text-center">
                                                        PERFIL DE COAGULACION
                                                    </div>
                                                    <form onsubmit="crearTiempos(event)">
                                                        <div class="card-body">
                                                            <div class="row">


                                                                <div class="col-md-4">
                                                                    <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                        <label>TIEMPO DE PROTROMBINA:</label>
                                                                        <input id="tp" class="form-control" type="text" name="tp">


                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="form-group">
                                                                        <label>TIEMPO PARCIAL DE TROMBOPLASTINA:</label>
                                                                        <input id="tpt" class="form-control" type="text" name="tpt">
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-4">
                                                                    <div class="form-group">
                                                                        <label>INR</label>
                                                                        <input id="inr" class="form-control" type="text" name="inr">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="form-group">
                                                                        <label>FIBRINOGENO</label>
                                                                        <input id="fibrinogeno" class="form-control" type="text" name="fibrinogeno">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <button class="btn btn-primary">
                                                                        Enviar
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                </form>
                                                <div class="table-responsive">
                                                    <table class="table table-hover" id="tblDetalle">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>Id</th>
                                                                <th>TP</th>
                                                                <th>TPT</th>
                                                                <th>INR</th>
                                                                <th>FIBRINOGENO</th>
                                                                <th>ACCION</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="detalle_tiempos" style="background: rgba(255, 255, 255, 0.70)">

                                                        </tbody>
                                                    </table>
                                                    <div class="card">
                                                        <div class="col-12 card-header bg-primary text-white text-center">
                                                            QUIMICA
                                                        </div>
                                                        <form onsubmit="crearQuimi(event)">
                                                            <div class="card-body">
                                                                <div class="row">


                                                                    <div class="col-md-3">
                                                                        <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                            <label>EXAMEN</label>
                                                                            <input id="examen" class="form-control" type="text" name="examen">


                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3">
                                                                        <div class="form-group">
                                                                            <label>VALOR UNIDAD</label>
                                                                            <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-3">
                                                                        <div class="form-group">
                                                                            <label>VALOR REFERNCIAL</label>
                                                                            <input id="valor_referencial1" class="form-control" type="text" name="valor_referencial1">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3">
                                                                        <div class="form-group">
                                                                            <label>VALOR REFERENCIAL</label>
                                                                            <input id="valor_referencial2" class="form-control" type="text" name="valor_referencial2">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <button class="btn btn-primary">
                                                                            Enviar
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                    </form>
                                                    <div class="table-responsive">
                                                        <table class="table table-hover" id="tblDetalle">
                                                            <thead class="thead-dark">
                                                                <tr>
                                                                    <th>Id</th>
                                                                    <th>EXAMEN</th>
                                                                    <th>VALOR UNIDAD</th>
                                                                    <th>VALOR REFERENCIAL</th>
                                                                    <th>VALOR REFERENCIAL</th>
                                                                    <th>ACCION</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody id="detalle_quimica" style="background: rgba(255, 255, 255, 0.70)">

                                                            </tbody>
                                                        </table>
                                                        <div class="card">
                                                            <div class="col-12 card-header bg-primary text-white text-center">
                                                                INMUNOSEROLOGIA
                                                            </div>
                                                            <form onsubmit="crearInmunoserologia(event)">
                                                                <div class="card-body">
                                                                    <div class="row">


                                                                        <div class="col-md-4">
                                                                            <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                                <label>EXAMEN</label>
                                                                                <input id="inmunoserologia" class="form-control" type="text" name="inmunoserologia">


                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <div class="form-group">
                                                                                <label>VALOR UNIDAD</label>
                                                                                <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-4">
                                                                            <div class="form-group">
                                                                                <label>VALOR REFERNCIAL</label>
                                                                                <input id="valor_referencialinmu" class="form-control" type="text" name="valor_referencialinmu">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <button class="btn btn-primary">
                                                                                Enviar
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                        </form>
                                                        <div class="table-responsive">
                                                            <table class="table table-hover" id="tblDetalle">
                                                                <thead class="thead-dark">
                                                                    <tr>
                                                                        <th>Id</th>
                                                                        <th>EXAMEN</th>
                                                                        <th>VALOR UNIDAD</th>
                                                                        <th>VALOR REFERENCIAL</th>
                                                                        <th>ACCION</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody id="detalle_inmunoserologia" style="background: rgba(255, 255, 255, 0.70)">

                                                                </tbody>
                                                            </table>


                                                        <?php
                                                    } elseif ($form_section['categoria'] == 'PERFIL LIPIDICO') {
                                                        ?>
                                                            <div class="card">
                                                                <div class="col-12 card-header bg-primary text-white text-center">
                                                                    QUIMICA
                                                                </div>
                                                                <form onsubmit="crearQuimi(event)">
                                                                    <div class="card-body">
                                                                        <div class="row">


                                                                            <div class="col-md-3">
                                                                                <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                                    <label>EXAMEN</label>
                                                                                    <input id="examen" class="form-control" type="text" name="examen">


                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3">
                                                                                <div class="form-group">
                                                                                    <label>VALOR UNIDAD</label>
                                                                                    <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-lg-3">
                                                                                <div class="form-group">
                                                                                    <label>VALOR REFERNCIAL</label>
                                                                                    <input id="valor_referencial1" class="form-control" type="text" name="valor_referencial1">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3">
                                                                                <div class="form-group">
                                                                                    <label>VALOR REFERENCIAL</label>
                                                                                    <input id="valor_referencial2" class="form-control" type="text" name="valor_referencial2">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-4">
                                                                                <button class="btn btn-primary">
                                                                                    Enviar
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                            </form>
                                                            <div class="table-responsive">
                                                                <table class="table table-hover" id="tblDetalle">
                                                                    <thead class="thead-dark">
                                                                        <tr>
                                                                            <th>Id</th>
                                                                            <th>EXAMEN</th>
                                                                            <th>VALOR UNIDAD</th>
                                                                            <th>VALOR REFERENCIAL</th>
                                                                            <th>VALOR REFERENCIAL</th>
                                                                            <th>ACCION</th>
                                                                        </tr>
                                                                    </thead>

                                                                    <tbody id="detalle_quimica" style="background: rgba(255, 255, 255, 0.70)">

                                                                    </tbody>
                                                                </table>

                                                            <?php
                                                        } elseif ($form_section['categoria'] == 'PERFIL HEPATICO') {
                                                            ?>
                                                                <div class="card">
                                                                    <div class="col-12 card-header bg-primary text-white text-center">
                                                                        PERFIL DE COAGULACION
                                                                    </div>
                                                                    <form onsubmit="crearTiempos(event)">
                                                                        <div class="card-body">
                                                                            <div class="row">


                                                                                <div class="col-md-4">
                                                                                    <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                                        <label>TIEMPO DE PROTROMBINA:</label>
                                                                                        <input id="tp" class="form-control" type="text" name="tp">


                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-4">
                                                                                    <div class="form-group">
                                                                                        <label>TIEMPO PARCIAL DE TROMBOPLASTINA:</label>
                                                                                        <input id="tpt" class="form-control" type="text" name="tpt">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-4">
                                                                                    <div class="form-group">
                                                                                        <label>INR</label>
                                                                                        <input id="inr" class="form-control" type="text" name="inr">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-4">
                                                                                    <div class="form-group">
                                                                                        <label>FIBRINOGENO</label>
                                                                                        <input id="fibrinogeno" class="form-control" type="text" name="fibrinogeno">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-4">
                                                                                    <button class="btn btn-primary">
                                                                                        Enviar
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                                </form>
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover" id="tblDetalle">
                                                                        <thead class="thead-dark">
                                                                            <tr>
                                                                                <th>Id</th>
                                                                                <th>TP</th>
                                                                                <th>TPT</th>
                                                                                <th>INR</th>
                                                                                <th>FIBRINOGENO</th>
                                                                                <th>ACCION</th>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody id="detalle_tiempos" style="background: rgba(255, 255, 255, 0.70)">

                                                                        </tbody>
                                                                    </table>
                                                                    <div class="card">
                                                                        <div class="col-12 card-header bg-primary text-white text-center">
                                                                            QUIMICA
                                                                        </div>
                                                                        <form onsubmit="crearQuimi(event)">
                                                                            <div class="card-body">
                                                                                <div class="row">


                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                                            <label>EXAMEN</label>
                                                                                            <input id="examen" class="form-control" type="text" name="examen">


                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-3">
                                                                                        <div class="form-group">
                                                                                            <label>VALOR UNIDAD</label>
                                                                                            <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-3">
                                                                                        <div class="form-group">
                                                                                            <label>VALOR REFERNCIAL</label>
                                                                                            <input id="valor_referencial1" class="form-control" type="text" name="valor_referencial1">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-3">
                                                                                        <div class="form-group">
                                                                                            <label>VALOR REFERENCIAL</label>
                                                                                            <input id="valor_referencial2" class="form-control" type="text" name="valor_referencial2">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-4">
                                                                                        <button class="btn btn-primary">
                                                                                            Enviar
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    </form>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-hover" id="tblDetalle">
                                                                            <thead class="thead-dark">
                                                                                <tr>
                                                                                    <th>Id</th>
                                                                                    <th>EXAMEN</th>
                                                                                    <th>VALOR UNIDAD</th>
                                                                                    <th>VALOR REFERENCIAL</th>
                                                                                    <th>VALOR REFERENCIAL</th>
                                                                                    <th>ACCION</th>
                                                                                </tr>
                                                                            </thead>

                                                                            <tbody id="detalle_quimica" style="background: rgba(255, 255, 255, 0.70)">

                                                                            </tbody>
                                                                        </table>


                                                                    <?php
                                                                } elseif ($form_section['categoria'] == 'PERFIL PEDIATRICO') {
                                                                    ?>
                                                                        <div class="card">
                                                                            <div class="card-header bg-primary text-white text-center">
                                                                                HEMATOLOGIA
                                                                            </div>
                                                                            <form onsubmit="crearHema(event)">
                                                                                <div class="card-body">
                                                                                    <div class="row">

                                                                                        <div class="col-lg-4">
                                                                                            <div class="form-group">
                                                                                                <label for="hemoglobina">HEMOGLOBINA</label>
                                                                                                <input id="hemoglobina" class="form-control" type="text" name="hemoglobina" placeholder="">
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="hematocritos">HEMATROCITOS</label>
                                                                                                <input id="hematocritos" class="form-control" type="text" name="hematocritos" placeholder="">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="cuentas_blancas">CUENTAS BLANCAS</label>
                                                                                                <input id="cuentas_blancas" class="form-control" type="text" name="cuentas_blancas" placeholder="">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="plaquetas">PLAQUETAS</label>
                                                                                                <input id="plaquetas" class="form-control" type="text" name="plaquetas" placeholder="">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="vsg">VSG</label>
                                                                                                <input id="vsg" class="form-control" type="text" name="vsg" placeholder="">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-4">
                                                                                            <button class="btn btn-primary">
                                                                                                Enviar
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-hover" id="tblDetalle">
                                                                            <thead class="thead-dark">
                                                                                <tr>
                                                                                    <th>Id</th>
                                                                                    <th>Hemoglobina</th>
                                                                                    <th>Hematrocitos</th>
                                                                                    <th>Cuentas Blancas</th>
                                                                                    <th>Plaquetas</th>
                                                                                    <th>VSG</th>
                                                                                    <th>Accion</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="detalle_hematologia" style="background: rgba(255, 255, 255, 0.70)">

                                                                            </tbody>
                                                                        </table>

                                                                    </div>



                                                                    <div class="card">
                                                                        <div class="card-header bg-primary text-white text-center">
                                                                            FORMULA LEUCOCITARIA
                                                                        </div>
                                                                        <form onsubmit="crearLeuco(event)">
                                                                            <div class="card-body">
                                                                                <div class="row" style="text-align:center;">
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label for="seg">SEG</label>
                                                                                            <input id="seg" class="form-control amt" type="text" name="seg">

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label for="linf">LINF</label>
                                                                                            <input id="linf" class="form-control amt" type="text" name="linf">

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label for="eosin">EOSIN</label>
                                                                                            <input id="eosin" class="form-control amt" type="text" name="eosin">

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label for="monoc">MONOC</label>
                                                                                            <input id="monoc" class="form-control amt" type="text" name="monoc">

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label for="basof">BASOF</label>
                                                                                            <input id="basof" class="form-control amt" type="text" name="basof">

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label for="otros">OTROS</label>
                                                                                            <input id="otros" class="form-control amt" type="text" name="otros">

                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label for="total">TOTAL</label>
                                                                                            <input id="total" action="post" class="form-control" type="text" name="total">

                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-4">
                                                                                        <button class="btn btn-primary">
                                                                                            Enviar
                                                                                        </button>
                                                                                    </div>

                                                                                </div>
                                                                            </div>

                                                                    </div>
                                                                    </form>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-hover" id="tblDetalle">
                                                                            <thead class="thead-dark">
                                                                                <tr>
                                                                                    <th>Id</th>
                                                                                    <th>SEG</th>
                                                                                    <th>LINF</th>
                                                                                    <th>EOSIN</th>
                                                                                    <th>MONOC</th>
                                                                                    <th>BASOF</th>
                                                                                    <th>OTROS</th>
                                                                                    <th>TOTAL</th>
                                                                                    <th>Accion</th>
                                                                                </tr>
                                                                            </thead>

                                                                            <tbody id="detalle_leuco" style="background: rgba(255, 255, 255, 0.70)">

                                                                            </tbody>
                                                                        </table>

                                                                    </div>
                                                                    <div class="card">
                                                                        <div class="col-12 card-header bg-primary text-white text-center">
                                                                            QUIMICA
                                                                        </div>
                                                                        <form onsubmit="crearQuimi(event)">
                                                                            <div class="card-body">
                                                                                <div class="row">


                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                                            <label>EXAMEN</label>
                                                                                            <input id="examen" class="form-control" type="text" name="examen">


                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-3">
                                                                                        <div class="form-group">
                                                                                            <label>VALOR UNIDAD</label>
                                                                                            <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-3">
                                                                                        <div class="form-group">
                                                                                            <label>VALOR REFERNCIAL</label>
                                                                                            <input id="valor_referencial1" class="form-control" type="text" name="valor_referencial1">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-3">
                                                                                        <div class="form-group">
                                                                                            <label>VALOR REFERENCIAL</label>
                                                                                            <input id="valor_referencial2" class="form-control" type="text" name="valor_referencial2">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-4">
                                                                                        <button class="btn btn-primary">
                                                                                            Enviar
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    </form>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-hover" id="tblDetalle">
                                                                            <thead class="thead-dark">
                                                                                <tr>
                                                                                    <th>Id</th>
                                                                                    <th>EXAMEN</th>
                                                                                    <th>VALOR UNIDAD</th>
                                                                                    <th>VALOR REFERENCIAL</th>
                                                                                    <th>VALOR REFERENCIAL</th>
                                                                                    <th>ACCION</th>
                                                                                </tr>
                                                                            </thead>

                                                                            <tbody id="detalle_quimica" style="background: rgba(255, 255, 255, 0.70)">

                                                                            </tbody>
                                                                        </table>

                                                                    </div>
                                                                    <div class="card">
                                                                        <div class="card-header bg-primary text-white text-center">
                                                                            EXAMEN DE ORINA
                                                                        </div>
                                                                        <form onsubmit="crearOrina(event)">
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            <label for="aspecto">ASPECTO</label>
                                                                                            <select id="aspecto" class="form-control" name="aspecto" value="Lig.Turbio">
                                                                                                <option>Lig. Turbio</option>
                                                                                                <option>Turbio</option>
                                                                                                <option>Purulento</option>
                                                                                            </select>
                                                                                            <input id="id" type="hidden" name="id">

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            <label for="densidad">DENSIDAD</label>
                                                                                            <select id="densidad" class="form-control" name="densidad" value="1.000">
                                                                                                <option>1.000</option>
                                                                                                <option>1.005</option>
                                                                                                <option>1.010</option>
                                                                                                <option>1.015</option>
                                                                                                <option>1.020</option>
                                                                                                <option>1.025</option>
                                                                                                <option>1.030</option>
                                                                                            </select>
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            <label for="ph">P.H</label>
                                                                                            <select id="ph" class="form-control" name="ph" value="6.0">
                                                                                                <option>5.0</option>
                                                                                                <option>6.0</option>
                                                                                                <option>6.5</option>
                                                                                                <option>7.0</option>
                                                                                                <option>7.5</option>
                                                                                                <option>8.0</option>
                                                                                                <option>8.5</option>
                                                                                                <option>9.0</option>
                                                                                            </select>
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            <label for="olor">OLOR</label>
                                                                                            <select id="olor" class="form-control" name="olor" value="S.G">
                                                                                                <option>S.G.</option>
                                                                                                <option>Amoniacal.</option>

                                                                                            </select>
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            <label for="color">COLOR</label>
                                                                                            <select id="color" class="form-control" name="color" value="Amarillo">
                                                                                                <option>Amarillo.</option>
                                                                                                <option>Amarillo intenso.</option>
                                                                                                <option>Ambar.</option>
                                                                                                <option>Rojiza.</option>
                                                                                                <option>Medicamentoza.</option>

                                                                                            </select>
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            <label for="nitritos">NITRITOS</label>
                                                                                            <select id="nitritos" class="form-control" name="nitritos" value="Neg.">
                                                                                                <option>Neg.</option>
                                                                                                <option>Trazas.</option>
                                                                                                <option>Positivo.</option>

                                                                                            </select>

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            <label for="proteinas">PROTEINAS</label>
                                                                                            <select id="proteinas" class="form-control" name="proteinas" value="Neg.">
                                                                                                <option>Neg.</option>
                                                                                                <option>Trazas.</option>
                                                                                                <option>Positivo +</option>
                                                                                                <option>Positivo ++</option>
                                                                                                <option>Positivo +++</option>

                                                                                            </select>

                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            <label for="glucosa">GLUCOSA</label>
                                                                                            <select id="glucosa" class="form-control" name="glucosa" value="Neg.">
                                                                                                <option>Neg.</option>
                                                                                                <option>Trazas.</option>
                                                                                                <option>Positivo +</option>
                                                                                                <option>Positivo ++</option>
                                                                                                <option>Positivo +++</option>

                                                                                            </select>
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            <label for="cetonas">CETONAS</label>
                                                                                            <select id="cetonas" class="form-control" name="cetonas" value="Neg.">
                                                                                                <option>Neg.</option>
                                                                                                <option>Trazas.</option>
                                                                                                <option>Positivo.</option>

                                                                                            </select>
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            <label for="urobilinogeno">UROBILINOGENO</label>
                                                                                            <select id="urobilinogeno" class="form-control" name="urobilinogeno" value="Neg.">
                                                                                                <option>Neg.</option>
                                                                                                <option>Trazas.</option>
                                                                                                <option>Positivo +</option>
                                                                                                <option>Positivo ++</option>
                                                                                                <option>Positivo +++</option>

                                                                                            </select>
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            <label for="bilirrubina">BILIRRUBINA</label>
                                                                                            <select id="bilirrubina" class="form-control" name="bilirrubina" value="Neg.">
                                                                                                <option>Neg.</option>
                                                                                                <option>Trazas.</option>
                                                                                                <option>Positivo +</option>
                                                                                                <option>Positivo ++</option>
                                                                                                <option>Positivo +++</option>

                                                                                            </select>
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            <label for="hemoglobina">HEMOGLOBINA</label>
                                                                                            <select id="hemoglobina" class="form-control" name="hemoglobina" value="Neg.">
                                                                                                <option>Neg.</option>
                                                                                                <option>Trazas.</option>
                                                                                                <option>Positivo +</option>
                                                                                                <option>Positivo ++</option>
                                                                                                <option>Positivo +++</option>

                                                                                            </select>
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            <label for="pigmen_biliares">PIGMENTOS BILIARES</label>
                                                                                            <select id="pigmen_biliares" class="form-control" name="pigmen_biliares" value="Neg.">
                                                                                                <option>Neg.</option>
                                                                                                <option>Trazas.</option>
                                                                                                <option>Positivo +</option>
                                                                                                <option>Positivo ++</option>
                                                                                                <option>Positivo +++</option>

                                                                                            </select>
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div class="form-group">
                                                                                            <label for="sales_biliares">SALES BILIARES</label>
                                                                                            <select id="sales_biliares" class="form-control" name="sales_biliares" value="Neg.">
                                                                                                <option>Neg.</option>
                                                                                                <option>Trazas.</option>
                                                                                                <option>Positivo +</option>
                                                                                                <option>Positivo ++</option>
                                                                                                <option>Positivo +++</option>

                                                                                            </select>
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-4">
                                                                                        <button class="btn btn-primary">
                                                                                            Enviar
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    </form>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-hover" id="tblDetalle">
                                                                            <thead class="thead-dark">
                                                                                <tr>
                                                                                    <th>Id</th>
                                                                                    <th>ASP.</th>
                                                                                    <th>DEN.</th>
                                                                                    <th>PH.</th>
                                                                                    <th>OLOR.</th>
                                                                                    <th>COL.</th>
                                                                                    <th>NIT.</th>
                                                                                    <th>PRO.</th>
                                                                                    <th>GLU.</th>
                                                                                    <th>CET.</th>
                                                                                    <th>UROB.</th>
                                                                                    <th>BILI.</th>
                                                                                    <th>HEMO.</th>
                                                                                    <th>PIG B.</th>
                                                                                    <th>SAL B.</th>
                                                                                    <th>ACCION</th>
                                                                                </tr>
                                                                            </thead>

                                                                            <tbody id="detalle_orina" style="background: rgba(255, 255, 255, 0.70)">

                                                                            </tbody>
                                                                        </table>

                                                                    </div>

                                                                    <div class="card">
                                                                        <div class="card-header bg-primary text-white text-center">
                                                                            EXAMEN DE MICROSCOPIO
                                                                        </div>
                                                                        <form onsubmit="crearExamisc(event)">
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <div class="col-lg-3">
                                                                                        <div class="form-group">
                                                                                            <label for="celulas_ep_planas">CELULAS EP PLANAS</label>
                                                                                            <select id="celulas_ep_planas" class="form-control" name="celulas_ep_planas">
                                                                                                <option>ESCASAS</option>
                                                                                                <option>MODERADAS</option>
                                                                                                <option>ABUNDANTES</option>

                                                                                            </select>
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-3">
                                                                                        <div class="form-group">
                                                                                            <label for="bacterias">BACTERIAS</label>
                                                                                            <select id="bacterias" class="form-control" name="bacterias">
                                                                                                <option>ESCASAS</option>
                                                                                                <option>MODERADAS</option>
                                                                                                <option>ABUNDANTES</option>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-3">
                                                                                        <div class="form-group">
                                                                                            <label for="leucocitos">LEUCOCITOS</label>
                                                                                            <input id="leucocitos" class="form-control" type="text" name="leucocitos">
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-3">
                                                                                        <div class="form-group">
                                                                                            <label for="hematies">HEMATIES</label>
                                                                                            <input id="hematies" class="form-control" type="text" name="hematies">
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-3">
                                                                                        <div class="form-group">
                                                                                            <label for="mucina">MUCINA</label>
                                                                                            <input id="mucina" class="form-control" type="text" name="mucina">
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-3">
                                                                                        <div class="form-group">
                                                                                            <label for="celulas_renales">CELULAS RENALES</label>
                                                                                            <input id="celulas_renales" class="form-control" type="text" name="celulas_renales">
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-3">
                                                                                        <div class="form-group">
                                                                                            <label for="cristales">CRISTALES</label>
                                                                                            <input id="cristales" class="form-control" type="text" name="cristales">
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-3">
                                                                                        <div class="form-group">
                                                                                            <label for="otros">OTROS</label>
                                                                                            <input id="otros" class="form-control" type="text" name="otros">
                                                                                            <input id="id" type="hidden" name="id">
                                                                                        </div>
                                                                                    </div>
                                                                                    <button class="btn btn-primary">
                                                                                        Enviar
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    </form>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-hover" id="tblDetalle">
                                                                            <thead class="thead-dark">
                                                                                <tr>
                                                                                    <th>Id</th>
                                                                                    <th>CELULAS EP PLANAS.</th>
                                                                                    <th>BACTERIAS.</th>
                                                                                    <th>LEUCOCITOS.</th>
                                                                                    <th>HEMATIES.</th>
                                                                                    <th>MUCINA.</th>
                                                                                    <th>CELULAS RENALES.</th>
                                                                                    <th>CRISTALES.</th>
                                                                                    <th>OTROS.</th>
                                                                                    <th>ACCION</th>
                                                                                </tr>
                                                                            </thead>

                                                                            <tbody id="detalle_orinamisc" style="background: rgba(255, 255, 255, 0.70)">

                                                                            </tbody>
                                                                        </table>
                                                                        <div class="card">
                                                                            <div class="card-header bg-primary text-white text-center">
                                                                                EXAMEN DE HECES
                                                                            </div>
                                                                            <form onsubmit="crearHeces(event)">
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="aspecto">ASPECTO</label>
                                                                                                <select id="aspecto" class="form-control" name="aspecto" value="HETEROGENEO">
                                                                                                    <option>HETEROGENEO</option>
                                                                                                    <option>HOMOGENEO</option>

                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="color">COLOR</label>
                                                                                                <select id="color" class="form-control" name="color" value="MARRON">
                                                                                                    <option>MARRON</option>
                                                                                                    <option>AMARILLA</option>
                                                                                                    <option>NEGRO</option>
                                                                                                    <option>ROJO</option>
                                                                                                    <option>VERDE</option>
                                                                                                    <option>BLANCA</option>
                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="olor">OLOR</label>
                                                                                                <select id="olor" class="form-control" name="olor" value="FETIDA">
                                                                                                    <option>FETIDA</option>
                                                                                                    <option>FECAL</option>
                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="consistencia">CONSISTENCIA</label>
                                                                                                <select id="consistencia" class="form-control" name="consistencia" value="BLANDA">
                                                                                                    <option>BLANDA</option>
                                                                                                    <option>DURA</option>
                                                                                                    <option>DIARREICA</option>
                                                                                                    <option>LIQUIDA</option>
                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="reaccion">REACCION</label>
                                                                                                <select id="reaccion" class="form-control" name="reaccion" value="ACIDA">
                                                                                                    <option>ACIDA</option>
                                                                                                    <option>ALCALINA</option>


                                                                                                </select>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="moco">MOCO</label>
                                                                                                <select id="moco" class="form-control" name="moco" value="AUSENTE">
                                                                                                    <option>AUSENTE</option>
                                                                                                    <option>PRESENTE</option>

                                                                                                </select>

                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="sangre">SANGRE</label>
                                                                                                <select id="sangre" class="form-control" name="sangre" value="AUSENTE">
                                                                                                    <option>AUSENTE</option>
                                                                                                    <option>PRESENTE</option>
                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="ra">R.A</label>
                                                                                                <select id="ra" class="form-control" name="ra" value="PRESENTE">
                                                                                                    <option>AUSENTE</option>
                                                                                                    <option>PRESENTE</option>

                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="ph">P.H</label>
                                                                                                <input id="ph" class="form-control" type="text" name="ph">

                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>



                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="azucares">AZUCARES REDUCTORES</label>
                                                                                                <input id="azucares" class="form-control" type="text" name="azucares">
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-lg-4">
                                                                                            <button class="btn btn-primary">
                                                                                                Enviar
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                        </form>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover" id="tblDetalle">
                                                                                <thead class="thead-dark">
                                                                                    <tr>
                                                                                        <th>Id</th>
                                                                                        <th>ASP.</th>
                                                                                        <th>COL.</th>
                                                                                        <th>OLOR.</th>
                                                                                        <th>CONS.</th>
                                                                                        <th>REAC.</th>
                                                                                        <th>MOCO.</th>
                                                                                        <th>SANGRE.</th>
                                                                                        <th>R.A.</th>
                                                                                        <th>PH.</th>
                                                                                        <th>AZUCARES REDUC.</th>
                                                                                        <th>ACCION</th>
                                                                                    </tr>
                                                                                </thead>

                                                                                <tbody id="detalle_heces" style="background: rgba(255, 255, 255, 0.70)">

                                                                                </tbody>
                                                                            </table>
                                                                            <div class="card">
                                                                                <div class="card-header bg-primary text-white text-center">
                                                                                    OBSERVACION MICROSCOPICA
                                                                                </div>
                                                                                <form onsubmit="crearHecesmisc(event)">
                                                                                    <div class="card-body">
                                                                                        <div class="row">


                                                                                            <div class="col-lg-4">
                                                                                                <div class="form-group">
                                                                                                    <label for="protozoarios">PROTOZOARIOS</label>
                                                                                                    <input id="protozoarios" class="form-control" type="text" name="protozoarios" value=" No se observó forma evolutiva parasitaria">
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-4">
                                                                                                <div class="form-group">
                                                                                                    <label for="helmintos">HELMINTOS</label>
                                                                                                    <input id="helmintos" class="form-control" type="text" name="helmintos" value=" No se observó forma evolutiva parasitaria">
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-4">
                                                                                                <div class="form-group">
                                                                                                    <label for="otros">OTROS</label>
                                                                                                    <input id="otros" class="form-control" type="text" name="otros">
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <button class="btn btn-primary">
                                                                                                Enviar
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                            </div>
                                                                            </form>
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover" id="tblDetalle">
                                                                                    <thead class="thead-dark">
                                                                                        <tr>
                                                                                            <th>Id</th>
                                                                                            <th>PROTOZOARIOS.</th>
                                                                                            <th>HELMINTOS.</th>
                                                                                            <th>OTROS.</th>
                                                                                            <th>ACCION</th>
                                                                                        </tr>
                                                                                    </thead>

                                                                                    <tbody id="detalle_hecesmisc" style="background: rgba(255, 255, 255, 0.70)">

                                                                                    </tbody>
                                                                                </table>

                                                                            </div>

                                                                        <?php
                                                                    } elseif ($form_section['categoria'] == 'PERFIL 20') {
                                                                        ?>
                                                                            <div class="card">
                                                                                <div class="card-header bg-primary text-white text-center">
                                                                                    HEMATOLOGIA
                                                                                </div>
                                                                                <form onsubmit="crearHema(event)">
                                                                                    <div class="card-body">
                                                                                        <div class="row">

                                                                                            <div class="col-lg-4">
                                                                                                <div class="form-group">
                                                                                                    <label for="hemoglobina">HEMOGLOBINA</label>
                                                                                                    <input id="hemoglobina" class="form-control" type="text" name="hemoglobina" placeholder="">
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="hematocritos">HEMATROCITOS</label>
                                                                                                    <input id="hematocritos" class="form-control" type="text" name="hematocritos" placeholder="">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="cuentas_blancas">CUENTAS BLANCAS</label>
                                                                                                    <input id="cuentas_blancas" class="form-control" type="text" name="cuentas_blancas" placeholder="">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="plaquetas">PLAQUETAS</label>
                                                                                                    <input id="plaquetas" class="form-control" type="text" name="plaquetas" placeholder="">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="vsg">VSG</label>
                                                                                                    <input id="vsg" class="form-control" type="text" name="vsg" placeholder="">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-4">
                                                                                                <button class="btn btn-primary">
                                                                                                    Enviar
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover" id="tblDetalle">
                                                                                <thead class="thead-dark">
                                                                                    <tr>
                                                                                        <th>Id</th>
                                                                                        <th>Hemoglobina</th>
                                                                                        <th>Hematrocitos</th>
                                                                                        <th>Cuentas Blancas</th>
                                                                                        <th>Plaquetas</th>
                                                                                        <th>VSG</th>
                                                                                        <th>Accion</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody id="detalle_hematologia" style="background: rgba(255, 255, 255, 0.70)">

                                                                                </tbody>
                                                                            </table>

                                                                        </div>



                                                                        <div class="card">
                                                                            <div class="card-header bg-primary text-white text-center">
                                                                                FORMULA LEUCOCITARIA
                                                                            </div>
                                                                            <form onsubmit="crearLeuco(event)">
                                                                                <div class="card-body">
                                                                                    <div class="row" style="text-align:center;">
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group">
                                                                                                <label for="seg">SEG</label>
                                                                                                <input id="seg" class="form-control amt" type="text" name="seg">

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group">
                                                                                                <label for="linf">LINF</label>
                                                                                                <input id="linf" class="form-control amt" type="text" name="linf">

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group">
                                                                                                <label for="eosin">EOSIN</label>
                                                                                                <input id="eosin" class="form-control amt" type="text" name="eosin">

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group">
                                                                                                <label for="monoc">MONOC</label>
                                                                                                <input id="monoc" class="form-control amt" type="text" name="monoc">

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group">
                                                                                                <label for="basof">BASOF</label>
                                                                                                <input id="basof" class="form-control amt" type="text" name="basof">

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group">
                                                                                                <label for="otros">OTROS</label>
                                                                                                <input id="otros" class="form-control amt" type="text" name="otros">

                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group">
                                                                                                <label for="total">TOTAL</label>
                                                                                                <input id="total" action="post" class="form-control" type="text" name="total">

                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-lg-4">
                                                                                            <button class="btn btn-primary">
                                                                                                Enviar
                                                                                            </button>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>

                                                                        </div>
                                                                        </form>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover" id="tblDetalle">
                                                                                <thead class="thead-dark">
                                                                                    <tr>
                                                                                        <th>Id</th>
                                                                                        <th>SEG</th>
                                                                                        <th>LINF</th>
                                                                                        <th>EOSIN</th>
                                                                                        <th>MONOC</th>
                                                                                        <th>BASOF</th>
                                                                                        <th>OTROS</th>
                                                                                        <th>TOTAL</th>
                                                                                        <th>Accion</th>
                                                                                    </tr>
                                                                                </thead>

                                                                                <tbody id="detalle_leuco" style="background: rgba(255, 255, 255, 0.70)">

                                                                                </tbody>
                                                                            </table>

                                                                        </div>
                                                                        <div class="card">
                                                                            <div class="col-12 card-header bg-primary text-white text-center">
                                                                                QUIMICA
                                                                            </div>
                                                                            <form onsubmit="crearQuimi(event)">
                                                                                <div class="card-body">
                                                                                    <div class="row">


                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                                                <label>EXAMEN</label>
                                                                                                <input id="examen" class="form-control" type="text" name="examen">


                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-3">
                                                                                            <div class="form-group">
                                                                                                <label>VALOR UNIDAD</label>
                                                                                                <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-lg-3">
                                                                                            <div class="form-group">
                                                                                                <label>VALOR REFERNCIAL</label>
                                                                                                <input id="valor_referencial1" class="form-control" type="text" name="valor_referencial1">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-3">
                                                                                            <div class="form-group">
                                                                                                <label>VALOR REFERENCIAL</label>
                                                                                                <input id="valor_referencial2" class="form-control" type="text" name="valor_referencial2">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-4">
                                                                                            <button class="btn btn-primary">
                                                                                                Enviar
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                        </form>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover" id="tblDetalle">
                                                                                <thead class="thead-dark">
                                                                                    <tr>
                                                                                        <th>Id</th>
                                                                                        <th>EXAMEN</th>
                                                                                        <th>VALOR UNIDAD</th>
                                                                                        <th>VALOR REFERENCIAL</th>
                                                                                        <th>VALOR REFERENCIAL</th>
                                                                                        <th>ACCION</th>
                                                                                    </tr>
                                                                                </thead>

                                                                                <tbody id="detalle_quimica" style="background: rgba(255, 255, 255, 0.70)">

                                                                                </tbody>
                                                                            </table>

                                                                        </div>
                                                                        <div class="card">
                                                                            <div class="card-header bg-primary text-white text-center">
                                                                                EXAMEN DE ORINA
                                                                            </div>
                                                                            <form onsubmit="crearOrina(event)">
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="aspecto">ASPECTO</label>
                                                                                                <select id="aspecto" class="form-control" name="aspecto" value="Lig.Turbio">
                                                                                                    <option>Lig. Turbio</option>
                                                                                                    <option>Turbio</option>
                                                                                                    <option>Purulento</option>
                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="densidad">DENSIDAD</label>
                                                                                                <select id="densidad" class="form-control" name="densidad" value="1.000">
                                                                                                    <option>1.000</option>
                                                                                                    <option>1.005</option>
                                                                                                    <option>1.010</option>
                                                                                                    <option>1.015</option>
                                                                                                    <option>1.020</option>
                                                                                                    <option>1.025</option>
                                                                                                    <option>1.030</option>
                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="ph">P.H</label>
                                                                                                <select id="ph" class="form-control" name="ph" value="6.0">
                                                                                                    <option>5.0</option>
                                                                                                    <option>6.0</option>
                                                                                                    <option>6.5</option>
                                                                                                    <option>7.0</option>
                                                                                                    <option>7.5</option>
                                                                                                    <option>8.0</option>
                                                                                                    <option>8.5</option>
                                                                                                    <option>9.0</option>
                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="olor">OLOR</label>
                                                                                                <select id="olor" class="form-control" name="olor" value="S.G">
                                                                                                    <option>S.G.</option>
                                                                                                    <option>Amoniacal.</option>

                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="color">COLOR</label>
                                                                                                <select id="color" class="form-control" name="color" value="Amarillo">
                                                                                                    <option>Amarillo.</option>
                                                                                                    <option>Amarillo intenso.</option>
                                                                                                    <option>Ambar.</option>
                                                                                                    <option>Rojiza.</option>
                                                                                                    <option>Medicamentoza.</option>

                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="nitritos">NITRITOS</label>
                                                                                                <select id="nitritos" class="form-control" name="nitritos" value="Neg.">
                                                                                                    <option>Neg.</option>
                                                                                                    <option>Trazas.</option>
                                                                                                    <option>Positivo.</option>

                                                                                                </select>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="proteinas">PROTEINAS</label>
                                                                                                <select id="proteinas" class="form-control" name="proteinas" value="Neg.">
                                                                                                    <option>Neg.</option>
                                                                                                    <option>Trazas.</option>
                                                                                                    <option>Positivo +</option>
                                                                                                    <option>Positivo ++</option>
                                                                                                    <option>Positivo +++</option>

                                                                                                </select>

                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="glucosa">GLUCOSA</label>
                                                                                                <select id="glucosa" class="form-control" name="glucosa" value="Neg.">
                                                                                                    <option>Neg.</option>
                                                                                                    <option>Trazas.</option>
                                                                                                    <option>Positivo +</option>
                                                                                                    <option>Positivo ++</option>
                                                                                                    <option>Positivo +++</option>

                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="cetonas">CETONAS</label>
                                                                                                <select id="cetonas" class="form-control" name="cetonas" value="Neg.">
                                                                                                    <option>Neg.</option>
                                                                                                    <option>Trazas.</option>
                                                                                                    <option>Positivo.</option>

                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="urobilinogeno">UROBILINOGENO</label>
                                                                                                <select id="urobilinogeno" class="form-control" name="urobilinogeno" value="Neg.">
                                                                                                    <option>Neg.</option>
                                                                                                    <option>Trazas.</option>
                                                                                                    <option>Positivo +</option>
                                                                                                    <option>Positivo ++</option>
                                                                                                    <option>Positivo +++</option>

                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="bilirrubina">BILIRRUBINA</label>
                                                                                                <select id="bilirrubina" class="form-control" name="bilirrubina" value="Neg.">
                                                                                                    <option>Neg.</option>
                                                                                                    <option>Trazas.</option>
                                                                                                    <option>Positivo +</option>
                                                                                                    <option>Positivo ++</option>
                                                                                                    <option>Positivo +++</option>

                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="hemoglobina">HEMOGLOBINA</label>
                                                                                                <select id="hemoglobina" class="form-control" name="hemoglobina" value="Neg.">
                                                                                                    <option>Neg.</option>
                                                                                                    <option>Trazas.</option>
                                                                                                    <option>Positivo +</option>
                                                                                                    <option>Positivo ++</option>
                                                                                                    <option>Positivo +++</option>

                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="pigmen_biliares">PIGMENTOS BILIARES</label>
                                                                                                <select id="pigmen_biliares" class="form-control" name="pigmen_biliares" value="Neg.">
                                                                                                    <option>Neg.</option>
                                                                                                    <option>Trazas.</option>
                                                                                                    <option>Positivo +</option>
                                                                                                    <option>Positivo ++</option>
                                                                                                    <option>Positivo +++</option>

                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <div class="form-group">
                                                                                                <label for="sales_biliares">SALES BILIARES</label>
                                                                                                <select id="sales_biliares" class="form-control" name="sales_biliares" value="Neg.">
                                                                                                    <option>Neg.</option>
                                                                                                    <option>Trazas.</option>
                                                                                                    <option>Positivo +</option>
                                                                                                    <option>Positivo ++</option>
                                                                                                    <option>Positivo +++</option>

                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-4">
                                                                                            <button class="btn btn-primary">
                                                                                                Enviar
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                        </form>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover" id="tblDetalle">
                                                                                <thead class="thead-dark">
                                                                                    <tr>
                                                                                        <th>Id</th>
                                                                                        <th>ASP.</th>
                                                                                        <th>DEN.</th>
                                                                                        <th>PH.</th>
                                                                                        <th>OLOR.</th>
                                                                                        <th>COL.</th>
                                                                                        <th>NIT.</th>
                                                                                        <th>PRO.</th>
                                                                                        <th>GLU.</th>
                                                                                        <th>CET.</th>
                                                                                        <th>UROB.</th>
                                                                                        <th>BILI.</th>
                                                                                        <th>HEMO.</th>
                                                                                        <th>PIG B.</th>
                                                                                        <th>SAL B.</th>
                                                                                        <th>ACCION</th>
                                                                                    </tr>
                                                                                </thead>

                                                                                <tbody id="detalle_orina" style="background: rgba(255, 255, 255, 0.70)">

                                                                                </tbody>
                                                                            </table>

                                                                        </div>

                                                                        <div class="card">
                                                                            <div class="card-header bg-primary text-white text-center">
                                                                                EXAMEN DE MICROSCOPIO
                                                                            </div>
                                                                            <form onsubmit="crearExamisc(event)">
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-3">
                                                                                            <div class="form-group">
                                                                                                <label for="celulas_ep_planas">CELULAS EP PLANAS</label>
                                                                                                <select id="celulas_ep_planas" class="form-control" name="celulas_ep_planas">
                                                                                                    <option>ESCASAS</option>
                                                                                                    <option>MODERADAS</option>
                                                                                                    <option>ABUNDANTES</option>

                                                                                                </select>
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-3">
                                                                                            <div class="form-group">
                                                                                                <label for="bacterias">BACTERIAS</label>
                                                                                                <select id="bacterias" class="form-control" name="bacterias">
                                                                                                    <option>ESCASAS</option>
                                                                                                    <option>MODERADAS</option>
                                                                                                    <option>ABUNDANTES</option>
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-3">
                                                                                            <div class="form-group">
                                                                                                <label for="leucocitos">LEUCOCITOS</label>
                                                                                                <input id="leucocitos" class="form-control" type="text" name="leucocitos">
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-3">
                                                                                            <div class="form-group">
                                                                                                <label for="hematies">HEMATIES</label>
                                                                                                <input id="hematies" class="form-control" type="text" name="hematies">
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-3">
                                                                                            <div class="form-group">
                                                                                                <label for="mucina">MUCINA</label>
                                                                                                <input id="mucina" class="form-control" type="text" name="mucina">
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-3">
                                                                                            <div class="form-group">
                                                                                                <label for="celulas_renales">CELULAS RENALES</label>
                                                                                                <input id="celulas_renales" class="form-control" type="text" name="celulas_renales">
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-3">
                                                                                            <div class="form-group">
                                                                                                <label for="cristales">CRISTALES</label>
                                                                                                <input id="cristales" class="form-control" type="text" name="cristales">
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-3">
                                                                                            <div class="form-group">
                                                                                                <label for="otros">OTROS</label>
                                                                                                <input id="otros" class="form-control" type="text" name="otros">
                                                                                                <input id="id" type="hidden" name="id">
                                                                                            </div>
                                                                                        </div>
                                                                                        <button class="btn btn-primary">
                                                                                            Enviar
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                        </form>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover" id="tblDetalle">
                                                                                <thead class="thead-dark">
                                                                                    <tr>
                                                                                        <th>Id</th>
                                                                                        <th>CELULAS EP PLANAS.</th>
                                                                                        <th>BACTERIAS.</th>
                                                                                        <th>LEUCOCITOS.</th>
                                                                                        <th>HEMATIES.</th>
                                                                                        <th>MUCINA.</th>
                                                                                        <th>CELULAS RENALES.</th>
                                                                                        <th>CRISTALES.</th>
                                                                                        <th>OTROS.</th>
                                                                                        <th>ACCION</th>
                                                                                    </tr>
                                                                                </thead>

                                                                                <tbody id="detalle_orinamisc" style="background: rgba(255, 255, 255, 0.70)">

                                                                                </tbody>
                                                                            </table>
                                                                            <div class="card">
                                                                                <div class="col-12 card-header bg-primary text-white text-center">
                                                                                    INMUNOSEROLOGIA
                                                                                </div>
                                                                                <form onsubmit="crearInmunoserologia(event)">
                                                                                    <div class="card-body">
                                                                                        <div class="row">


                                                                                            <div class="col-md-4">
                                                                                                <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                                                    <label>EXAMEN</label>
                                                                                                    <input id="inmunoserologia" class="form-control" type="text" name="inmunoserologia">


                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-4">
                                                                                                <div class="form-group">
                                                                                                    <label>VALOR UNIDAD</label>
                                                                                                    <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-lg-4">
                                                                                                <div class="form-group">
                                                                                                    <label>VALOR REFERNCIAL</label>
                                                                                                    <input id="valor_referencialinmu" class="form-control" type="text" name="valor_referencialinmu">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-4">
                                                                                                <button class="btn btn-primary">
                                                                                                    Enviar
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                            </div>
                                                                            </form>
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover" id="tblDetalle">
                                                                                    <thead class="thead-dark">
                                                                                        <tr>
                                                                                            <th>Id</th>
                                                                                            <th>EXAMEN</th>
                                                                                            <th>VALOR UNIDAD</th>
                                                                                            <th>VALOR REFERENCIAL</th>
                                                                                            <th>ACCION</th>
                                                                                        </tr>
                                                                                    </thead>

                                                                                    <tbody id="detalle_inmunoserologia" style="background: rgba(255, 255, 255, 0.70)">

                                                                                    </tbody>
                                                                                </table>
                                                                            </div>

                                                                        <?php
                                                                    } elseif ($form_section['categoria'] == 'PERFIL PRENATAL') {
                                                                        ?>
                                                                            <div class="card">
                                                                                <div class="card-header bg-primary text-white text-center">
                                                                                    HEMATOLOGIA
                                                                                </div>
                                                                                <form onsubmit="crearHema(event)">
                                                                                    <div class="card-body">
                                                                                        <div class="row">

                                                                                            <div class="col-lg-4">
                                                                                                <div class="form-group">
                                                                                                    <label for="hemoglobina">HEMOGLOBINA</label>
                                                                                                    <input id="hemoglobina" class="form-control" type="text" name="hemoglobina" placeholder="">
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="hematocritos">HEMATROCITOS</label>
                                                                                                    <input id="hematocritos" class="form-control" type="text" name="hematocritos" placeholder="">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="cuentas_blancas">CUENTAS BLANCAS</label>
                                                                                                    <input id="cuentas_blancas" class="form-control" type="text" name="cuentas_blancas" placeholder="">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="plaquetas">PLAQUETAS</label>
                                                                                                    <input id="plaquetas" class="form-control" type="text" name="plaquetas" placeholder="">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="vsg">VSG</label>
                                                                                                    <input id="vsg" class="form-control" type="text" name="vsg" placeholder="">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-4">
                                                                                                <button class="btn btn-primary">
                                                                                                    Enviar
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover" id="tblDetalle">
                                                                                <thead class="thead-dark">
                                                                                    <tr>
                                                                                        <th>Id</th>
                                                                                        <th>Hemoglobina</th>
                                                                                        <th>Hematrocitos</th>
                                                                                        <th>Cuentas Blancas</th>
                                                                                        <th>Plaquetas</th>
                                                                                        <th>VSG</th>
                                                                                        <th>Accion</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody id="detalle_hematologia" style="background: rgba(255, 255, 255, 0.70)">

                                                                                </tbody>
                                                                            </table>

                                                                        </div>



                                                                        <div class="card">
                                                                            <div class="card-header bg-primary text-white text-center">
                                                                                FORMULA LEUCOCITARIA
                                                                            </div>
                                                                            <form onsubmit="crearLeuco(event)">
                                                                                <div class="card-body">
                                                                                    <div class="row" style="text-align:center;">
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group">
                                                                                                <label for="seg">SEG</label>
                                                                                                <input id="seg" class="form-control amt" type="text" name="seg">

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group">
                                                                                                <label for="linf">LINF</label>
                                                                                                <input id="linf" class="form-control amt" type="text" name="linf">

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group">
                                                                                                <label for="eosin">EOSIN</label>
                                                                                                <input id="eosin" class="form-control amt" type="text" name="eosin">

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group">
                                                                                                <label for="monoc">MONOC</label>
                                                                                                <input id="monoc" class="form-control amt" type="text" name="monoc">

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group">
                                                                                                <label for="basof">BASOF</label>
                                                                                                <input id="basof" class="form-control amt" type="text" name="basof">

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group">
                                                                                                <label for="otros">OTROS</label>
                                                                                                <input id="otros" class="form-control amt" type="text" name="otros">

                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group">
                                                                                                <label for="total">TOTAL</label>
                                                                                                <input id="total" action="post" class="form-control" type="text" name="total">

                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-lg-4">
                                                                                            <button class="btn btn-primary">
                                                                                                Enviar
                                                                                            </button>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>

                                                                        </div>
                                                                        </form>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover" id="tblDetalle">
                                                                                <thead class="thead-dark">
                                                                                    <tr>
                                                                                        <th>Id</th>
                                                                                        <th>SEG</th>
                                                                                        <th>LINF</th>
                                                                                        <th>EOSIN</th>
                                                                                        <th>MONOC</th>
                                                                                        <th>BASOF</th>
                                                                                        <th>OTROS</th>
                                                                                        <th>TOTAL</th>
                                                                                        <th>Accion</th>
                                                                                    </tr>
                                                                                </thead>

                                                                                <tbody id="detalle_leuco" style="background: rgba(255, 255, 255, 0.70)">

                                                                                </tbody>
                                                                            </table>
                                                                            <div class="card">
                                                                                <div class="col-12 card-header bg-primary text-white text-center">
                                                                                    GRUPO SANGUINEO
                                                                                </div>
                                                                                <form onsubmit="crearGruposanguineo(event)">
                                                                                    <div class="card-body">
                                                                                        <div class="row">
                                                                                            <div class="col-md-4">
                                                                                                <div class="form-group">
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                    <label>GRUPO SANGUINEO</label>
                                                                                                    <input id="grupo" class="form-control" type="text" name="grupo">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-4">
                                                                                                <div class="form-group">
                                                                                                    <label>FACTOR RH</label>
                                                                                                    <input id="factor" class="form-control" type="text" name="factor">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-4">
                                                                                                <button class="btn btn-primary">
                                                                                                    Enviar
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover" id="tblDetalle">
                                                                                    <thead class="thead-dark">
                                                                                        <tr>
                                                                                            <th>Id</th>
                                                                                            <th>GRUPO SANGUINEO</th>
                                                                                            <th>FACTOR RH</th>
                                                                                            <th>ACCION</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody id="detalle_gruposanguineo" style="background: rgba(255, 255, 255, 0.70)">
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                            <div class="card">
                                                                                <div class="col-12 card-header bg-primary text-white text-center">
                                                                                    QUIMICA
                                                                                </div>
                                                                                <form onsubmit="crearQuimi(event)">
                                                                                    <div class="card-body">
                                                                                        <div class="row">


                                                                                            <div class="col-md-3">
                                                                                                <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                                                    <label>EXAMEN</label>
                                                                                                    <input id="examen" class="form-control" type="text" name="examen">


                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-3">
                                                                                                <div class="form-group">
                                                                                                    <label>VALOR UNIDAD</label>
                                                                                                    <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-lg-3">
                                                                                                <div class="form-group">
                                                                                                    <label>VALOR REFERNCIAL</label>
                                                                                                    <input id="valor_referencial1" class="form-control" type="text" name="valor_referencial1">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-3">
                                                                                                <div class="form-group">
                                                                                                    <label>VALOR REFERENCIAL</label>
                                                                                                    <input id="valor_referencial2" class="form-control" type="text" name="valor_referencial2">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-4">
                                                                                                <button class="btn btn-primary">
                                                                                                    Enviar
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                            </div>
                                                                            </form>
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover" id="tblDetalle">
                                                                                    <thead class="thead-dark">
                                                                                        <tr>
                                                                                            <th>Id</th>
                                                                                            <th>EXAMEN</th>
                                                                                            <th>VALOR UNIDAD</th>
                                                                                            <th>VALOR REFERENCIAL</th>
                                                                                            <th>VALOR REFERENCIAL</th>
                                                                                            <th>ACCION</th>
                                                                                        </tr>
                                                                                    </thead>

                                                                                    <tbody id="detalle_quimica" style="background: rgba(255, 255, 255, 0.70)">

                                                                                    </tbody>
                                                                                </table>

                                                                            </div>
                                                                            <div class="card">
                                                                                <div class="card-header bg-primary text-white text-center">
                                                                                    EXAMEN DE ORINA
                                                                                </div>
                                                                                <form onsubmit="crearOrina(event)">
                                                                                    <div class="card-body">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="aspecto">ASPECTO</label>
                                                                                                    <select id="aspecto" class="form-control" name="aspecto" value="Lig.Turbio">
                                                                                                        <option>Lig. Turbio</option>
                                                                                                        <option>Turbio</option>
                                                                                                        <option>Purulento</option>
                                                                                                    </select>
                                                                                                    <input id="id" type="hidden" name="id">

                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="densidad">DENSIDAD</label>
                                                                                                    <select id="densidad" class="form-control" name="densidad" value="1.000">
                                                                                                        <option>1.000</option>
                                                                                                        <option>1.005</option>
                                                                                                        <option>1.010</option>
                                                                                                        <option>1.015</option>
                                                                                                        <option>1.020</option>
                                                                                                        <option>1.025</option>
                                                                                                        <option>1.030</option>
                                                                                                    </select>
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="ph">P.H</label>
                                                                                                    <select id="ph" class="form-control" name="ph" value="6.0">
                                                                                                        <option>5.0</option>
                                                                                                        <option>6.0</option>
                                                                                                        <option>6.5</option>
                                                                                                        <option>7.0</option>
                                                                                                        <option>7.5</option>
                                                                                                        <option>8.0</option>
                                                                                                        <option>8.5</option>
                                                                                                        <option>9.0</option>
                                                                                                    </select>
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="olor">OLOR</label>
                                                                                                    <select id="olor" class="form-control" name="olor" value="S.G">
                                                                                                        <option>S.G.</option>
                                                                                                        <option>Amoniacal.</option>

                                                                                                    </select>
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="color">COLOR</label>
                                                                                                    <select id="color" class="form-control" name="color" value="Amarillo">
                                                                                                        <option>Amarillo.</option>
                                                                                                        <option>Amarillo intenso.</option>
                                                                                                        <option>Ambar.</option>
                                                                                                        <option>Rojiza.</option>
                                                                                                        <option>Medicamentoza.</option>

                                                                                                    </select>
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="nitritos">NITRITOS</label>
                                                                                                    <select id="nitritos" class="form-control" name="nitritos" value="Neg.">
                                                                                                        <option>Neg.</option>
                                                                                                        <option>Trazas.</option>
                                                                                                        <option>Positivo.</option>

                                                                                                    </select>

                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="proteinas">PROTEINAS</label>
                                                                                                    <select id="proteinas" class="form-control" name="proteinas" value="Neg.">
                                                                                                        <option>Neg.</option>
                                                                                                        <option>Trazas.</option>
                                                                                                        <option>Positivo +</option>
                                                                                                        <option>Positivo ++</option>
                                                                                                        <option>Positivo +++</option>

                                                                                                    </select>

                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="glucosa">GLUCOSA</label>
                                                                                                    <select id="glucosa" class="form-control" name="glucosa" value="Neg.">
                                                                                                        <option>Neg.</option>
                                                                                                        <option>Trazas.</option>
                                                                                                        <option>Positivo +</option>
                                                                                                        <option>Positivo ++</option>
                                                                                                        <option>Positivo +++</option>

                                                                                                    </select>
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="cetonas">CETONAS</label>
                                                                                                    <select id="cetonas" class="form-control" name="cetonas" value="Neg.">
                                                                                                        <option>Neg.</option>
                                                                                                        <option>Trazas.</option>
                                                                                                        <option>Positivo.</option>

                                                                                                    </select>
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="urobilinogeno">UROBILINOGENO</label>
                                                                                                    <select id="urobilinogeno" class="form-control" name="urobilinogeno" value="Neg.">
                                                                                                        <option>Neg.</option>
                                                                                                        <option>Trazas.</option>
                                                                                                        <option>Positivo +</option>
                                                                                                        <option>Positivo ++</option>
                                                                                                        <option>Positivo +++</option>

                                                                                                    </select>
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="bilirrubina">BILIRRUBINA</label>
                                                                                                    <select id="bilirrubina" class="form-control" name="bilirrubina" value="Neg.">
                                                                                                        <option>Neg.</option>
                                                                                                        <option>Trazas.</option>
                                                                                                        <option>Positivo +</option>
                                                                                                        <option>Positivo ++</option>
                                                                                                        <option>Positivo +++</option>

                                                                                                    </select>
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="hemoglobina">HEMOGLOBINA</label>
                                                                                                    <select id="hemoglobina" class="form-control" name="hemoglobina" value="Neg.">
                                                                                                        <option>Neg.</option>
                                                                                                        <option>Trazas.</option>
                                                                                                        <option>Positivo +</option>
                                                                                                        <option>Positivo ++</option>
                                                                                                        <option>Positivo +++</option>

                                                                                                    </select>
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="pigmen_biliares">PIGMENTOS BILIARES</label>
                                                                                                    <select id="pigmen_biliares" class="form-control" name="pigmen_biliares" value="Neg.">
                                                                                                        <option>Neg.</option>
                                                                                                        <option>Trazas.</option>
                                                                                                        <option>Positivo +</option>
                                                                                                        <option>Positivo ++</option>
                                                                                                        <option>Positivo +++</option>

                                                                                                    </select>
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="sales_biliares">SALES BILIARES</label>
                                                                                                    <select id="sales_biliares" class="form-control" name="sales_biliares" value="Neg.">
                                                                                                        <option>Neg.</option>
                                                                                                        <option>Trazas.</option>
                                                                                                        <option>Positivo +</option>
                                                                                                        <option>Positivo ++</option>
                                                                                                        <option>Positivo +++</option>

                                                                                                    </select>
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-4">
                                                                                                <button class="btn btn-primary">
                                                                                                    Enviar
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                            </div>
                                                                            </form>
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover" id="tblDetalle">
                                                                                    <thead class="thead-dark">
                                                                                        <tr>
                                                                                            <th>Id</th>
                                                                                            <th>ASP.</th>
                                                                                            <th>DEN.</th>
                                                                                            <th>PH.</th>
                                                                                            <th>OLOR.</th>
                                                                                            <th>COL.</th>
                                                                                            <th>NIT.</th>
                                                                                            <th>PRO.</th>
                                                                                            <th>GLU.</th>
                                                                                            <th>CET.</th>
                                                                                            <th>UROB.</th>
                                                                                            <th>BILI.</th>
                                                                                            <th>HEMO.</th>
                                                                                            <th>PIG B.</th>
                                                                                            <th>SAL B.</th>
                                                                                            <th>ACCION</th>
                                                                                        </tr>
                                                                                    </thead>

                                                                                    <tbody id="detalle_orina" style="background: rgba(255, 255, 255, 0.70)">

                                                                                    </tbody>
                                                                                </table>

                                                                            </div>

                                                                            <div class="card">
                                                                                <div class="card-header bg-primary text-white text-center">
                                                                                    EXAMEN DE MICROSCOPIO
                                                                                </div>
                                                                                <form onsubmit="crearExamisc(event)">
                                                                                    <div class="card-body">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-3">
                                                                                                <div class="form-group">
                                                                                                    <label for="celulas_ep_planas">CELULAS EP PLANAS</label>
                                                                                                    <select id="celulas_ep_planas" class="form-control" name="celulas_ep_planas">
                                                                                                        <option>ESCASAS</option>
                                                                                                        <option>MODERADAS</option>
                                                                                                        <option>ABUNDANTES</option>

                                                                                                    </select>
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-3">
                                                                                                <div class="form-group">
                                                                                                    <label for="bacterias">BACTERIAS</label>
                                                                                                    <select id="bacterias" class="form-control" name="bacterias">
                                                                                                        <option>ESCASAS</option>
                                                                                                        <option>MODERADAS</option>
                                                                                                        <option>ABUNDANTES</option>
                                                                                                        <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-3">
                                                                                                <div class="form-group">
                                                                                                    <label for="leucocitos">LEUCOCITOS</label>
                                                                                                    <input id="leucocitos" class="form-control" type="text" name="leucocitos">
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-3">
                                                                                                <div class="form-group">
                                                                                                    <label for="hematies">HEMATIES</label>
                                                                                                    <input id="hematies" class="form-control" type="text" name="hematies">
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-3">
                                                                                                <div class="form-group">
                                                                                                    <label for="mucina">MUCINA</label>
                                                                                                    <input id="mucina" class="form-control" type="text" name="mucina">
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-3">
                                                                                                <div class="form-group">
                                                                                                    <label for="celulas_renales">CELULAS RENALES</label>
                                                                                                    <input id="celulas_renales" class="form-control" type="text" name="celulas_renales">
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-3">
                                                                                                <div class="form-group">
                                                                                                    <label for="cristales">CRISTALES</label>
                                                                                                    <input id="cristales" class="form-control" type="text" name="cristales">
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-3">
                                                                                                <div class="form-group">
                                                                                                    <label for="otros">OTROS</label>
                                                                                                    <input id="otros" class="form-control" type="text" name="otros">
                                                                                                    <input id="id" type="hidden" name="id">
                                                                                                </div>
                                                                                            </div>
                                                                                            <button class="btn btn-primary">
                                                                                                Enviar
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                            </div>
                                                                            </form>
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover" id="tblDetalle">
                                                                                    <thead class="thead-dark">
                                                                                        <tr>
                                                                                            <th>Id</th>
                                                                                            <th>CELULAS EP PLANAS.</th>
                                                                                            <th>BACTERIAS.</th>
                                                                                            <th>LEUCOCITOS.</th>
                                                                                            <th>HEMATIES.</th>
                                                                                            <th>MUCINA.</th>
                                                                                            <th>CELULAS RENALES.</th>
                                                                                            <th>CRISTALES.</th>
                                                                                            <th>OTROS.</th>
                                                                                            <th>ACCION</th>
                                                                                        </tr>
                                                                                    </thead>

                                                                                    <tbody id="detalle_orinamisc" style="background: rgba(255, 255, 255, 0.70)">

                                                                                    </tbody>
                                                                                </table>

                                                                                <div class="card">
                                                                                    <div class="col-12 card-header bg-primary text-white text-center">
                                                                                        INMUNOSEROLOGIA
                                                                                    </div>
                                                                                    <form onsubmit="crearInmunoserologia(event)">
                                                                                        <div class="card-body">
                                                                                            <div class="row">


                                                                                                <div class="col-md-4">
                                                                                                    <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                                                        <label>EXAMEN</label>
                                                                                                        <input id="inmunoserologia" class="form-control" type="text" name="inmunoserologia">


                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-lg-4">
                                                                                                    <div class="form-group">
                                                                                                        <label>VALOR UNIDAD</label>
                                                                                                        <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="col-lg-4">
                                                                                                    <div class="form-group">
                                                                                                        <label>VALOR REFERNCIAL</label>
                                                                                                        <input id="valor_referencialinmu" class="form-control" type="text" name="valor_referencialinmu">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-lg-4">
                                                                                                    <button class="btn btn-primary">
                                                                                                        Enviar
                                                                                                    </button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>
                                                                                </form>
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-hover" id="tblDetalle">
                                                                                        <thead class="thead-dark">
                                                                                            <tr>
                                                                                                <th>Id</th>
                                                                                                <th>EXAMEN</th>
                                                                                                <th>VALOR UNIDAD</th>
                                                                                                <th>VALOR REFERENCIAL</th>
                                                                                                <th>ACCION</th>
                                                                                            </tr>
                                                                                        </thead>

                                                                                        <tbody id="detalle_inmunoserologia" style="background: rgba(255, 255, 255, 0.70)">

                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>

                                                                            <?php
                                                                        } elseif ($form_section['categoria'] == 'PERFIL COVID 19') {
                                                                            ?>
                                                                                <div class="card">
                                                                                    <div class="card-header bg-primary text-white text-center">
                                                                                        HEMATOLOGIA
                                                                                    </div>
                                                                                    <form onsubmit="crearHema(event)">
                                                                                        <div class="card-body">
                                                                                            <div class="row">

                                                                                                <div class="col-lg-4">
                                                                                                    <div class="form-group">
                                                                                                        <label for="hemoglobina">HEMOGLOBINA</label>
                                                                                                        <input id="hemoglobina" class="form-control" type="text" name="hemoglobina" placeholder="">
                                                                                                        <input id="id" type="hidden" name="id">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-lg-2">
                                                                                                    <div class="form-group">
                                                                                                        <label for="hematocritos">HEMATROCITOS</label>
                                                                                                        <input id="hematocritos" class="form-control" type="text" name="hematocritos" placeholder="">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-lg-2">
                                                                                                    <div class="form-group">
                                                                                                        <label for="cuentas_blancas">CUENTAS BLANCAS</label>
                                                                                                        <input id="cuentas_blancas" class="form-control" type="text" name="cuentas_blancas" placeholder="">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-lg-2">
                                                                                                    <div class="form-group">
                                                                                                        <label for="plaquetas">PLAQUETAS</label>
                                                                                                        <input id="plaquetas" class="form-control" type="text" name="plaquetas" placeholder="">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-lg-2">
                                                                                                    <div class="form-group">
                                                                                                        <label for="vsg">VSG</label>
                                                                                                        <input id="vsg" class="form-control" type="text" name="vsg" placeholder="">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-lg-4">
                                                                                                    <button class="btn btn-primary">
                                                                                                        Enviar
                                                                                                    </button>
                                                                                                </div>
                                                                                            </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover" id="tblDetalle">
                                                                                    <thead class="thead-dark">
                                                                                        <tr>
                                                                                            <th>Id</th>
                                                                                            <th>Hemoglobina</th>
                                                                                            <th>Hematrocitos</th>
                                                                                            <th>Cuentas Blancas</th>
                                                                                            <th>Plaquetas</th>
                                                                                            <th>VSG</th>
                                                                                            <th>Accion</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody id="detalle_hematologia" style="background: rgba(255, 255, 255, 0.70)">

                                                                                    </tbody>
                                                                                </table>

                                                                            </div>



                                                                            <div class="card">
                                                                                <div class="card-header bg-primary text-white text-center">
                                                                                    FORMULA LEUCOCITARIA
                                                                                </div>
                                                                                <form onsubmit="crearLeuco(event)">
                                                                                    <div class="card-body">
                                                                                        <div class="row" style="text-align:center;">
                                                                                            <div class="col-md-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="seg">SEG</label>
                                                                                                    <input id="seg" class="form-control amt" type="text" name="seg">

                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="linf">LINF</label>
                                                                                                    <input id="linf" class="form-control amt" type="text" name="linf">

                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="eosin">EOSIN</label>
                                                                                                    <input id="eosin" class="form-control amt" type="text" name="eosin">

                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="monoc">MONOC</label>
                                                                                                    <input id="monoc" class="form-control amt" type="text" name="monoc">

                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="basof">BASOF</label>
                                                                                                    <input id="basof" class="form-control amt" type="text" name="basof">

                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="otros">OTROS</label>
                                                                                                    <input id="otros" class="form-control amt" type="text" name="otros">

                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-md-2">
                                                                                                <div class="form-group">
                                                                                                    <label for="total">TOTAL</label>
                                                                                                    <input id="total" action="post" class="form-control" type="text" name="total">

                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-lg-4">
                                                                                                <button class="btn btn-primary">
                                                                                                    Enviar
                                                                                                </button>
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>

                                                                            </div>
                                                                            </form>
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover" id="tblDetalle">
                                                                                    <thead class="thead-dark">
                                                                                        <tr>
                                                                                            <th>Id</th>
                                                                                            <th>SEG</th>
                                                                                            <th>LINF</th>
                                                                                            <th>EOSIN</th>
                                                                                            <th>MONOC</th>
                                                                                            <th>BASOF</th>
                                                                                            <th>OTROS</th>
                                                                                            <th>TOTAL</th>
                                                                                            <th>Accion</th>
                                                                                        </tr>
                                                                                    </thead>

                                                                                    <tbody id="detalle_leuco" style="background: rgba(255, 255, 255, 0.70)">

                                                                                    </tbody>
                                                                                </table>
                                                                                <div class="card">
                                                                                    <div class="col-12 card-header bg-primary text-white text-center">
                                                                                        PERFIL DE COAGULACION
                                                                                    </div>
                                                                                    <form onsubmit="crearTiempos(event)">
                                                                                        <div class="card-body">
                                                                                            <div class="row">


                                                                                                <div class="col-md-4">
                                                                                                    <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                                                        <label>TIEMPO DE PROTROMBINA:</label>
                                                                                                        <input id="tp" class="form-control" type="text" name="tp">


                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-lg-4">
                                                                                                    <div class="form-group">
                                                                                                        <label>TIEMPO PARCIAL DE TROMBOPLASTINA:</label>
                                                                                                        <input id="tpt" class="form-control" type="text" name="tpt">
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="col-lg-4">
                                                                                                    <div class="form-group">
                                                                                                        <label>INR</label>
                                                                                                        <input id="inr" class="form-control" type="text" name="inr">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-lg-4">
                                                                                                    <div class="form-group">
                                                                                                        <label>FIBRINOGENO</label>
                                                                                                        <input id="fibrinogeno" class="form-control" type="text" name="fibrinogeno">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-lg-4">
                                                                                                    <button class="btn btn-primary">
                                                                                                        Enviar
                                                                                                    </button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>
                                                                                </form>
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-hover" id="tblDetalle">
                                                                                        <thead class="thead-dark">
                                                                                            <tr>
                                                                                                <th>Id</th>
                                                                                                <th>TP</th>
                                                                                                <th>TPT</th>
                                                                                                <th>INR</th>
                                                                                                <th>FIBRINOGENO</th>
                                                                                                <th>ACCION</th>
                                                                                            </tr>
                                                                                        </thead>

                                                                                        <tbody id="detalle_tiempos" style="background: rgba(255, 255, 255, 0.70)">

                                                                                        </tbody>
                                                                                    </table>
                                                                                    <div class="card">
                                                                                        <div class="col-12 card-header bg-primary text-white text-center">
                                                                                            PRUEBA ESPECIAL
                                                                                        </div>
                                                                                        <form onsubmit="crearPrueba(event)">
                                                                                            <div class="card-body">
                                                                                                <div class="row">


                                                                                                    <div class="col-md-3">
                                                                                                        <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                                                            <label>EXAMEN</label>
                                                                                                            <input id="especial" class="form-control" type="text" name="especial">


                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col-lg-3">
                                                                                                        <div class="form-group">
                                                                                                            <label>VALOR UNIDAD</label>
                                                                                                            <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <div class="col-lg-3">
                                                                                                        <div class="form-group">
                                                                                                            <label>VALOR REFERNCIAL</label>
                                                                                                            <input id="valor_referencial" class="form-control" type="text" name="valor_referencial">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col-lg-3">
                                                                                                        <div class="form-group">
                                                                                                            <label>VALOR REFERENCIAL</label>
                                                                                                            <input id="valor_referencialprueba" class="form-control" type="text" name="valor_referencialprueba">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col-lg-4">
                                                                                                        <button class="btn btn-primary">
                                                                                                            Enviar
                                                                                                        </button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                    </div>
                                                                                    </form>
                                                                                    <div class="table-responsive">
                                                                                        <table class="table table-hover" id="tblDetalle">
                                                                                            <thead class="thead-dark">
                                                                                                <tr>
                                                                                                    <th>Id</th>
                                                                                                    <th>EXAMEN</th>
                                                                                                    <th>VALOR UNIDAD</th>
                                                                                                    <th>VALOR REFERENCIAL</th>
                                                                                                    <th>VALOR REFERENCIAL</th>
                                                                                                    <th>ACCION</th>
                                                                                                </tr>
                                                                                            </thead>

                                                                                            <tbody id="detalle_prueba" style="background: rgba(255, 255, 255, 0.70)">

                                                                                            </tbody>
                                                                                        </table>
                                                                                        <div class="card">
                                                                                            <div class="col-12 card-header bg-primary text-white text-center">
                                                                                                QUIMICA
                                                                                            </div>
                                                                                            <form onsubmit="crearQuimi(event)">
                                                                                                <div class="card-body">
                                                                                                    <div class="row">


                                                                                                        <div class="col-md-3">
                                                                                                            <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                                                                <label>EXAMEN</label>
                                                                                                                <input id="examen" class="form-control" type="text" name="examen">


                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-lg-3">
                                                                                                            <div class="form-group">
                                                                                                                <label>VALOR UNIDAD</label>
                                                                                                                <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <div class="col-lg-3">
                                                                                                            <div class="form-group">
                                                                                                                <label>VALOR REFERNCIAL</label>
                                                                                                                <input id="valor_referencial1" class="form-control" type="text" name="valor_referencial1">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-lg-3">
                                                                                                            <div class="form-group">
                                                                                                                <label>VALOR REFERENCIAL</label>
                                                                                                                <input id="valor_referencial2" class="form-control" type="text" name="valor_referencial2">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-lg-4">
                                                                                                            <button class="btn btn-primary">
                                                                                                                Enviar
                                                                                                            </button>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                        </div>
                                                                                        </form>
                                                                                        <div class="table-responsive">
                                                                                            <table class="table table-hover" id="tblDetalle">
                                                                                                <thead class="thead-dark">
                                                                                                    <tr>
                                                                                                        <th>Id</th>
                                                                                                        <th>EXAMEN</th>
                                                                                                        <th>VALOR UNIDAD</th>
                                                                                                        <th>VALOR REFERENCIAL</th>
                                                                                                        <th>VALOR REFERENCIAL</th>
                                                                                                        <th>ACCION</th>
                                                                                                    </tr>
                                                                                                </thead>

                                                                                                <tbody id="detalle_quimica" style="background: rgba(255, 255, 255, 0.70)">

                                                                                                </tbody>
                                                                                            </table>
                                                                                            <div class="card">
                                                                                                <div class="col-12 card-header bg-primary text-white text-center">
                                                                                                    INMUNOSEROLOGIA
                                                                                                </div>
                                                                                                <form onsubmit="crearInmunoserologia(event)">
                                                                                                    <div class="card-body">
                                                                                                        <div class="row">


                                                                                                            <div class="col-md-4">
                                                                                                                <div class="form-group"> <input id="id" type="hidden" name="id">
                                                                                                                    <label>EXAMEN</label>
                                                                                                                    <input id="inmunoserologia" class="form-control" type="text" name="inmunoserologia">


                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-lg-4">
                                                                                                                <div class="form-group">
                                                                                                                    <label>VALOR UNIDAD</label>
                                                                                                                    <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="col-lg-4">
                                                                                                                <div class="form-group">
                                                                                                                    <label>VALOR REFERNCIAL</label>
                                                                                                                    <input id="valor_referencialinmu" class="form-control" type="text" name="valor_referencialinmu">
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-lg-4">
                                                                                                                <button class="btn btn-primary">
                                                                                                                    Enviar
                                                                                                                </button>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                            </div>
                                                                                            </form>
                                                                                            <div class="table-responsive">
                                                                                                <table class="table table-hover" id="tblDetalle">
                                                                                                    <thead class="thead-dark">
                                                                                                        <tr>
                                                                                                            <th>Id</th>
                                                                                                            <th>EXAMEN</th>
                                                                                                            <th>VALOR UNIDAD</th>
                                                                                                            <th>VALOR REFERENCIAL</th>
                                                                                                            <th>ACCION</th>
                                                                                                        </tr>
                                                                                                    </thead>

                                                                                                    <tbody id="detalle_inmunoserologia" style="background: rgba(255, 255, 255, 0.70)">

                                                                                                    </tbody>
                                                                                                </table>


                                                                                    <?php





                                                                                }
                                                                            }
                                                                        }
                                                                                    ?>

                                                                                    <div class="col-md-6">
                                                                                        <button class="btn btn-primary" id="btn_resultados"><i class="fas fa-save"></i> Generar Resultados</button>
                                                                                    </div>


                                                                                    <?php include_once "includes/footerlab.php"; ?>