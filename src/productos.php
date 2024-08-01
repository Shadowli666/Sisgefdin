<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!--  Datatables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css"/>  

    <style>
    .table thead, .table tfoot{
        background-color: #455a64;
        color:azure;
    }
    </style>
</head>
<body>
    <?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "medicamentos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
if (!empty($_POST)) {
    $alert = "";
    $id = $_POST['id'];
    $codigo = $_POST['codigo'];
    $producto = $_POST['producto'];
    $ubi = $_POST['ubi'];
    $cantidad = $_POST['cantidad'];
    $tipo = $_POST['tipo'];
    $presentacion = $_POST['presentacion'];
    $laboratorio = $_POST['laboratorio'];
    $espe = $_POST['espe'];
    $vencimiento = '';
    if (!empty($_POST['accion'])) {
        $vencimiento = $_POST['vencimiento'];
    }
   if (empty($codigo) || empty($producto) || empty($tipo) || empty($presentacion) || empty($laboratorio)  || empty($espe) || empty($ubi) || empty($cantidad) || $cantidad <  0) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todos los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        if (empty($id)) {
            $query = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo = '$codigo'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El Medicamento ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
               $query_insert = mysqli_query($conexion, "INSERT INTO producto(codigo,descripcion,ubi,existencia,id_lab,id_presentacion,id_tipo,espe, vencimiento) values ('$codigo', '$producto', '$ubi', '$cantidad', '$laboratorio', '$presentacion', '$tipo', '$espe', '$vencimiento')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Medicamenrto registrado
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
            $query_update = mysqli_query($conexion, "UPDATE producto SET codigo = '$codigo', descripcion = '$producto', existencia = $cantidad, espe = '$espe', ubi = '$ubi', vencimiento = '$vencimiento' WHERE codproducto = $id");
             if ($query_update) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Medicamento Modificado
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
                        Medicamentos
                    </div>
                    <div class="card-body">
                        <form action="" method="post" autocomplete="off" id="formulario">
                            <?php echo isset($alert) ? $alert : ''; ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="codigo" class=" text-dark font-weight-bold"><i class="fas fa-barcode"></i>Código</label>
                                        <input type="text" placeholder="Ingrese código de barras" name="codigo" id="codigo" class="form-control" value="<?php
function generate_string() {
    $strength = 5;
    $input = '0123456789';
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
    return $random_string;
}
echo generate_string();
?>">
                                        <input type="hidden" id="id" name="id">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="producto" class=" text-dark font-weight-bold">Medicamentos</label>
                                        <input type="text" placeholder="Ingrese nombre del Medicamentos" name="producto" id="producto" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="ubi" class=" text-dark font-weight-bold" >Ubicacion</label>
                                        <input  list= "text"  placeholder="Ingrese Ubicacion" class="form-control"  name="ubi" id="ubi">
                                        <datalist id="text">
                                        <option value="C-1">
                                        <option value="C-2">
                                        <option value="C-3">
                                        <option value="C-4">
                                        <option value="C-5">
                                        <option value="C-6">
                                        <option value="C-7"> 
                                        <option value="C-8">
                                        <option value="C-9">
                                        <option value="C-10"> 
                                        <option value="C-11">
                                        <option value="C-12"> 
                                        <option value="C-13">
                                        <option value="C-14"> 
                                        <option value="C-15">
                                        <option value="C-16"> 
                                        <option value="C-17"> 
                                        <option value="C-18"> 
                                        <option value="C-19">
                                        <option value="C-20">
                                        </datalist>
                                    
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cantidad" class=" text-dark font-weight-bold">Cantidad</label>
                                        <input type="text" placeholder="Ingrese Cantidad" class="form-control" name="cantidad" id="cantidad">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                         <label for="tipo" class=" text-dark font-weight-bold">Principio Activo</label>
                                        <input list="browser" id="tipo" class="form-control" name="tipo" placeholder="Ingrese Principio Activo" required>
                                        <datalist id="browser">
                                            <?php
                                            $query_tipo = mysqli_query($conexion, "SELECT * FROM tipos ");
                                            while ($datos = mysqli_fetch_assoc($query_tipo)) { ?>
                                                <option value="<?php echo $datos['tipo'] ?>"></option>
                                            <?php } ?>
                                         </datalist>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="presentacion"  class=" text-dark font-weight-bold">Presentación</label>
                                        <input list="pres"  id="presentacion" placeholder="Ingrese Presentacion"class="form-control" name="presentacion" requeried>
                                        <datalist id="pres">
                                            <?php
                                            $query_pre = mysqli_query($conexion, "SELECT * FROM presentacion");
                                            while ($datos = mysqli_fetch_assoc($query_pre)) { ?>
                                                <option value="<?php echo $datos['nombre'] ?>"></option>
                                            <?php } ?>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="laboratorio"  class=" text-dark font-weight-bold">Utilidad</label>
                                       <input list="uti" id="laboratorio" placeholder="Ingrese Utilidad"class="form-control" name="laboratorio" required>
                                       <datalist id="uti">
                                            <?php
                                            $query_lab = mysqli_query($conexion, "SELECT * FROM laboratorios");
                                            while ($datos = mysqli_fetch_assoc($query_lab)) { ?>
                                                <option value="<?php echo $datos['laboratorio'] ?>"></option>
                                            <?php } ?>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="espe"  class=" text-dark font-weight-bold">Especialidad</label>
                                       <input list="es" id="espe" placeholder="Ingrese Especialidad"class="form-control" name="espe" required>
                                       <datalist id="es">
                                            <option value="Medicina Interna"></option>
                                            <option value="Cardiologia"></option>
                                            <option value="Cirugia Bucal"></option>
                                            <option value="Oftalmologia"></option>
                                            <option value="Pediatria"></option>
                                            <option value="Psiquiatria"></option>
                                            <option value="Oncologia"></option>
                                            <option value="Nemonologia"></option>
                                            <option value="Urologia"></option>
                                            <option value="Ginecologia"></option>
                                            <option value="Otorrinolaringologia"></option>
                                            <option value="Gastroenterologia"></option>
                                            <option value="Nefrologia"></option>
                                            <option value="Traumatologia"></option>
                                            <option value="Odontologia"></option>
                                            <option value="Neurologia"></option>
                                            <option value="Mastologia"></option>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="form-group">
                                        <input id="accion" class="form-check-input" type="checkbox" name="accion" value="si">
                                        <label for="vencimiento">Vencimiento</label>
                                        <input id="vencimiento" class="form-control" type="date" name="vencimiento">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                                    <input type="button" value="Nuevo" onclick="limpiar()" class="btn btn-success" id="btnNuevo">
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

   <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="example" rowStyleClass="stockbajo" >
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Medicamento</th>
                            <th>Principio Activo</th>
                            <th>Presentacion</th>
                            <th>Ubicacion</th>
                            <th>Stock</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                     <a href="./excel_productos.php" class="btn btn-success"><i class='fas fa-file-excel'></i> </a>
                             <a  href="./productos.php ?>" class="btn btn-info" onclick="window.print()"><i class='fas fa-print'></i> </a>
                    <tbody>
                        <?php
                            include "../conexion.php";
                            $query = mysqli_query($conexion, "SELECT * FROM producto");
                            $result = mysqli_num_rows($query);

                            if ($result > 0) {
                                while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                                        <td><?php echo $data['codproducto']; ?></td>
                                        <td><?php echo $data['codigo']; ?></td>
                                        <td><?php echo $data['descripcion']; ?></td>
                                        <td><?php echo $data['id_tipo']; ?></td>
                                        <td><?php echo $data['id_presentacion']; ?></td>
                                        <td><?php echo $data['ubi']; ?></td>
                                        <td><?php echo $data['existencia']; ?></td>
                                        <td>
                                            <a href="#" onclick="editarProducto(<?php echo $data['codproducto']; ?>)" class="btn btn-primary"><i class='fas fa-edit'></i></a>

                                        <form action="eliminar_producto.php?id=<?php echo $data['codproducto']; ?>" method="post" class="confirmar d-inline">
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



     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        
    <!--  Datatables JS-->
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>   
    <!-- SUM()  Datatables-->
    <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>

    <script>
    $(document).ready(function(){
        var tabla = $("#example").DataTable({
               "createdRow":function(row,data,index){                   
                   //pintar una celda
                   if(data[6] == 0){
                        $('td', row).eq(6).css({
                           'background-color':'#ff5252',
                           'color':'white', 
                       }); 
                   
                  
                      }
                }
        });

        //1era forma para sum()
        //var tot = tabla.column(5).data().sum();
        //$("#total").text(tot);
    });
    </script>
</body>
</html>

<?php include_once "includes/footer.php"; ?>