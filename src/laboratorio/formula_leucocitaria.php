<div class="card">
                <div class="card-header bg-primary text-white text-center">
                    FORMULA LEUCOCITARIA
                </div>
                <form onsubmit="crearLeuco(event)">
                    <div class="card-body">
                        <div class="row" style="text-align:center;">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="idcita" id="idcita" value="<?php echo $idcita;?>">
                                    <label for="seg">SEG</label>
                                    <input id="seg" class="form-control amt" type="text" name="seg">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="linf">LINF</label>
                                    <input id="linf" class="form-control amt" type="text" name="linf">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="eosin">EOSIN</label>
                                    <input id="eosin" class="form-control amt" type="text" name="eosin">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="monoc">MONOC</label>
                                    <input id="monoc" class="form-control amt" type="text" name="monoc">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="basof">BASOF</label>
                                    <input id="basof" class="form-control amt" type="text" name="basof">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="otros">OTROS</label>
                                    <input id="otros" class="form-control amt" type="text" name="otros">

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="total">TOTAL</label>
                                    <input id="total" action="post" class="form-control" type="text" name="total">

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
                            <th>SEG</th>
                            <th>LINF</th>
                            <th>EOSIN</th>
                            <th>MONOC</th>
                            <th>BASOF</th>
                            <th>OTROS</th>
                            <th>TOTAL</th>
                            <th>Accion</th>
                        </tr>
                    </thead>

                    <tbody id="detalle_leuco" style="background: rgba(255, 255, 255, 0.70)">

                    </tbody>
                </table>
