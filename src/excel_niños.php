<?php

header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= Programa Divino Niño Jesus.xls")

?>


   <table class="table table-striped table-bordered" id="tbl">
                    <thead class="thead-dark">
                    
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Cedula</th>
                            <th>Representante</th>
                            <th>Telefono</th>
                            <th>Direccion</th>
                            <th>Diagnostico</th>
                            <th>Fecha de Cit</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            include "../conexion.php";

                            $query = mysqli_query($conexion, "SELECT * FROM ninos");
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                                while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                                        <td><?php echo $data['idninos']; ?></td>
                                        <td><?php echo $data['nombre']; ?></td>
                                        <td><?php echo $data['apellido']; ?></td>
                                        <td><?php echo $data['cedula']; ?></td>
                                        <td><?php echo $data['representante']; ?></td>
                                        <td><?php echo $data['telefono']; ?></td>
                                        <td><?php echo $data['direccion']; ?></td>
                                        <td><?php echo $data['diagnostico_medico']; ?></td>
                                        <td><?php echo $data['fecha_cita']; ?></td>
                                        <td>
                                            <a href="#" onclick="editarNiños(<?php echo $data['idninos']; ?>)" class="btn btn-primary"><i class='fas fa-edit'></i></a>
                                            <form action="eliminar_nino.php?id=<?php echo $data['idninos']; ?>" method="post" class="confirmar d-inline">
                                                <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>

                    </table>