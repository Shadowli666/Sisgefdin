<!--ventana para Update--->
<div class="modal fade" id="atencion<?php echo $data['idninos']; ?>" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel" aria-hidden="true">
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


      <form method="POST" action="recib_Update_Aten_ninos.php">
        <input type="hidden" name="id" value="<?php echo $data['idninos']; ?>">

        <div class="modal-body" id="cont_modal">

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Atencion:</label>
            <select id="atencion"type="text" name="atencion" class="form-control" value="<?php echo $data['atencion']; ?>"
              required="true">
              <option value="Atendido">Atendido</option>
              <option value="En Espera">En Espera</option>
              <option value="No se Presento">No se Presento</option>
            </select>
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