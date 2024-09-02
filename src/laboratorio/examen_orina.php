<div class="card">
    <div class="card-header bg-primary text-white text-center">
        EXAMEN DE ORINA
    </div>
    <form onsubmit="crearOrina(event)">
        <div class="card-body">
            <div class="row">
                <input type="hidden" name="idcita" id="idcita" value="<?php echo $idcita;?>">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="aspecto">ASPECTO</label>
                        <select id="aspecto" class="form-control" name="aspecto" value="Lig.Turbio">
                            <option>Lig. Turbio</option>
                            <option>Turbio</option>
                            <option>Purulento</option>
                        </select>
                        <input id="id" type="hidden" name="id">
                        
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="densidad">DENSIDAD</label>
                        <select id="densidad" class="form-control" name="densidad" value="1.000">
                            <option>1.000</option>
                            <option>1.005</option>
                            <option>1.010</option>
                            <option>1.015</option>
                            <option>1.020</option>
                            <option>1.025</option>
                            <option>1.030</option>
                        </select>
                        <input id="id" type="hidden" name="id">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="ph">P.H</label>
                        <select id="ph" class="form-control" name="ph" value="6.0">
                            <option>5.0</option>
                            <option>6.0</option>
                            <option>6.5</option>
                            <option>7.0</option>
                            <option>7.5</option>
                            <option>8.0</option>
                            <option>8.5</option>
                            <option>9.0</option>
                        </select>
                        <input id="id" type="hidden" name="id">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="olor">OLOR</label>
                        <select id="olor" class="form-control" name="olor" value="S.G">
                            <option>S.G.</option>
                            <option>Amoniacal.</option>
                            
                        </select>
                        <input id="id" type="hidden" name="id">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="color">COLOR</label>
                        <select id="color" class="form-control" name="color" value="Amarillo">
                            <option>Amarillo.</option>
                            <option>Amarillo intenso.</option>
                            <option>Ambar.</option>
                            <option>Rojiza.</option>
                            <option>Medicamentoza.</option>
                            
                        </select>
                        <input id="id" type="hidden" name="id">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="nitritos">NITRITOS</label>
                        <select id="nitritos" class="form-control" name="nitritos" value="Neg.">
                            <option>Neg.</option>
                            <option>Trazas.</option>
                            <option>Positivo.</option>
                            
                        </select>
                        
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="proteinas">PROTEINAS</label>
                        <select id="proteinas" class="form-control" name="proteinas" value="Neg.">
                            <option>Neg.</option>
                            <option>Trazas.</option>
                            <option>Positivo +</option>
                            <option>Positivo ++</option>
                            <option>Positivo +++</option>
                            
                        </select>
                        
                        <input id="id" type="hidden" name="id">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="glucosa">GLUCOSA</label>
                        <select id="glucosa" class="form-control" name="glucosa" value="Neg.">
                            <option>Neg.</option>
                            <option>Trazas.</option>
                            <option>Positivo +</option>
                            <option>Positivo ++</option>
                            <option>Positivo +++</option>
                            
                        </select>
                        <input id="id" type="hidden" name="id">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="cetonas">CETONAS</label>
                        <select id="cetonas" class="form-control" name="cetonas" value="Neg.">
                            <option>Neg.</option>
                            <option>Trazas.</option>
                            <option>Positivo.</option>
                            
                        </select>
                        <input id="id" type="hidden" name="id">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="urobilinogeno">UROBILINOGENO</label>
                        <select id="urobilinogeno" class="form-control" name="urobilinogeno" value="Neg.">
                            <option>Neg.</option>
                            <option>Trazas.</option>
                            <option>Positivo +</option>
                            <option>Positivo ++</option>
                            <option>Positivo +++</option>
                            
                        </select>
                        <input id="id" type="hidden" name="id">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="bilirrubina">BILIRRUBINA</label>
                        <select id="bilirrubina" class="form-control" name="bilirrubina" value="Neg.">
                            <option>Neg.</option>
                            <option>Trazas.</option>
                            <option>Positivo +</option>
                            <option>Positivo ++</option>
                            <option>Positivo +++</option>
                            
                        </select>
                        <input id="id" type="hidden" name="id">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="hemoglobina">HEMOGLOBINA</label>
                        <select id="hemoglobina" class="form-control" name="hemoglobina" value="Neg.">
                            <option>Neg.</option>
                            <option>Trazas.</option>
                            <option>Positivo +</option>
                            <option>Positivo ++</option>
                            <option>Positivo +++</option>
                            
                        </select>
                        <input id="id" type="hidden" name="id">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="pigmen_biliares">PIGMENTOS BILIARES</label>
                        <select id="pigmen_biliares" class="form-control" name="pigmen_biliares" value="Neg.">
                            <option>Neg.</option>
                            <option>Trazas.</option>
                            <option>Positivo +</option>
                            <option>Positivo ++</option>
                            <option>Positivo +++</option>
                            
                        </select>
                        <input id="id" type="hidden" name="id">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="sales_biliares">SALES BILIARES</label>
                        <select id="sales_biliares" class="form-control" name="sales_biliares" value="Neg.">
                            <option>Neg.</option>
                            <option>Trazas.</option>
                            <option>Positivo +</option>
                            <option>Positivo ++</option>
                            <option>Positivo +++</option>
                            
                        </select>
                        <input id="id" type="hidden" name="id">
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
                <th>ASP.</th>
                <th>DEN.</th>
                <th>PH.</th>
                <th>OLOR.</th>
                <th>COL.</th>
                <th>NIT.</th>
                <th>PRO.</th>
                <th>GLU.</th>
                <th>CET.</th>
                <th>UROB.</th>
                <th>BILI.</th>
                <th>HEMO.</th>
                <th>PIG B.</th>
                <th>SAL B.</th>
                <th>ACCION</th>
            </tr>
        </thead>
        
        <tbody id="detalle_orina" style="background: rgba(255, 255, 255, 0.70)">
            
        </tbody>
    </table>
</div>