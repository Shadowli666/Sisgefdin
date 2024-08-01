
<!--ventana para Update--->
<div class="modal fade" id="pdnj<?php echo $data['idninos']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #33FFC4 !important;">
        <h6 class="modal-title" style="color: #000; text-align: center;">
            Actualizar Información
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


      <form method="POST" action="recib_Update_ninos.php">
        <input type="hidden" name="id" value="<?php echo $data['idninos']; ?>">

            <div class="modal-body" id="cont_modal">

                <div class="form-group">
                  <label for="recipient-name" class="col-form-label">Pediatra</label>
                  <input list="ped" name="pediatra" class="form-control" value="<?php echo $data['pediatra']; ?>" required="true">
                  <datalist id="ped">
                                                 <?php
                                               $query_doctor = mysqli_query($conexion, "SELECT * FROM medicos WHERE especialidad = 'Pediatria' ");
                                            while ($datos = mysqli_fetch_assoc($query_doctor)) { ?>
                                                <option value="<?php echo $datos['nombre'] ?>"></option>
                                            <?php } ?>
                                         </datalist>
                </div>
                <div class="form-group">
                  <label for="recipient-name" class="col-form-label">Diagnostico Medico</label>
                  <input type="text" name="diagnostico_medico" class="form-control" value="<?php echo $data['diagnostico_medico']; ?>" required="true">
                </div>
                <div class="form-group">
                  <label for="recipient-name" class="col-form-label">Peso</label>
                  <input type="text" name="peso" class="form-control" value="<?php echo $data['peso']; ?>" >
                </div>
                <div class="form-group">
                  <label for="recipient-name" class="col-form-label">Talla</label>
                  <input type="text" name="talla" class="form-control" value="<?php echo $data['talla']; ?>">
                </div>
                <div class="form-group">
                  <label for="recipient-name" class="col-form-label">Talla</label>
                  <input type="text" name="temperatura" class="form-control" value="<?php echo $data['temperatura']; ?>" >
                </div>
               
                
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
       </form>

    </div>
  </div>
</div>
<!---fin ventana Update --->
