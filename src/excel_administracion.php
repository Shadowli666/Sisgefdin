<?php

header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= Historia de PACIENTES.xls")

?>

 <table class="table table-light" id="tbla">
                <thead class="thead-dark">
                   <tr style="background: #D0CDCD;">
                            
                            <th class="color">Nombre</th>
                            <th class="color">Apellido</th>
                            
                            <th class="color">Doctor</th>
                           
                            <th class="color">Servicio</th>
                            <th class="color">Precios</th>
                            <th class="color">Fecha de Cit</th>
                           
                            <th class="color">Observacion</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('configs.php');
              $sqlTrabajadores = ('SELECT * FROM itinerario ORDER BY fecha ASC');
              $query = mysqli_query($con, $sqlTrabajadores );
                            
                                 while ($row = mysqli_fetch_array($query)) { ?>
                                    <tr>
                                        
                                        <td><?php echo $row['nombre']; ?></td>
                                        <td><?php echo $row['apellido']; ?></td>
                                        
                                        <td><?php echo $row['doctor']; ?></td>
                                        
                                        <td><?php echo $row['descripcion']; ?></td>
                                        <td><?php echo $row['precios']; ?></td>
                                        <td><?php echo $row['fecha']; ?></td>
                                 
                                        <td><?php echo $row['observacion']; ?></td>
                            <td>
                                <a href="pdf/generar.php?cl=<?php echo $row['id_cliente'] ?>&v=<?php echo $row['id'] ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>

                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            