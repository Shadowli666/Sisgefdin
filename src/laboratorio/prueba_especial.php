<div class="card">
    <div class="col-12 card-header bg-primary text-white text-center">
        PRUEBA ESPECIAL
    </div>
    <form onsubmit="crearPrueba(event)">
        <div class="card-body">
            <div class="row">
                <input type="hidden" name="idcita" id="idcita" value="<?php echo $idcita;?>">
                <div class="col-md-3">
                    <div class="form-group"> <input id="id" type="hidden" name="id">
                        <label>EXAMEN</label>
                        <input id="especial" class="form-control" type="text" name="especial">
                        
                        
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>VALOR UNIDAD</label>
                        <input id="valor_unidad" class="form-control" type="text" name="valor_unidad">
                    </div>
                </div>
                
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>VALOR REFERNCIAL</label>
                        <input id="valor_referencial" class="form-control" type="text" name="valor_referencial">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>VALOR REFERENCIAL</label>
                        <input id="valor_referencialprueba" class="form-control" type="text" name="valor_referencialprueba">
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
                <th>VALOR REFERENCIAL</th>
                <th>ACCION</th>
            </tr>
        </thead>
        
        <tbody id="detalle_prueba" style="background: rgba(255, 255, 255, 0.70)">
            
        </tbody>
    </table>
</div>