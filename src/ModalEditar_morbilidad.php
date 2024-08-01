
<!--ventana para Update--->
<div class="modal fade" id="morbilidad<?php echo $data['idcita']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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


      <form method="POST" action="recib_Update_morbilidad.php">
        <input type="hidden" name="id" value="<?php echo $data['idcita']; ?>">

            <div class="modal-body" id="cont_modal">

                <div class="form-group">
                  <label for="recipient-name" class="col-form-label">Motivo de Consulta</label>
                  <input type="text" name="motivo_consulta" class="form-control" value="<?php echo $data['motivo_consulta']; ?>" required="true">
                  
                </div>
                <div class="form-group">
                  <label for="recipient-name" class="col-form-label">Diagnostico Medico</label>
                  <input type="text" name="diagnostico" class="form-control" value="<?php echo $data['diagnostico']; ?>" required="true">
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
