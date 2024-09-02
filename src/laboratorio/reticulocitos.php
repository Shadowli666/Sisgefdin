<div class="card">
    <div class="col-12 card-header bg-primary text-white text-center">
        HEMATOLOGIA
    </div>
    <form onsubmit="crearReticulocitos(event)">
        <div class="card-body">
            <div class="row">
                <input type="hidden" name="idcita" id="idcita" value="<?php echo $idcita;?>">
                <div class="col-md-4">
                    <div class="form-group"> <input id="id" type="hidden" name="id">
                        <label>RETICULOCITOS</label>
                        <input id="reticulocitos" class="form-control" type="text" name="reticulocitos" value="RETICULOCITOS">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>VALOR UNIDAD</label>
                        <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>VALOR REFERNCIAL</label>
                        <input id="valor_referencial" class="form-control" type="text" name="valor_referencial" value="VALOR REFERENCIAL   ADULTOS  0.5 - 2.0 %">
                    </div>
                </div>
                <div class="col-lg-4">
                    <button class="btn btn-primary">
                        Enviar
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-hover" id="tblDetalle">
        <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>EXAMEN</th>
                <th>VALOR UNIDAD</th>
                <th>VALOR REFERENCIAL</th>
                <th>ACCION</th>
            </tr>
        </thead>
        <tbody id="detalle_reticulocitos" style="background: rgba(255, 255, 255, 0.70)">

        </tbody>
    </table>
</div>