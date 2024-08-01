<?php

header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= Control de Censo.xls")

?>


                     <table class="table table-striped table-bordered" id="tbl">
                        <thead class="thead-dark">
                              
                            <tr>
                               <th>Cedula</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Edad</th>
                                <th>Genero</th>
                                <th>Telefono</th>
                                <th>Motivo de  Consulta</th>
                                <th>Medicacion</th>
                                <th>Habitos del Paciente</th>
                                <th>Antecedentes Personales</th>
                                <th>Antecedentes Familiares</th>
                                <th>Responsable del Censo</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "../conexion.php";

                            $query = mysqli_query($conexion, "SELECT * FROM jornada");
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                                while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                               
                                        <td><?php echo $data['cedula']; ?></td>
                                        <td><?php echo $data['nombre']; ?></td>
                                        <td><?php echo $data['apellido']; ?></td>
                                        <td><?php echo $data['edad']; ?></td>
                                        <td><?php echo $data['genero']; ?></td>
                                        <td><?php echo $data['telefono']; ?></td>
                                        <td><?php echo $data['Causa_consulta']; ?></td>
                                        <td><?php echo $data['medicacion']; ?></td>
                                        <td><?php echo $data['habitos']; ?></td>
                                        <td><?php echo $data['antecedente_per']; ?></td>
                                        <td><?php echo $data['antecedente_fa']; ?></td>
                                        <td><?php echo $data['observacion']; ?></td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>

                    </table>
                

