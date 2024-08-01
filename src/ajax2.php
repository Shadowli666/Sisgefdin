<?php
require_once "../conexion.php";
session_start();
if (isset($_GET['q'])) {
    $datos = array();
    $cedula = $_GET['q'];
    $cliente = mysqli_query($conexion, "SELECT * FROM cliente WHERE cedula LIKE '%$cedula%'");
    while ($row = mysqli_fetch_assoc($cliente)) {
        $data['id'] = $row['idcliente'];
        $data['nombre'] = $row['nombre'];
        $data['segun_nombre'] = $row['segun_nombre'];
        $data['apellido'] = $row['apellido'];
        $data['segun_apellido'] = $row['segun_apellido'];
        $data['label'] = $row['cedula'];
        $data['edad'] = $row['edad'];
        $data['fecha_nac'] = $row['fecha_nac'];
        $data['genero'] = $row['genero'];
        $data['telefono'] = $row['telefono'];
        $data['direccion'] = $row['direccion'];
        $data['parroquia'] = $row['parroquia'];
        $data['municipio'] = $row['municipio'];
        $data['estado'] = $row['estado'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['c'])) {
    $dato = array();
    $cedulas = $_GET['c'];
    $clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE cedulas LIKE '%$cedulas%'");
    while ($row = mysqli_fetch_assoc($clientes)) {
        $data['id'] = $row['idcliente'];
        $data['nombre'] = $row['nombre'];
        $data['segun_nombre'] = $row['segun_nombre'];
        $data['apellido'] = $row['apellido'];
        $data['segun_apellido'] = $row['segun_apellido'];
        $data['label'] = $row['cedula'];
        $data['edad'] = $row['edad'];
        $data['fecha_nac'] = $row['fecha_nac'];
        $data['genero'] = $row['genero'];
        $data['telefono'] = $row['telefono'];
        $data['direccion'] = $row['direccion'];
        $data['parroquia'] = $row['parroquia'];
        $data['municipio'] = $row['municipio'];
        $data['estado'] = $row['estado'];
        array_push($dato, $data);
    }
    echo json_encode($dato);
    die();
} else if (isset($_GET['s'])) {
    $datos = array();
    $nombre = $_GET['s'];

    $servicios = mysqli_query($conexion, "SELECT * FROM servicios INNER JOIN tasa WHERE servicios.especialidad  LIKE '%" . $nombre . "%' OR descripcion LIKE '%" . $nombre . "%'");
    // $getcotiza = mysqli_query($conexion, "SELECT tasa.id,servicios.descripcion from tasa INNER JOIN servicios ON servicios.idservicios=tasa.id");
    while ($row = mysqli_fetch_assoc($servicios)) {
        $data['id'] = $row['idservicios'];
        $data['label'] = $row['especialidad'] . ' - ' . $row['descripcion'];
        $data['value'] = $row['descripcion'];
        $data['especialidad'] = $row['especialidad'];
		$data['existencia'] = $row['existencia'];
        $data['precio'] = $row['precio'];
        $data['cotizacion'] = $row['cotizacion'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['m'])) {
    $datos = array();
    $nombre = $_GET['m'];

    $metodo_pago = mysqli_query($conexion, "SELECT * FROM metodo_pago");
    // $getcotiza = mysqli_query($conexion, "SELECT tasa.id,servicios.descripcion from tasa INNER JOIN servicios ON servicios.idservicios=tasa.id");
    while ($row = mysqli_fetch_assoc($metodo_pago)) {
        $data['id'] = $row['id'];
        $data['label'] = $row['id'] . ' - ' . $row['metodo_pago'];
        $data['value'] = $row['metodo_pago'];
         
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['detalle'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT d.*, s.idservicios, s.descripcion, ta.cotizacion FROM detalle_temps d LEFT JOIN tasa ta ON  ta.id = 1 INNER JOIN servicios s ON d.id_servicios = s.idservicios  WHERE d.id_usuario = $id");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['descripcion'] = $row['descripcion'];
        $data['especialidad'] = $row['especialidad'];
        $data['especialista'] = $row['especialista'];
        $data['cantidad'] = $row['cantidad'];
        $data['descuento'] = $row['descuento'];
        $data['precio_venta'] = $row['precio_venta'];
        $data['sub_total'] = $row['total'];
        $data['sub_totalbs'] = $row['total'] * $row['cotizacion'];
        $data['cotizacion'] = $row['cotizacion'];
        $data['obser'] = $row['obser'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['delete_detalle'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM detalle_temps WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['delete_pago'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM pagos_temp WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        echo mysqli_error($conexion);
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['procesarVenta'])) {
    $id_cliente = $_GET['id'];
    $id_user = $_SESSION['idUser'];
    $consulta = mysqli_query($conexion, "SELECT total,totalbs, SUM(total) AS total_pagar, SUM(totalbs) AS total_pagarbs FROM detalle_temps WHERE id_usuario = $id_user");
    $result = mysqli_fetch_assoc($consulta);
    $total = $result['total_pagar'];
    $totalbs = $result['total_pagarbs'];
    
    $msg = "";
    $insertar = mysqli_query($conexion, "INSERT INTO venta (id_cliente, total, totalbs, id_usuario) VALUES ($id_cliente, '$total', '$totalbs', $id_user)");
    if ($insertar) {
        $id_maximo = mysqli_query($conexion, "SELECT MAX(id) AS total FROM venta");
        $resultId = mysqli_fetch_assoc($id_maximo);
        $ultimoId = $resultId['total'];
        $consultaDetalle = mysqli_query($conexion, "SELECT * FROM detalle_temps
        left join (
            select idservicios, agrupable, descripcion from servicios
        ) as s on s.idservicios = detalle_temps.id_servicios 
        WHERE id_usuario = $id_user");

        $itemsItinerario = [];
        $descripcionTemp = [];
        $especialistaLaboratorio = "";
        $totalLaboratorio = 0;
        $contador = 0;

        while ($row = mysqli_fetch_assoc($consultaDetalle)) {
            $id_servicios = $row['id_servicios'];
            $cantidad = $row['cantidad'];
            $desc = !empty($row['descuento']) ? $row['descuento'] : 0;
            $precio = $row['precio_venta'];
            $total = $row['total'];
            $totalbs = $row['totalbs'];
            $obser = $row['obser'];
            $especialista = $row['especialista'];
            $insertarDet = mysqli_query($conexion, "INSERT INTO detalle_ventas (id_servicios, id_venta, cantidad, precio, descuento, total, totalbs, obser) VALUES ($id_servicios, $ultimoId, $cantidad, '$precio', '$desc', '$total',' $totalbs', '$obser')");
            if (!$insertarDet){
                echo mysqli_error($conexion);
                return;
            }
            $stockActual = mysqli_query($conexion, "SELECT * FROM servicios WHERE idservicios = $id_servicios");
            if(!$stockActual){
                echo mysqli_error($conexion);
                return;
            }
            $stockNuevo = mysqli_fetch_assoc($stockActual);
            $stockTotal = $stockNuevo['existencia'] - $cantidad;
            $stock = mysqli_query($conexion, "UPDATE servicios SET existencia = $stockTotal WHERE idservicios = $id_servicios");
            if(!$stock){
                echo mysqli_error($conexion);
                return;
            }
            
            if ($row['agrupable']) {
                /* ACIDO URICO */
                /* CALCIO */
                array_push($descripcionTemp, $row['descripcion']);
                $totalLaboratorio += floatval($total);
                if ($contador == 0) {
                    $especialistaLaboratorio = $especialista;
                }
                $contador++;
            }else{
                array_push($itemsItinerario, array(
                    "id_cliente" => $id_cliente,
                    "descripcion" => $row['descripcion'],
                    "especialista" => $especialista,
                    "total" => $total,
                ));
            }

        }
        
        $implodeDescripcion = implode(",", $descripcionTemp);

        if ($implodeDescripcion){
            array_push($itemsItinerario, array(
                "id_cliente" => $id_cliente,
                /* ACIDO URICO, CALCIO */
                "descripcion" => $implodeDescripcion,
                "especialista" => $especialistaLaboratorio,
                "total" => $totalLaboratorio,
            ));
        }

        /* Meter $id_cliente en itinerario */

        foreach ($itemsItinerario as $item) {
            $id_cliente = $item["id_cliente"];
            $descripcion = $item["descripcion"];
            $especialista = $item["especialista"];
            $total = $item["total"];
        

            $insertarItinerario = mysqli_query($conexion, "INSERT INTO itinerario (id_cliente,  descripcion, especialista, precios) VALUES ('$id_cliente', '$descripcion', '$especialista', '$total')");
            if (!$insertarItinerario){
                echo mysqli_error($conexion);
                return;
            }
        }
        
        $eliminar = mysqli_query($conexion, "DELETE FROM detalle_temps WHERE id_usuario = $id_user");
        $msg = array('id_cliente' => $id_cliente, 'id_venta' => $ultimoId);
			
	

		$consultaDetalle_pago = mysqli_query($conexion, "SELECT * FROM pagos_temp WHERE id_usuario = $id_user");
        if (!$consultaDetalle_pago){
            echo mysqli_error($conexion);
            return; 
        }
        while ($row = mysqli_fetch_assoc($consultaDetalle_pago)) {
            $id_usuario = $row['id_usuario'];
            $metodo_pago = $row['metodo_pago'];
            $referencia = $row['referencia'];
            $bolivares = floatval($row['bolivares']);
            $dolares = floatval($row['dolares']);
            $obser = $row['obser'];
            $insertarPag = mysqli_query($conexion, "INSERT INTO detalle_pago (id_usuario, id_cliente, id_ventas, metodo_pago, referencia, bolivares, dolares, obser) VALUES ('$id_usuario', '$id_cliente', '$ultimoId', '$metodo_pago', '$referencia', '$bolivares', '$dolares', '$obser')");
            if(!$insertarPag){
                echo mysqli_error($conexion);
                return;  
            }
            if ($insertarPag){
				$eliminar = mysqli_query($conexion, "DELETE FROM pagos_temp WHERE id_usuario = $id_user");
			}
			else{
				echo mysqli_error($conexion);
                return;
			}
			
			
        }
            
            

	
    } else {

        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
} else if (isset($_GET['descuento'])) {
    $id = $_GET['id'];
    $desc = $_GET['desc'];
    $consulta = mysqli_query($conexion, "SELECT * FROM detalle_temps WHERE id = $id");
    $result = mysqli_fetch_assoc($consulta);
    $total_desc = $desc + $result['descuento'];
    $total = $result['total'] - $desc;
    $totalbs = $result['totalbs'] - $desc;
    $insertar = mysqli_query($conexion, "UPDATE detalle_temps SET descuento = $total_desc, total = '$total', totalbs = '$totalbs'  WHERE id = $id");
    if ($insertar) {
        $msg = array('mensaje' => 'descontado');
    } else {
        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
} else if (isset($_GET['obser'])) {
    $id = $_GET['id'];
    $observ = $_GET['observ'];
    $consulta = mysqli_query($conexion, "SELECT * FROM detalle_temps WHERE id = $id");
    $result = mysqli_fetch_assoc($consulta);
    $insertar = mysqli_query($conexion, "UPDATE detalle_temps SET obser = '$observ'   WHERE id = $id");
    if ($insertar) {
        $msg = array('mensaje' => 'observado');
    } else {
        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
} else if (isset($_GET['bolivares'])) {
    $id = $_GET['id'];
    $bolivar = $_GET['bolivar'];
    $consulta = mysqli_query($conexion, "SELECT * FROM detalle_temps WHERE id = $id");
    $result = mysqli_fetch_assoc($consulta);
    $total_b = $bolivar + $result['bolivares'];
    $insertar = mysqli_query($conexion, "UPDATE detalle_temps SET bolivares = '$bolivar' WHERE id = $id");
    if ($insertar) {
        $msg = array('mensaje' => 'cambiado');
    } else {
        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
} else if (isset($_GET['dolares'])) {
    $id = $_GET['id'];
    $dolar = $_GET['dolar'];
    $consulta = mysqli_query($conexion, "SELECT * FROM detalle_temps WHERE id = $id");
    $result = mysqli_fetch_assoc($consulta);
    $total_d = $dolar + $result['dolares'];
    $insertar = mysqli_query($conexion, "UPDATE detalle_temps SET dolares = '$dolar' WHERE id = $id");
    if ($insertar) {
        $msg = array('mensaje' => 'cambio');
    } else {
        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
} else if (isset($_GET['pago'])) {
    $id = $_GET['id'];
    $pagos = $_GET['pagos'];
    $consulta = mysqli_query($conexion, "SELECT * FROM detalle_temps WHERE id = $id");
    $result = mysqli_fetch_assoc($consulta);
    $insertar = mysqli_query($conexion, "UPDATE detalle_temps SET pago = '$pagos'   WHERE id = $id");
    if ($insertar) {
        $msg = array('mensaje' => 'pagado');
    } else {
        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
} else if (isset($_GET['id_taza'])) {
    $id = $_GET['id'];
    $cotizacion = $_GET['cotizacion'];
    $consulta = mysqli_query($conexion, "SELECT * FROM detalle_temps WHERE id = $id");
    $result = mysqli_fetch_assoc($consulta);
    $total_bcv = $cotizacion + $result['id_taza'];
    $totalbs = $result['total'] * $id_taza;
    $insertar = mysqli_query($conexion, "UPDATE detalle_temps SET id_taza = $total_bcv, totalbs = '$totalbs'  WHERE id = $id");
    if ($insertar) {
        $msg = array('mensaje' => 'convertido');
    } else {
        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
} else if (isset($_GET['editarCliente'])) {
    $idcliente = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarUsuario'])) {
    $idusuario = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $idusuario");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarProducto'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarTipo'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM tipos WHERE id = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarTasa'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM tasa WHERE id = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarPresent'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM presentacion WHERE id = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarLab'])) {
    $idservicios = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM laboratorios WHERE id = $idservicios");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
}
if (isset($_POST['regDetalle'])) {
    $id = $_POST['id'];
    $cant = $_POST['cant'];
    $precio = $_POST['precio'];
    $especialidad = $_POST['especialidad'];
    $doctor = $_POST['doctor'];
    $sub_totalbs = $_POST['sub_totalbs'];
    $taza = $_POST['taza'];
    $id_user = $_SESSION['idUser'];
    $total = $precio * $cant;
    $verificar = mysqli_query($conexion, "SELECT * FROM detalle_temps WHERE id_servicios = $id AND id_usuario = $id_user");
    $result = mysqli_num_rows($verificar);
    $datos = mysqli_fetch_assoc($verificar);
    if ($result > 0) {
        $cantidad = $datos['cantidad'] + $cant;
        $especialidad = $datos['especialidad'];
        $doctor = $datos['especialista'];
        $total_precio = ($cantidad * $total);
        // $sub_totalbs= ($sub_totalbs);
        $query = mysqli_query($conexion, "UPDATE detalle_temps SET  especialidad = '$especialidad', especialista = '$doctor', cantidad = $cantidad, total = '$total_precio', totalbs = '$sub_totalbs' WHERE id_servicios = $id AND id_usuario = $id_user ");
        if ($query) {
            $msg = "actualizado";
        } else {
            echo mysqli_error($conexion);
            $msg = "Error al ingresar";
        }
    } else {
        $query = mysqli_query($conexion, "INSERT INTO detalle_temps(id_usuario, id_servicios, especialidad, especialista, cantidad, precio_venta, total, totalbs, taza) VALUES ($id_user, $id, '$especialidad','$doctor', $cant,'$precio', '$total', '$sub_totalbs', '$taza')");
        if ($query) {
            $msg = "registrado";
        } else {
            echo mysqli_error($conexion);
            $msg = "Error al ingresar";
        }
    }
    echo json_encode($msg);
    die();
} else if (isset($_POST['cambio'])) {
    if (empty($_POST['actual']) || empty($_POST['nueva'])) {
        $msg = 'Los campos estan vacios';
    } else {
        $id = $_SESSION['idUser'];
        $actual = md5($_POST['actual']);
        $nueva = md5($_POST['nueva']);
        $consulta = mysqli_query($conexion, "SELECT * FROM usuario WHERE clave = '$actual' AND idusuario = $id");
        $result = mysqli_num_rows($consulta);
        if ($result == 1) {
            $query = mysqli_query($conexion, "UPDATE usuario SET clave = '$nueva' WHERE idusuario = $id");
            if ($query) {
                $msg = 'ok';
            } else {
                $msg = 'error';
            }
        } else {
            $msg = 'dif';
        }
    }
    echo $msg;
    die();
} else if (isset($_GET['detallePago'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT * FROM pagos_temp WHERE id_usuario = '$id'");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['metodo_pago'] = $row['metodo_pago'];
        $data['referencia'] = $row['referencia'];
        $data['bolivares'] = $row['bolivares'];
        $data['dolares'] = $row['dolares'];
        $data['obser'] = $row['obser'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['detalleHema'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT * FROM detalle_hematologia WHERE id_usuario = '$id'");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['hemoglobina'] = $row['hemoglobina'];
        $data['hematrocitos'] = $row['hematrocitos'];
        $data['cuentas_blancas'] = $row['cuentas_blancas'];
        $data['plaquetas'] = $row['plaquetas'];
        $data['vsg'] = $row['vsg'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if(isset($_POST['crearPago'])){
    $metodo_pago = $_POST['metodo_pago'];
    $referencia = $_POST['referencia'];
    $bolivares = $_POST['bolivares'];
    $dolares = $_POST['dolares'];
    $obser = $_POST['obser'];
    $id_user = $_SESSION['idUser'];
    $query = mysqli_query($conexion, "INSERT INTO pagos_temp(id_usuario, metodo_pago, referencia, bolivares, dolares, obser) VALUES ($id_user, '$metodo_pago', '$referencia',$bolivares, $dolares, '$obser')");
    if ($query) {
        $msg = "registrado";
    } else {
        echo mysqli_error($conexion);
        $msg = "Error al ingresar";
    }
    echo json_encode($msg);
    die();
} else if(isset($_POST['crearHema'])){
    $hemoglobina = $_POST['hemoglobina'];
    $hematrocitos = $_POST['hematrocitos'];
    $cuentas_blancas = $_POST['cuentas_blancas'];
    $plaquetas = $_POST['plaquetas'];
    $vsg = $_POST['vsg'];
    $id_user = $_SESSION['idUser'];
    $query = mysqli_query($conexion, "INSERT INTO detalle_hematologia(id_usuario, hemoglobina, hematrocitos, cuentas_blancas, plaquetas, vsg) VALUES ($id_user, '$hemoglobina', '$hematrocitos',$cuentas_blancas, $plaquetas, '$vsg')");
    if ($query) {
        $msg = "registrado";
    } else {
        echo mysqli_error($conexion);
        $msg = "Error al ingresar";
    }
    echo json_encode($msg);
    die();
} else if(isset($_POST['obtenerMedicos'])){
    $especialidad = $_POST['especialidad'];
    $query = mysqli_query($conexion, "SELECT * FROM medicos WHERE especialidad = '$especialidad'");
    $medicos = array();

    while ($row = mysqli_fetch_assoc($query)) {
        array_push($medicos, $row);
    }
    
    if ($query) {
        $msg = "registrado";
    } else {
        echo mysqli_error($conexion);
        $msg = "Error al ingresar";
    }
    echo json_encode($medicos);
    die();
}