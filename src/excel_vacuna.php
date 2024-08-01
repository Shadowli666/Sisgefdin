<?php

header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=  Proyecto de Vacunacion.xls")
?>
<table class="table table-striped table-bordered" id="tbl">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Edad</th>
                            <th>Genero</th>
                            <th>Representante</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Vacunado</th>
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
                                        <td><?php echo $data['nombre']; ?></td>
                                        <td><?php echo $data['edad']; ?></td>
                                        <td><?php echo $data['genero']; ?></td>
                                        <td><?php echo $data['representante']; ?></td>
                                        <td><?php echo $data['telefono']; ?></td>
                                        <td><?php echo $data['direccion']; ?></td>
                                        <td><?php echo $data['vencimiento']; ?></td>
                                        <td>
                                            <a href="#" onclick="editarVacunacion(<?php echo $data['idvacuna']; ?>)" class="btn btn-primary"><i class='fas fa-edit'></i></a>
                                            <form action="eliminar_vacuna.php?id=<?php echo $data['idvacuna']; ?>" method="post" class="confirmar d-inline">
                                                <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>

                    </table>