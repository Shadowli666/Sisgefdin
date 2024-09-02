<!-- Formulario de Hematologia -->
<div class="card">
    <div class="card-header bg-primary text-white text-center">
        HEMATOLOGIA
    </div>
    <form onsubmit="crearHema(event)">
        <div class="card-body">
            <div class="row">
                <input type="hidden" name="idcita" id="idcita" value="<?php echo $idcita;?>">
                <div class="col-lg-4">
                    <div class="form-group">
                        <input type="hidden" id="idcliente" value="<?php echo $id_cliente; ?>" name="idcliente" required>
                        <label for="hemoglobina">HEMOGLOBINA</label>
                        <input id="hemoglobina" class="form-control" type="text" name="hemoglobina" placeholder="">
                        <input id="id" type="hidden" name="id">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="hematocritos">HEMATROCITOS</label>
                        <input id="hematocritos" class="form-control" type="text" name="hematocritos" placeholder="">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="cuentas_blancas">CUENTAS BLANCAS</label>
                        <input id="cuentas_blancas" class="form-control" type="text" name="cuentas_blancas" placeholder="">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="plaquetas">PLAQUETAS</label>
                        <input id="plaquetas" class="form-control" type="text" name="plaquetas" placeholder="">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="vsg">VSG</label>
                        <input id="vsg" class="form-control" type="text" name="vsg" placeholder="">
                    </div>
                </div>
                <div class="col-lg-4">
                    <button class="btn btn-primary">
                        Enviar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Resultados de Hematologia -->
<div class="table-responsive">
    <table class="table table-hover" id="tblDetalle">
        <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Hemoglobina</th>
                <th>Hematrocitos</th>
                <th>Cuentas Blancas</th>
                <th>Plaquetas</th>
                <th>VSG</th>
                <th>Accion</th>
            </tr>
        </thead>
        <tbody id="detalle_hematologia" style="background: rgba(255, 255, 255, 0.70)">
        </tbody>
    </table>
</div>