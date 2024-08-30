<?php
require_once "../conexion.php";
session_start();
$msg = "";
if (isset($_GET['q'])) {
    $datos = array();
    $nombre = $_GET['q'];
    $cliente = mysqli_query($conexion, "SELECT * FROM cliente WHERE nombre LIKE '%$nombre%'");
    while ($row = mysqli_fetch_assoc($cliente)) {
        $data['id'] = $row['idcliente'];
        $data['label'] = $row['nombre'];
        $data['direccion'] = $row['direccion'];
        $data['telefono'] = $row['telefono'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}else if (isset($_GET['pro'])) {
    $datos = array();
    $nombre = $_GET['pro'];
    $producto = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo LIKE '%" . $nombre . "%' OR descripcion LIKE '%" . $nombre . "%'");
    while ($row = mysqli_fetch_assoc($producto)) {
        $data['id'] = $row['codproducto'];
        $data['label'] = $row['codigo'] . ' - ' .$row['descripcion'];
        $data['value'] = $row['descripcion'];
        $data['precio'] = $row['precio'];
        $data['existencia'] = $row['existencia'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}else if (isset($_GET['qui'])) {
    $datos = array();
    $nombre = $_GET['qui'];
    $producto = mysqli_query($conexion, "SELECT * FROM quimica WHERE id LIKE '%" . $nombre . "%' OR examen LIKE '%" . $nombre . "%'");
    while ($row = mysqli_fetch_assoc($producto)) {
        $data['id'] = $row['id'];
        $data['label'] = $row['id'] . ' - ' .$row['examen'];
        $data['value'] = $row['examen'];
        $data['valor_referencial1'] = $row['valor_referencial1'];
        $data['valor_referencial2'] = $row['valor_referencial2'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}else if (isset($_GET['espe'])) {
    $datos = array();
    $nombre = $_GET['espe'];
    $producto = mysqli_query($conexion, "SELECT * FROM examenpruebaespecial WHERE id LIKE '%" . $nombre . "%' OR examen LIKE '%" . $nombre . "%'");
    while ($row = mysqli_fetch_assoc($producto)) {
        $data['id'] = $row['id'];
        $data['label'] = $row['id'] . ' - ' .$row['examen'];
        $data['value'] = $row['examen'];
        $data['valor_referencial'] = $row['valor_referencial'];
        $data['valor_referencialprueba'] = $row['valor_referencial2'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}else if (isset($_GET['inmu'])) {
    $datos = array();
    $nombre = $_GET['inmu'];
    $producto = mysqli_query($conexion, "SELECT * FROM exameninmunoserologia WHERE id LIKE '%" . $nombre . "%' OR examen LIKE '%" . $nombre . "%'");
    while ($row = mysqli_fetch_assoc($producto)) {
        $data['id'] = $row['id'];
        $data['label'] = $row['id'] . ' - ' .$row['examen'];
        $data['value'] = $row['examen'];
        $data['valor_referencialinmu'] = $row['valor_referencial'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}else if (isset($_GET['detalle'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion FROM detalle_temp d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_usuario = $id");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['descripcion'] = $row['descripcion'];
        $data['cantidad'] = $row['cantidad'];
        $data['descuento'] = $row['descuento'];
        $data['precio_venta'] = $row['precio_venta'];
        $data['sub_total'] = $row['total'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();

    /**
     * Funcion para Mostrar datos de Hematologia ya almacenados previamente
     * TODO:
     * Debug para mostrar los datos.
     */
} else if (isset($_GET['detalleHema'])) {
    $id = $_SESSION['idUser'];
    $idcita = $_GET['idcita'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT * FROM detalle_hematologia WHERE idcita = $idcita");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['hemoglobina'] = $row['hemoglobina'];
        $data['hematocritos'] = $row['hematocritos'];
        $data['cuentas_blancas'] = $row['cuentas_blancas'];
        $data['plaquetas'] = $row['plaquetas'];
        $data['vsg'] = $row['vsg'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['detalleLeuco'])) {
    $id = $_SESSION['idUser'];
    $idcita = $_GET['idcita'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT * FROM detalle_leuco WHERE idcita = $idcita");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['seg'] = $row['seg'];
        $data['linf'] = $row['linf'];
        $data['eosin'] = $row['eosin'];
        $data['monoc'] = $row['monoc'];
        $data['basof'] = $row['basof'];
        $data['otros'] = $row['otros'];
        $data['total'] = $row['total'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['detalleQuimica'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, " SELECT d.id AS id_detalle, d.valor_unidad, d.valor_referencial1, d.valor_referencial2, q.examen 
                                        FROM detalle_quimica d 
                                        INNER JOIN quimica q ON d.id_examen = q.id 
                                        WHERE d.id_usuario = '$id'");
                                        
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id_detalle'];
        $data['examen'] = $row['examen'];
        $data['valor_unidad'] = $row['valor_unidad'];
        $data['valor_referencial1'] = $row['valor_referencial1'];
        $data['valor_referencial2'] = $row['valor_referencial2'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['detalleOrina'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT * FROM detalle_orina WHERE id_usuario = '$id'");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['aspecto'] = $row['aspecto'];
        $data['densidad'] = $row['densidad'];
        $data['ph'] = $row['ph'];
        $data['olor'] = $row['olor'];
        $data['color'] = $row['color'];
        $data['nitritos'] = $row['nitritos'];
        $data['proteinas'] = $row['proteinas'];
        $data['glucosa'] = $row['glucosa'];
        $data['cetonas'] = $row['cetonas'];
        $data['urobilinogeno'] = $row['urobilinogeno'];
        $data['bilirrubina'] = $row['bilirrubina'];
        $data['hemoglobina'] = $row['hemoglobina'];
        $data['pigmen_biliares'] = $row['pigmen_biliares'];
        $data['sales_biliares'] = $row['sales_biliares'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['detalleExamisc'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT * FROM orina_microscopio WHERE id_usuario = '$id'");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['celulas_ep_planas'] = $row['celulas_ep_planas'];
        $data['bacterias'] = $row['bacterias'];
        $data['leucocitos'] = $row['leucocitos'];
        $data['hematies'] = $row['hematies'];
        $data['mucina'] = $row['mucina'];
        $data['celulas_renales'] = $row['celulas_renales'];
        $data['cristales'] = $row['cristales'];
        $data['otros'] = $row['otros'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['detalleHeces'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT * FROM detalle_heces WHERE id_usuario = '$id'");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['aspecto'] = $row['aspecto'];
        $data['color'] = $row['color'];
        $data['olor'] = $row['olor'];
        $data['consistencia'] = $row['consistencia'];
        $data['reaccion'] = $row['reaccion'];
        $data['moco'] = $row['moco'];
        $data['sangre'] = $row['sangre'];
        $data['ra'] = $row['ra'];
        $data['ph'] = $row['ph'];
        $data['azucares'] = $row['azucares'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['detalleHecesmisc'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT * FROM detalle_hecesmisc WHERE id_usuario = '$id'");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['protozoarios'] = $row['protozoarios'];
        $data['helmintos'] = $row['helmintos'];
        $data['otros'] = $row['otros'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['detalleReticulocitos'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT * FROM detalle_reticulocitos WHERE id_usuario = '$id'");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['reticulocitos'] = $row['reticulocitos'];
        $data['valor_unidad'] = $row['valor_unidad'];
        $data['valor_referencial'] = $row['valor_referencial'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['detalleTiempos'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT * FROM detalle_tiempos WHERE id_usuario = '$id'");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['tp'] = $row['tp'];
        $data['tpt'] = $row['tpt'];
        $data['inr'] = $row['inr'];
        $data['fibrinogeno'] = $row['fibrinogeno'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['detallePruebaespecial'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, " SELECT d.id AS id_detalle, d.valor_unidad, d.valor_referencial, d.valor_referencial2, e.examen 
                                        FROM detalle_pruebaespecial d 
                                        INNER JOIN examenpruebaespecial e ON d.id_examen = e.id 
                                        WHERE d.id_usuario = '$id'");
                                        
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id_detalle'];
        $data['examen'] = $row['examen'];
        $data['valor_unidad'] = $row['valor_unidad'];
        $data['valor_referencial'] = $row['valor_referencial'];
        $data['valor_referencial2'] = $row['valor_referencial2'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['detalleInmunoserologia'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, " SELECT d.id AS id_detalle, d.valor_unidad, d.valor_referencial, e.examen 
                                        FROM detalle_inmunoserologia d 
                                        INNER JOIN exameninmunoserologia e ON d.id_examen = e.id 
                                        WHERE d.id_usuario = '$id'");
                                        
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id_detalle'];
        $data['examen'] = $row['examen'];
        $data['valor_unidad'] = $row['valor_unidad'];
        $data['valor_referencial'] = $row['valor_referencial'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['detalleGruposanguineo'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT * FROM detalle_gruposanguineo WHERE id_usuario = '$id'");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['gruposanguineo'] = $row['gruposanguineo'];
        $data['factor'] = $row['factor'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
    

} else if (isset($_GET['delete_detalle'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM detalle_temp WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['delete_hema'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM detalle_hematologia WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['delete_leu'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM detalle_leuco WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['delete_quimi'])) {
    $id_detalle = intval($_GET['id']);
    if ($id_detalle > 0) {
        $query = mysqli_query($conexion, "DELETE FROM detalle_quimica WHERE id = $id_detalle");
        if ($query) {
            if (mysqli_affected_rows($conexion) > 0) {
                $msg = "ok";
            } else {
                $msg = "Error: No rows affected";
            }
        } else {
            $msg = "Error: " . mysqli_error($conexion);
        }
    } else {
        $msg = "Invalid ID";
    }
    echo $msg;
    die();
} else if (isset($_GET['delete_orina'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM detalle_orina WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['delete_examisc'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM orina_microscopio WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['delete_heces'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM detalle_heces WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['delete_hecesmisc'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM detalle_hecesmisc WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['delete_reticulocitos'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM detalle_reticulocitos WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['delete_tiempos'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM detalle_tiempos WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['delete_prueba'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM detalle_pruebaespecial WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['delete_inmunoserologia'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM detalle_inmunoserologia WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['delete_gruposanguineo'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM detalle_gruposanguineo WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
    
    
    

} else if (isset($_GET['procesarVenta'])) {
    $id_cliente = $_GET['id'];
    $id_user = $_SESSION['idUser'];
    $response = array(); // Array para almacenar la respuesta

    $consulta = mysqli_query($conexion, "SELECT total, SUM(total) AS total_pagar FROM detalle_temp WHERE id_usuario = $id_user");
    $result = mysqli_fetch_assoc($consulta);
    $total = $result['total_pagar'];
    $id_maximo = mysqli_query($conexion, "SELECT MAX(id) AS id_venta FROM examenes");
    $resultId = mysqli_fetch_assoc($id_maximo);
    $ultimoId = $resultId['id_venta'];
    $insertar = mysqli_query($conexion, "INSERT INTO examenes(id_cliente, id_usuario, id_venta) VALUES ($id_cliente, $id_user, $ultimoId)");
    if ($insertar) {
        $id_maximo = mysqli_query($conexion, "SELECT MAX(id) AS id_venta FROM examenes");
        $resultId = mysqli_fetch_assoc($id_maximo);
        $ultimoId = $resultId['id_venta'];
    $consultaDetalle_hema = mysqli_query($conexion, "SELECT * FROM detalle_hematologia WHERE id_usuario = $id_user");
    if (!$consultaDetalle_hema){
        echo mysqli_error($conexion);
        return; 
    }
    while ($row = mysqli_fetch_assoc($consultaDetalle_hema)) {
        $id_usuario = $row['id_usuario'];
        $hemoglobina = $row['hemoglobina'];
        $hematocritos = $row['hematocritos'];
        $cuentas_blancas = floatval($row['cuentas_blancas']);
        $plaquetas = floatval($row['plaquetas']);
        $vsg = $row['vsg'];
        $insertarHema = mysqli_query($conexion, "INSERT INTO hematologia(id_usuario, id_cliente, id_venta, hemoglobina, hematocritos, cuentas_blancas, plaquetas, vsg ) VALUES ('$id_usuario', '$id_cliente', $ultimoId, '$hemoglobina', '$hematocritos', '$cuentas_blancas', '$plaquetas', '$vsg')");
        if(!$insertarHema){
            echo mysqli_error($conexion);
            return;  
        }
        if ($insertarHema){
            $eliminar = mysqli_query($conexion, "DELETE FROM detalle_hematologia WHERE id_usuario = $id_user");
        }
        else{
            echo mysqli_error($conexion);
            return;
        }
        
        
    }
    $consultaDetalle_leuco = mysqli_query($conexion, "SELECT * FROM detalle_leuco WHERE id_usuario = $id_user");
    if (!$consultaDetalle_leuco){
        echo mysqli_error($conexion);
        return; 
    }
    while ($row = mysqli_fetch_assoc($consultaDetalle_leuco)) {
        $id_usuario = $row['id_usuario'];
        $seg = $row['seg'];
        $linf = $row['linf'];
        $eosin = floatval($row['eosin']);
        $monoc = floatval($row['monoc']);
        $basof = $row['basof'];
        $otros = $row['otros'];
        $total = $row['total'];
        $insertarLeuco = mysqli_query($conexion, "INSERT INTO formula_leuco(id_usuario, id_cliente, id_venta, seg, linf, eosin, monoc, basof, otros, total ) VALUES ('$id_usuario', '$id_cliente', $ultimoId, '$seg', '$linf', '$eosin', '$monoc', '$basof', '$otros', '$total')");
        if(!$insertarLeuco){
            echo mysqli_error($conexion);
            return;  
        }
        if ($insertarLeuco){
            $eliminar = mysqli_query($conexion, "DELETE FROM detalle_leuco WHERE id_usuario = $id_user");
        }
        else{
            echo mysqli_error($conexion);
            return;
        }
        
        
    }
    $consultaDetalle_quimica = mysqli_query($conexion, "SELECT * FROM detalle_quimica WHERE id_usuario = $id_user");
    if (!$consultaDetalle_quimica){
        echo mysqli_error($conexion);
        return; 
    }
    while ($row = mysqli_fetch_assoc($consultaDetalle_quimica)) {
        $id_usuario = $row['id_usuario'];
        $id_examen = $row['id_examen'];
        $valor_unidad = $row['valor_unidad'];
        $devalor_referencial1 = $row['valor_referencial1'];
        $valor_referencial2 = $row['valor_referencial2'];
        $insertarQuimica = mysqli_query($conexion, "INSERT INTO resul_quimica(id_usuario, id_cliente, id_venta, id_examen, valor_unidad, valor_referencial1, valor_referencial2 ) VALUES ('$id_usuario', '$id_cliente', $ultimoId, $id_examen, '$valor_unidad', '$devalor_referencial1', '$valor_referencial2')");
        if(!$insertarQuimica){
            echo mysqli_error($conexion);
            return;
        }
        if ($insertarQuimica){
            $eliminar = mysqli_query($conexion, "DELETE FROM detalle_quimica WHERE id_usuario = $id_user");
        }
        else{
            echo mysqli_error($conexion);
            return;
        }
    }
    $consultaDetalle_orina = mysqli_query($conexion, "SELECT * FROM detalle_orina WHERE id_usuario = $id_user");
    if (!$consultaDetalle_orina){
        echo mysqli_error($conexion);
        return; 
    }
    while ($row = mysqli_fetch_assoc($consultaDetalle_orina)) {
        $id_usuario = $row['id_usuario'];
        $aspecto = $row['aspecto'];
        $densidad = $row['densidad'];
        $ph = floatval($row['ph']);
        $olor = floatval($row['olor']);
        $color = $row['color'];
        $nitritos = $row['nitritos'];
        $proteinas = $row['proteinas'];
        $glucosa = $row['glucosa'];
        $cetonas = $row['cetonas'];
        $urobilinogeno = $row['urobilinogeno'];
        $bilirrubina = $row['bilirrubina'];
        $hemoglobina = $row['hemoglobina'];
        $pigmen_biliares = $row['pigmen_biliares'];
        $sales_biliares = $row['sales_biliares'];
        $insertarOrina = mysqli_query($conexion, "INSERT INTO orina(id_usuario, id_cliente, id_venta, aspecto, densidad, ph, olor, color, nitritos, proteinas, glucosa, cetonas, urobilinogeno, bilirrubina, hemoglobina, pigmen_biliares, sales_biliares ) VALUES ('$id_usuario', '$id_cliente',  $ultimoId, '$aspecto', '$densidad', '$ph', '$olor', '$color', '$nitritos', '$proteinas', '$glucosa', '$cetonas', '$urobilinogeno','$bilirrubina' ,'$hemoglobina', '$pigmen_biliares', '$sales_biliares')");
        if(!$insertarOrina){
            echo mysqli_error($conexion);
            return;  
        }
        if ($insertarOrina){
            $eliminar = mysqli_query($conexion, "DELETE FROM detalle_orina WHERE id_usuario = $id_user");
        }
        else{
            echo mysqli_error($conexion);
            return;
        }
    }
    $consultaDetalle_misc = mysqli_query($conexion, "SELECT * FROM  orina_microscopio WHERE id_usuario = $id_user");
    if (!$consultaDetalle_misc){
        echo mysqli_error($conexion);
        return; 
    }
    while ($row = mysqli_fetch_assoc($consultaDetalle_misc)) {
        $id_usuario = $row['id_usuario'];
        $celulas_ep_planas= $row['celulas_ep_planas'];
        $bacterias = $row['bacterias'];
        $leucocitos = $row['leucocitos'];
        $hematies = $row['hematies'];
        $mucina = $row['mucina'];
        $celulas_renales = $row['celulas_renales'];
        $cristales = $row['cristales'];
        $otros = $row['otros'];
        $insertarMisc = mysqli_query($conexion, "INSERT INTO detalle_exm_misc_ori(id_usuario, id_cliente, id_venta, celulas_ep_planas, bacterias, leucocitos, hematies, mucina, celulas_renales, cristales, otros) VALUES ('$id_usuario', '$id_cliente',$ultimoId, '$celulas_ep_planas', '$bacterias', '$leucocitos', '$hematies', '$mucina', '$celulas_renales', '$cristales', '$otros')");
        if(!$insertarMisc){
            echo mysqli_error($conexion);
            return;  
        }
        if ($insertarMisc){
            $eliminar = mysqli_query($conexion, "DELETE FROM orina_microscopio WHERE id_usuario = $id_user");
        }
        else{
            echo mysqli_error($conexion);
            return;
        }
        
        
    }
    $consultaDetalle_heces = mysqli_query($conexion, "SELECT * FROM detalle_heces WHERE id_usuario = $id_user");
    if (!$consultaDetalle_heces){
        echo mysqli_error($conexion);
        return; 
    }
    while ($row = mysqli_fetch_assoc($consultaDetalle_heces)) {
        $id_usuario = $row['id_usuario'];
        $aspecto = $row['aspecto'];
        $color = $row['color'];
        $olor = $row['olor'];
        $consistencia = $row['consistencia'];
        $reaccion = $row['reaccion'];
        $moco = $row['moco'];
        $sangre = $row['sangre'];
        $ra = $row['ra'];
        $ph = floatval($row['ph']);
        $azucares = $row['azucares'];
        $insertarHeces = mysqli_query($conexion, "INSERT INTO heces(id_usuario, id_cliente, id_venta, aspecto, color, olor, consistencia, reaccion, moco, sangre, ra, ph, azucares) VALUES ('$id_usuario', '$id_cliente',  $ultimoId, '$aspecto', '$color', '$olor', '$consistencia', '$reaccion', '$moco', '$sangre', '$ra', '$ph', '$azucares')");
        if(!$insertarHeces){
            echo mysqli_error($conexion);
            return;  
        }
        if ($insertarHeces){
            $eliminar = mysqli_query($conexion, "DELETE FROM detalle_heces WHERE id_usuario = $id_user");
        }
        else{
            echo mysqli_error($conexion);
            return;
        }
    }
    $consultaDetalle_hecesmisc = mysqli_query($conexion, "SELECT * FROM detalle_hecesmisc WHERE id_usuario = $id_user");
    if (!$consultaDetalle_hecesmisc){
        echo mysqli_error($conexion);
        return; 
    }
    while ($row = mysqli_fetch_assoc($consultaDetalle_hecesmisc)) {
        $id_usuario = $row['id_usuario'];
        $protozoarios = $row['protozoarios'];
        $helmintos = $row['helmintos'];
        $otros = $row['otros'];
        $insertarHecesmisc = mysqli_query($conexion, "INSERT INTO heces_misc(id_usuario, id_cliente, id_venta, protozoarios, helmintos, otros) VALUES ('$id_usuario', '$id_cliente',  $ultimoId, '$protozoarios', '$helmintos', '$otros')");
        if(!$insertarHecesmisc){
            echo mysqli_error($conexion);
            return;  
        }
        if ($insertarHecesmisc){
            $eliminar = mysqli_query($conexion, "DELETE FROM detalle_hecesmisc WHERE id_usuario = $id_user");
        }
        else{
            echo mysqli_error($conexion);
            return;
        }
    }
    $consultaDetalle_reticulocitos = mysqli_query($conexion, "SELECT * FROM detalle_reticulocitos WHERE id_usuario = $id_user");
    if (!$consultaDetalle_reticulocitos){
        echo mysqli_error($conexion);
        return; 
    }
    while ($row = mysqli_fetch_assoc($consultaDetalle_reticulocitos)) {
        $id_usuario = $row['id_usuario'];
        $reticulocitos = $row['reticulocitos'];
        $valor_unidad = $row['valor_unidad'];
        $valor_referencial = $row['valor_referencial'];
        $insertarReticulocitos = mysqli_query($conexion, "INSERT INTO reticulocitos(id_usuario, id_cliente, id_venta, reticulocitos, valor_unidad, valor_referencial) VALUES ('$id_usuario', '$id_cliente',  $ultimoId, '$reticulocitos', '$valor_unidad', '$valor_referencial')");
        if(!$insertarReticulocitos){
            echo mysqli_error($conexion);
            return;  
        }
        if ($insertarReticulocitos){
            $eliminar = mysqli_query($conexion, "DELETE FROM detalle_reticulocitos WHERE id_usuario = $id_user");
        }
        else{
            echo mysqli_error($conexion);
            return;
        }
    }
    $consultaDetalle_tiempos = mysqli_query($conexion, "SELECT * FROM detalle_tiempos WHERE id_usuario = $id_user");
    if (!$consultaDetalle_tiempos){
        echo mysqli_error($conexion);
        return; 
    }
    while ($row = mysqli_fetch_assoc($consultaDetalle_tiempos)) {
        $id_usuario = $row['id_usuario'];
        $tp = $row['tp'];
        $tpt = $row['tpt'];
        $inr = $row['inr'];
        $fibrinogeno = $row['fibrinogeno'];
        $insertarTiempos = mysqli_query($conexion, "INSERT INTO tiempos(id_usuario, id_cliente, id_venta, tp, tpt, inr,fibrinogeno) VALUES ('$id_usuario', '$id_cliente',  $ultimoId, '$tp', '$tpt', '$inr', '$fibrinogeno')");
    if(!$insertarTiempos){
            echo mysqli_error($conexion);
            return;
        }
        if ($insertarTiempos){
            $eliminar = mysqli_query($conexion, "DELETE FROM detalle_tiempos WHERE id_usuario = $id_user");
        }
        else{
            echo mysqli_error($conexion);
            return;
        }
        
    }
    $consultaDetalle_prueba = mysqli_query($conexion, "SELECT * FROM detalle_pruebaespecial WHERE id_usuario = $id_user");
    if (!$consultaDetalle_prueba){
        echo mysqli_error($conexion);
        return; 
    }
    while ($row = mysqli_fetch_assoc($consultaDetalle_prueba)) {
        $id_usuario = $row['id_usuario'];
        $id_examen = $row['id_examen'];
        $valor_unidad = $row['valor_unidad'];
        $devalor_referencial = $row['valor_referencial'];
        $valor_referencial2 = $row['valor_referencial2'];
        $insertarPrueba = mysqli_query($conexion, "INSERT INTO pruebaespecial(id_usuario, id_cliente, id_venta, id_examen, valor_unidad, valor_referencial, valor_referencial2 ) VALUES ('$id_usuario', '$id_cliente', $ultimoId, $id_examen, '$valor_unidad', '$devalor_referencial', '$valor_referencial2')");
        if(!$insertarPrueba){
            echo mysqli_error($conexion);
            return;
        }
        if ($insertarPrueba){
            $eliminar = mysqli_query($conexion, "DELETE FROM detalle_pruebaespecial WHERE id_usuario = $id_user");
        }
        else{
            echo mysqli_error($conexion);
            return;
        }
    }
    $consultaDetalle_inmunoserologia = mysqli_query($conexion, "SELECT * FROM detalle_inmunoserologia WHERE id_usuario = $id_user");
    if (!$consultaDetalle_inmunoserologia){
        echo mysqli_error($conexion);
        return; 
    }
    while ($row = mysqli_fetch_assoc($consultaDetalle_inmunoserologia)) {
        $id_usuario = $row['id_usuario'];
        $id_examen = $row['id_examen'];
        $valor_unidad = $row['valor_unidad'];
        $devalor_referencial = $row['valor_referencial'];
        $insertarInmunoserologia = mysqli_query($conexion, "INSERT INTO inmunoserologia(id_usuario, id_cliente, id_venta, id_examen, valor_unidad, valor_referencial ) VALUES ('$id_usuario', '$id_cliente', $ultimoId, $id_examen, '$valor_unidad', '$devalor_referencial')");
        if(!$insertarInmunoserologia){
            echo mysqli_error($conexion);
            return;
        }
        if ($insertarInmunoserologia){
            $eliminar = mysqli_query($conexion, "DELETE FROM detalle_inmunoserologia WHERE id_usuario = $id_user");
        }
        else{
            echo mysqli_error($conexion);
            return;
        }
    }
    $consultaDetalle_gruposanguineo = mysqli_query($conexion, "SELECT * FROM detalle_gruposanguineo WHERE id_usuario = $id_user");
    if (!$consultaDetalle_gruposanguineo){
        echo mysqli_error($conexion);
        return; 
    }
    while ($row = mysqli_fetch_assoc($consultaDetalle_gruposanguineo)) {
        $id_usuario = $row['id_usuario'];
        $gruposanguineo = $row['gruposanguineo'];
        $factor = $row['factor'];
        $insertarGruposanguineo = mysqli_query($conexion, "INSERT INTO gruposanguineo(id_usuario, id_cliente, id_venta, gruposanguineo, factor) VALUES ('$id_usuario', '$id_cliente',  $ultimoId, '$gruposanguineo', '$factor')");
        if(!$insertarGruposanguineo){
            echo mysqli_error($conexion);
            return;  
        }
        if ($insertarGruposanguineo){
            $eliminar = mysqli_query($conexion, "DELETE FROM detalle_gruposanguineo WHERE id_usuario = $id_user");
        }
        else{
            echo mysqli_error($conexion);
            return;
        }
    }
    $msg = array('id_cliente' => $id_cliente, 'id_venta' => $ultimoId);
} else {

    $msg = array('mensaje' => 'error');
}
    echo json_encode($msg);
    die();
}else if (isset($_GET['descuento'])) {
    $id = $_GET['id'];
    $desc = $_GET['desc'];
    $consulta = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE id = $id");
    $result = mysqli_fetch_assoc($consulta);
    $total_desc = $desc + $result['descuento'];
    $total = $result['total'] - $desc;
    $insertar = mysqli_query($conexion, "UPDATE detalle_temp SET descuento = $total_desc, total = '$total'  WHERE id = $id");
    if ($insertar) {
        $msg = array('mensaje' => 'descontado');
    }else{
        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
} else if (isset($_GET['examenes'])) {
    $id = $_GET['id'];
    $hemato = $_GET['hemato'];
    $consulta = mysqli_query($conexion, "SELECT * FROM detalle_hematologia WHERE id = $id");
    $result = mysqli_fetch_assoc($consulta);
    $insertar = mysqli_query($conexion, "UPDATE detalle_hematologia SET examenes = '$hemato'   WHERE id = $id");
    if ($insertar) {
        $msg = array('mensaje' => 'pagado');
    } else {
        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
}else if(isset($_GET['editarCliente'])){
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
}
if (isset($_POST['regDetalle'])) {
    $id = $_POST['id'];
    $cant = $_POST['cant'];
    $precio = $_POST['precio'];
    $id_user = $_SESSION['idUser'];
    $total = $precio * $cant;
    $verificar = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE id_producto = $id AND id_usuario = $id_user");
    $result = mysqli_num_rows($verificar);
    $datos = mysqli_fetch_assoc($verificar);
    if ($result > 0) {
        $cantidad = $datos['cantidad'] + $cant;
        $total_precio = ($cantidad * $total);
        $query = mysqli_query($conexion, "UPDATE detalle_temp SET cantidad = $cantidad, total = '$total_precio' WHERE id_producto = $id AND id_usuario = $id_user");
        if ($query) {
            $msg = "actualizado";
        } else {
            $msg = "Error al ingresar";
        }
    }else{
        $query = mysqli_query($conexion, "INSERT INTO detalle_temp(id_usuario, id_producto, cantidad ,precio_venta, total) VALUES ($id_user, $id, $cant,'$precio', '$total')");
        if ($query) {
            $msg = "registrado";
        }else{
            $msg = "Error al ingresar";
        }
    }
    echo json_encode($msg);
    die();
} else if(isset($_POST['crearHema'])){
    $hemoglobina = $_POST['hemoglobina'];
    $hematocritos = $_POST['hematocritos'];
    $cuentas_blancas = $_POST['cuentas_blancas'];
    $plaquetas = $_POST['plaquetas'];
    $vsg = $_POST['vsg'];
    $id_user = $_SESSION['idUser'];
    $idcita = $_POST['idcita'];
    $query = mysqli_query($conexion, "INSERT INTO detalle_hematologia(id_usuario,  hemoglobina, hematocritos, cuentas_blancas, plaquetas, vsg, idcita) VALUES ($id_user, $hemoglobina, $hematocritos,$cuentas_blancas, $plaquetas, $vsg, $idcita)");
    if ($query) {
        $msg = "registrado";
    } else {
        echo mysqli_error($conexion);
        $msg = "Error al ingresar";
    }
    echo json_encode($msg);
    die();
} else if(isset($_POST['crearLeuco'])){
    $seg = $_POST['seg'];
    $idcita = $_POST['idcita'];
    $linf = $_POST['linf'];
    $eosin = $_POST['eosin'];
    $monoc = $_POST['monoc'];
    $basof = $_POST['basof'];
    $otros = $_POST['otros'];
    $total = $_POST['total'];
    $id_user = $_SESSION['idUser'];
    $query = mysqli_query($conexion, "INSERT INTO detalle_leuco(id_usuario, seg, linf, eosin, monoc, basof, otros, total, idcita) VALUES ($id_user, '$seg', '$linf','$eosin', '$monoc', '$basof', '$otros', '$total', '$idcita')"); 
    if ($query) {
        $msg = "registrado";
    } else {
        echo mysqli_error($conexion);
        $msg = "Error al ingresar";
    }
    echo json_encode($msg);
    die();
} else if(isset($_POST['crearQuimi'])){
    $id = $_POST['id'];
    $examen = $_POST['examen'];
    $valor_unidad = $_POST['valor_unidad'];
    $valor_referencial1 = $_POST['valor_referencial1'];
    $valor_referencial2 = $_POST['valor_referencial2'];
    $id_user = $_SESSION['idUser']; 
    
    $query_examen = mysqli_query($conexion, "SELECT id FROM quimica WHERE examen = '$examen' ");
    
    if (!$query_examen) {
        echo json_encode("Error en la consulta del examen: " . mysqli_error($conexion));
        die();
    }
    
    $row_examen = mysqli_fetch_assoc($query_examen);
    
    if (!$row_examen) {
        echo json_encode("Examen no encontrado");
        die();
    }
    
    $id_examen = $row_examen['id'];

    $verificar = mysqli_query($conexion, "SELECT id FROM detalle_quimica WHERE id_examen = '$id_examen' AND id_usuario = '$id_user'");
    $result = mysqli_num_rows($verificar);

    if ($result > 0) {
        // Update existing record
        $query = mysqli_query($conexion, "UPDATE detalle_quimica SET valor_unidad = '$valor_unidad' WHERE id_examen = '$id' AND id_usuario = '$id_user'");
        if ($query) {
            $msg = "actualizado";
        } else {
            $msg = "Error al actualizar: " . mysqli_error($conexion);
        }
    } else {
        // Insert new record
        $query = mysqli_query($conexion, "INSERT INTO detalle_quimica (id_usuario, id_examen, valor_unidad, valor_referencial1, valor_referencial2) VALUES ('$id_user', '$id_examen', '$valor_unidad', '$valor_referencial1', '$valor_referencial2')"); 
        if ($query) {
            $msg = "registrado";
        } else {
            $msg = "Error al insertar: " . mysqli_error($conexion);
        }
    }
    echo json_encode($msg);
    die();
} else if(isset($_POST['crearOrina'])){
        $aspecto = $_POST['aspecto'];
        $densidad = $_POST['densidad'];
        $ph = $_POST['ph'];
        $olor = $_POST['olor'];
        $color = $_POST['color'];
        $nitritos = $_POST['nitritos'];
        $proteinas = $_POST['proteinas'];
        $glucosa = $_POST['glucosa'];
        $cetonas = $_POST['cetonas']; 
        $urobilinogeno = $_POST['urobilinogeno'];
        $bilirrubina = $_POST['bilirrubina'];
        $hemoglobina = $_POST['hemoglobina'];
        $pigmen_biliares = $_POST['pigmen_biliares'];
        $sales_biliares = $_POST['sales_biliares'];
        $id_user = $_SESSION['idUser'];
    $query = mysqli_query($conexion, "INSERT INTO detalle_orina(id_usuario, aspecto, densidad, ph, olor,color, nitritos, proteinas, glucosa, cetonas, urobilinogeno, bilirrubina, hemoglobina, pigmen_biliares, sales_biliares) VALUES ($id_user,  '$aspecto', '$densidad','$ph','$olor','$color', '$nitritos','$proteinas', '$glucosa', '$cetonas', '$urobilinogeno', '$bilirrubina', '$hemoglobina', '$pigmen_biliares', '$sales_biliares')"); 
        if ($query) {
            $msg = "registrado";
        } else {
            echo mysqli_error($conexion);
            $msg = "Error al ingresar";
        }
        echo json_encode($msg);
        die();
    } else if(isset($_POST['crearExamisc'])){
        $celulas_ep_planas = $_POST['celulas_ep_planas'];
        $bacterias = $_POST['bacterias'];
        $leucocitos = $_POST['leucocitos'];
        $hematies = $_POST['hematies'];
        $mucina = $_POST['mucina'];
        $celulas_renales = $_POST['celulas_renales'];
        $cristales = $_POST['cristales'];
        $otros = $_POST['otros'];
        $id_user = $_SESSION['idUser'];
        $query = mysqli_query($conexion, "INSERT INTO orina_microscopio(id_usuario, celulas_ep_planas, bacterias, leucocitos, hematies, mucina, celulas_renales, cristales, otros) VALUES ($id_user,  '$celulas_ep_planas', '$bacterias','$leucocitos', '$hematies', '$mucina', '$celulas_renales', '$cristales', '$otros')");
        if ($query) {
            $msg = "registrado";
        } else {
            echo mysqli_error($conexion);
            $msg = "Error al ingresar";
        }
        echo json_encode($msg);
        die();
    } else if(isset($_POST['crearHeces'])){
        $aspecto = $_POST['aspecto'];
        $color = $_POST['color'];
        $olor = $_POST['olor'];
        $consistencia = $_POST['consistencia'];
        $reaccion = $_POST['reaccion'];
        $moco = $_POST['moco'];
        $sangre = $_POST['sangre'];
        $ra = $_POST['ra'];
        $ph = $_POST['ph'];
        $azucares = $_POST['azucares'];
        $id_user = $_SESSION['idUser'];
        $query = mysqli_query($conexion, "INSERT INTO detalle_heces(id_usuario, aspecto, color, olor, consistencia, reaccion, moco, sangre, ra, ph, azucares) VALUES ($id_user,  '$aspecto', '$color','$olor', '$consistencia', '$reaccion', '$moco', '$sangre', '$ra', '$ph', '$azucares')");
         
        if ($query) {
            $msg = "registrado";
        } else {
            echo mysqli_error($conexion);
            $msg = "Error al ingresar";
        }
        echo json_encode($msg);
        die();
    } else if(isset($_POST['crearHecesmisc'])){
        $protozoarios = $_POST['protozoarios'];
        $helmintos = $_POST['helmintos'];
        $otros = $_POST['otros'];
        $id_user = $_SESSION['idUser'];
        $query = mysqli_query($conexion, "INSERT INTO detalle_hecesmisc(id_usuario, protozoarios, helmintos, otros) VALUES ($id_user,  '$protozoarios', '$helmintos', '$otros')");
         
        if ($query) {
            $msg = "registrado";
        } else {
            echo mysqli_error($conexion);
            $msg = "Error al ingresar";
        }
        echo json_encode($msg);
        die();
    } else if(isset($_POST['crearReticulocitos'])){
        $reticulocitos = $_POST['reticulocitos'];
        $valor_unidad = $_POST['valor_unidad'];
        $valor_referencial = $_POST['valor_referencial'];
        $id_user = $_SESSION['idUser'];
        $query = mysqli_query($conexion, "INSERT INTO detalle_reticulocitos(id_usuario, reticulocitos, valor_unidad, valor_referencial) VALUES ($id_user,  '$reticulocitos', '$valor_unidad', '$valor_referencial')");
         
        if ($query) {
            $msg = "registrado";
        } else {
            echo mysqli_error($conexion);
            $msg = "Error al ingresar";
        }
        echo json_encode($msg);
        die();
    } else if(isset($_POST['crearTiempos'])){
        $tp = $_POST['tp'];
        $tpt = $_POST['tpt'];
        $inr = $_POST['inr'];
        $fibrinogeno = $_POST['fibrinogeno'];
        $id_user = $_SESSION['idUser'];
        $query = mysqli_query($conexion, "INSERT INTO detalle_tiempos(id_usuario, tp, tpt, inr, fibrinogeno) VALUES ($id_user,  '$tp', '$tpt', '$inr', '$fibrinogeno')");
         
        if ($query) {
            $msg = "registrado";
        } else {
            echo mysqli_error($conexion);
            $msg = "Error al ingresar";
        }
        echo json_encode($msg);
        die();
    } else if(isset($_POST['crearPrueba'])){
        $id = $_POST['id'];
        $examen = $_POST['especial'];
        $valor_unidad = $_POST['valor_unidad'];
        $valor_referencial = $_POST['valor_referencial'];
        $valor_referencial2 = $_POST['valor_referencialprueba'];
        $id_user = $_SESSION['idUser']; 
        
        $query_examen = mysqli_query($conexion, "SELECT id FROM examenpruebaespecial WHERE examen = '$examen' ");
        
        if (!$query_examen) {
            echo json_encode("Error en la consulta del examen: " . mysqli_error($conexion));
            die();
        }
        
        $row_examen = mysqli_fetch_assoc($query_examen);
        
        if (!$row_examen) {
            echo json_encode("Examen no encontrado");
            die();
        }
        
        $id_examen = $row_examen['id'];
    
        $verificar = mysqli_query($conexion, "SELECT id FROM detalle_pruebaespecial WHERE id_examen = '$id_examen' AND id_usuario = '$id_user'");
        $result = mysqli_num_rows($verificar);
    
        if ($result > 0) {
            // Update existing record
            $query = mysqli_query($conexion, "UPDATE detalle_pruebaespecial SET valor_unidad = '$valor_unidad' WHERE id_examen = '$id' AND id_usuario = '$id_user'");
            if ($query) {
                $msg = "actualizado";
            } else {
                $msg = "Error al actualizar: " . mysqli_error($conexion);
            }
        } else {
            // Insert new record
            $query = mysqli_query($conexion, "INSERT INTO detalle_pruebaespecial (id_usuario, id_examen, valor_unidad, valor_referencial, valor_referencial2) VALUES ('$id_user', '$id_examen', '$valor_unidad', '$valor_referencial', '$valor_referencial2')"); 
            if ($query) {
                $msg = "registrado";
            } else {
                $msg = "Error al insertar: " . mysqli_error($conexion);
            }
        }
        echo json_encode($msg);
        die();
    } else if(isset($_POST['crearInmunoserologia'])){
        $id = $_POST['id'];
        $examen = $_POST['inmunoserologia'];
        $valor_unidad = $_POST['valor_unidad'];
        $valor_referencial = $_POST['valor_referencialinmu'];
        $id_user = $_SESSION['idUser']; 
        
        $query_examen = mysqli_query($conexion, "SELECT id FROM exameninmunoserologia WHERE examen = '$examen' ");
        
        if (!$query_examen) {
            echo json_encode("Error en la consulta del examen: " . mysqli_error($conexion));
            die();
        }
        
        $row_examen = mysqli_fetch_assoc($query_examen);
        
        if (!$row_examen) {
            echo json_encode("Examen no encontrado");
            die();
        }
        
        $id_examen = $row_examen['id'];
    
        $verificar = mysqli_query($conexion, "SELECT id FROM detalle_inmunoserologia WHERE id_examen = '$id_examen' AND id_usuario = '$id_user'");
        $result = mysqli_num_rows($verificar);
    
        if ($result > 0) {
            // Update existing record
            $query = mysqli_query($conexion, "UPDATE detalle_inmunoserologia SET valor_unidad = '$valor_unidad' WHERE id_examen = '$id' AND id_usuario = '$id_user'");
            if ($query) {
                $msg = "actualizado";
            } else {
                $msg = "Error al actualizar: " . mysqli_error($conexion);
            }
        } else {
            // Insert new record
            $query = mysqli_query($conexion, "INSERT INTO detalle_inmunoserologia (id_usuario, id_examen, valor_unidad, valor_referencial) VALUES ('$id_user', '$id_examen', '$valor_unidad', '$valor_referencial')"); 
            if ($query) {
                $msg = "registrado";
            } else {
                $msg = "Error al insertar: " . mysqli_error($conexion);
            }
        }
        echo json_encode($msg);
        die();
    } else if(isset($_POST['crearGruposanguineo'])){
        $grupo = $_POST['grupo'];
        $factor = $_POST['factor'];
        $id_user = $_SESSION['idUser'];
        $query = mysqli_query($conexion, "INSERT INTO detalle_gruposanguineo(id_usuario, gruposanguineo, factor) VALUES ($id_user,  '$grupo', '$factor')");
         
        if ($query) {
            $msg = "registrado";
        } else {
            echo mysqli_error($conexion);
            $msg = "Error al ingresar";
        }
        echo json_encode($msg);
        die();
}else if (isset($_POST['cambio'])) {
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
            }else{
                $msg = 'error';
            }
        } else {
            $msg = 'dif';
        }
        
    }
    echo $msg;
    die();
    
}

