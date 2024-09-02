<div class="card">
    <div class="col-12 card-header bg-primary text-white text-center">
        PERFIL DE COAGULACION
    </div>
    <form onsubmit="crearTiempos(event)">
        <div class="card-body">
            <input type="hidden" name="idcita" id="idcita" value="<?php echo $idcita;?>">
            <div class="row">
                <input type="hidden" name="idcita" id="idcita" value="<?php echo $idcita;?>">
                <div class="col-md-4">
                    <div class="form-group"> <input id="id" type="hidden" name="id">
                        <label>TIEMPO DE PROTROMBINA:</label>
                        <input id="tp" class="form-control" type="text" name="tp">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>TIEMPO PARCIAL DE TROMBOPLASTINA:</label>
                        <input id="tpt" class="form-control" type="text" name="tpt">
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>INR</label>
                        <input id="inr" class="form-control" type="text" name="inr">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>FIBRINOGENO</label>
                        <input id="fibrinogeno" class="form-control" type="text" name="fibrinogeno">
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
                <th>TP</th>
                <th>TPT</th>
                <th>INR</th>
                <th>FIBRINOGENO</th>
                <th>ACCION</th>
            </tr>
        </thead>
        <tbody id="detalle_tiempos" style="background: rgba(255, 255, 255, 0.70)">
        </tbody>
    </table>
</div>
