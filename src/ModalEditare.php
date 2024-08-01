<!--ventana para Update--->
<div class="modal fade" id="editChildresne<?php echo $data['idcita']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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


      <form method="POST" action="recib_Updates.php">
        <input type="hidden" name="id" value="<?php echo $data['idcita']; ?>">

        <div class="modal-body" id="cont_modal">


          <label for="recipient-name" class="col-form-label">Especialista FDN:</label>
          <input list="doc" name="doctor" class="form-control" value="<?php echo $data['especialista']; ?>" required="false">
          <datalist id="doc">
            <?php
            $query_doctor = mysqli_query($conexion, "SELECT medicos.nombre, medicos.especialidad FROM medicos  ");
            while ($datos = mysqli_fetch_assoc($query_doctor)) { ?>
              <option value="<?php echo $datos['nombre'] ?> / <?php echo $datos['especialidad'] ?>"></option>
            <?php } ?>
          </datalist>



          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Servicio:</label>
            <input type="text" name="descripcion" class="form-control" value="<?php echo $data['descripcion']; ?>" required="true">
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Precio:</label>
            <input type="text" name="precios" class="form-control" value="<?php echo $data['precios']; ?>" required="true">
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Atencion:</label>
            <input type="text" name="atencion" class="form-control" value="<?php echo $data['atencion']; ?>" required="true">
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