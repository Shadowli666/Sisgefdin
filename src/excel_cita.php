<?php

header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= Historia de PACIENTES.xls")

?>

 <table class="table table-light" id="tbla">
                <thead class="thead-dark">
                   <tr>
                            <th>cedula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Teléfono</th>
                            <th>Doctor</th>
                            <th>Espe.</th>
                            <th>Servicio</th>
                            <th>Precios</th>
                            <th>Fecha de Cit</th>
                            <th>Atencion</th>
                            <th>Observacion</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('configs.php');
              $sqlTrabajadores = ('SELECT * FROM itinerario ORDER BY fecha ASC');
              $query = mysqli_query($con, $sqlTrabajadores );
                            
                                 while ($row = mysqli_fetch_array($query)) { ?>
                                    <tr>
                                        
                                        <td><?php echo $row['cedula']; ?></td>
                                        <td><?php echo $row['nombre']; ?></td>
                                        <td><?php echo $row['apellido']; ?></td>
                                        <td><?php echo $row['telefono']; ?></td>
                                        <td><?php echo $row['doctor']; ?></td>
                                        <td><?php echo $row['especialidad']; ?></td>
                                        <td><?php echo $row['descripcion']; ?></td>
                                        <td><?php echo $row['precios']; ?></td>
                                        <td><?php echo $row['fecha']; ?></td>
                                        <td><?php echo $row['atencion']; ?></td>
                                        <td><?php echo $row['observacion']; ?></td>
                            <td>
                                <a href="pdf/generar.php?cl=<?php echo $row['id_cliente'] ?>&v=<?php echo $row['id'] ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>

                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>