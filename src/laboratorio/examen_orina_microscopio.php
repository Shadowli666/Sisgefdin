<div class="card">
    <div class="card-header bg-primary text-white text-center">
        EXAMEN DE MICROSCOPIO
    </div>
    <form onsubmit="crearExamisc(event)">
        <div class="card-body">
            <div class="row">
                <input type="hidden" name="idcita" id="idcita" value="<?php echo $idcita;?>">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="celulas_ep_planas">CELULAS EP PLANAS</label>
                        <select id="celulas_ep_planas" class="form-control" name="celulas_ep_planas">
                            <option>ESCASAS</option>
                            <option>MODERADAS</option>
                            <option>ABUNDANTES</option>
                            
                        </select>
                        <input id="id" type="hidden" name="id">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="bacterias">BACTERIAS</label>
                        <select id="bacterias" class="form-control" name="bacterias">
                            <option>ESCASAS</option>
                            <option>MODERADAS</option>
                            <option>ABUNDANTES</option>
                            <input id="id" type="hidden" name="id">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="leucocitos">LEUCOCITOS</label>
                            <input id="leucocitos" class="form-control" type="text" name="leucocitos">
                            <input id="id" type="hidden" name="id">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="hematies">HEMATIES</label>
                            <input id="hematies" class="form-control" type="text" name="hematies">
                            <input id="id" type="hidden" name="id">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="mucina">MUCINA</label>
                            <input id="mucina" class="form-control" type="text" name="mucina">
                            <input id="id" type="hidden" name="id">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="celulas_renales">CELULAS RENALES</label>
                            <input id="celulas_renales" class="form-control" type="text" name="celulas_renales">
                            <input id="id" type="hidden" name="id">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="cristales">CRISTALES</label>
                            <input id="cristales" class="form-control" type="text" name="cristales">
                            <input id="id" type="hidden" name="id">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="otros">OTROS</label>
                            <input id="otros" class="form-control" type="text" name="otros">
                            <input id="id" type="hidden" name="id">
                        </div>
                    </div>
                    <button class="btn btn-primary">
                        Enviar
                    </button>
                </div>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-hover" id="tblDetalle">
            <thead class="thead-dark">
                <tr>
                    <th>Id</th>
                    <th>CELULAS EP PLANAS.</th>
                    <th>BACTERIAS.</th>
                    <th>LEUCOCITOS.</th>
                    <th>HEMATIES.</th>
                    <th>MUCINA.</th>
                    <th>CELULAS RENALES.</th>
                    <th>CRISTALES.</th>
                    <th>OTROS.</th>
                    <th>ACCION</th>
                </tr>
            </thead>
            
            <tbody id="detalle_orinamisc" style="background: rgba(255, 255, 255, 0.70)">
                
            </tbody>
        </table>
    </div>
</div>