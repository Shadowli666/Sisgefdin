<?php
require "../conexion.php";
$usuarios = mysqli_query($conexion, "SELECT * FROM usuario");
$total['usuarios'] = mysqli_num_rows($usuarios);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente");
$total['clientes'] = mysqli_num_rows($clientes);
$ninos = mysqli_query($conexion, "SELECT * FROM ninos");
$total['ninos'] = mysqli_num_rows($ninos);
$productos = mysqli_query($conexion, "SELECT * FROM producto");
$total['productos'] = mysqli_num_rows($productos);
$ventas = mysqli_query($conexion, "SELECT * FROM ventas");
$total['ventas'] = mysqli_num_rows($ventas);
$jornada = mysqli_query($conexion, "SELECT * FROM jornada");
$total['jornada'] = mysqli_num_rows($jornada);
session_start();
include_once "includes/header.php";
?>
<!-- Content Row -->
 
<script src="assets/js/sweetalert.js"></script>
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-user fa-2x"></i>
                </div>
                <a href="usuarios.php" class="card-category text-warning font-weight-bold">
                    Usuarios del Sistema
                </a>
                <h3 class="card-title"><?php echo $total['usuarios']; ?></h3>
            </div>
            <div class="card-footer bg-warning text-white">
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-success card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <a href="clientes.php" class="card-category text-success font-weight-bold">
                    Control de Pacientes
                </a>
                <h3 class="card-title"><?php echo $total['clientes']; ?></h3>
            </div>
            <div class="card-footer bg-primary text-white">
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-dove fa-2x"></i>
                </div>
                <a href="niños.php" class="card-category text-rose font-weight-bold">
                    Control de P.D.N.J
                </a>
                <h3 class="card-title"><?php echo $total['ninos']; ?></h3>
            </div>
            <div class="card-footer bg-success text-white">
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-danger card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-tablets fa-2x"></i>
                </div>
                <a href="productos.php" class="card-category text-danger font-weight-bold">
                    Medicamentos 
                </a>
                <h3 class="card-title"><?php echo $total['productos']; ?></h3>
            </div>
            <div class="card-footer bg-info">
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-hand-holding-heart fa-2x"></i>
                </div>
                <a href="ventas.php" class="card-category text-info font-weight-bold">
                    Control de Donaciones
                </a>
                <h3 class="card-title"><?php echo $total['ventas']; ?></h3>
            </div>
            <div class="card-footer bg-danger text-white">
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-purple card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-venus-mars  fa-2x"></i>
                </div>
                <a href="ventas.php" class="card-category text-warning font-weight-bold">
                    Control de Censo
                </a>
                <h3 class="card-title"><?php echo $total['jornada']; ?></h3>
            </div>
            <div class="card-footer bg-warning text-white">
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Productos Vencidos
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-danger table-bordered" id="tbla">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Principio Activo</th>
                            <th>Presentacion</th>
                            <th>Ubicacion</th>
                            <th>Stock</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "../conexion.php";
                        $hoy = date('Y-m-d');

                            $query = mysqli_query($conexion, "SELECT p.*, t.tipo, pr.nombre FROM producto p INNER JOIN tipos t ON p.id_tipo = t.tipo INNER JOIN presentacion pr ON p.id_presentacion = pr.nombre WHERE p.vencimiento != '0000-00-00' AND p.vencimiento < '$hoy'" );
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
 <div class="col-lg-6">
     <div class="card">
            <div class="card-header card-header-primary">
                <h3 class="title-2 m-b-40">Productos con stock mínimo</h3>
            </div>
            <div class="card-body">
                <canvas id="stockMinimo"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header card-header-primary">
                <h3 class="title-2 m-b-40">Productos más solicitados</h3>
            </div>
            <div class="card-body">
                <canvas id="ProductosVendidos"></canvas>
            </div>
        </div>
    </div>
</div>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../assets/js/sweetalert.js"></script>
<?php include_once "includes/footer.php"; ?>
 