<div class="card">
    <div class="card-body">
        <div class="form-group row">
            <label for="puesto" class="col-sm-2 text-right control-label col-form-label requerido">Puesto</label>
            <div class="col-sm-4">
                <input type="text" name="puesto" class="form-control" placeholder="Puesto" value="{{old("puesto", $data->laboral->puesto ?? "")}}" required>
            </div>
            <label for="institucion" class="col-sm-2 text-right control-label col-form-label requerido">Institución/Empresa</label>
            <div class="col-sm-4 input-group">
                <input type="text" name="institucion" class="form-control" placeholder="Institución" value="{{old("institucion", $data->laboral->institucion ?? "")}}" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="fecha_ini" class="col-sm-2 text-right requerido">Fecha Inicio</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <div class="input-group-append">
                        <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
                    </div>
                    @if (@$data->laboral->fecha_ini)
                        <input type="text" class="form-control" id="datepicker-autoclose_ini" placeholder="dd-mm-yyyy" value="{{ date('d-m-Y', strtotime($data->laboral->fecha_ini)) }}" required>
                    @else
                        <input type="text" class="form-control" id="datepicker-autoclose_ini" placeholder="dd-mm-yyyy" required>
                    @endif
                    <input type="hidden" name="fecha_ini" id="fecha_ini" value="{{old("fecha_ini", $data->laboral->fecha_ini ?? "")}}">
                </div>
            </div>
            <label for="fecha_fin" class="col-sm-2 text-right">Fecha Fin</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <div class="input-group-append">
                        <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
                    </div>
                    @if (@$data->laboral->fecha_fin)
                        <input type="text" class="form-control" id="datepicker-autoclose_fin" placeholder="dd-mm-yyyy" value="{{ date('d-m-Y', strtotime($data->laboral->fecha_fin)) }}" required>
                    @else
                        <input type="text" class="form-control" id="datepicker-autoclose_fin" placeholder="dd-mm-yyyy" required>
                    @endif
                    <input type="hidden" name="fecha_fin" id="fecha_fin" value="{{old("fecha_fin", $data->laboral->fecha_fin ?? "")}}">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="ref_laboral" class="col-sm-3 text-right control-label col-form-label requerido">Referencia Laboral</label>
            <div class="col-sm-9 input-group">
                <input type="text" name="ref_laboral" id="ref_laboral" class="form-control" placeholder="Referencia Laboral" value="{{old("ref_laboral", $data->laboral->ref_laboral ?? "")}}" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="tel_ref_lab" class="col-sm-3 text-right control-label col-form-label requerido">Teléfono Ref. Laboral</label>
            <div class="col-sm-3 input-group">
                <input type="text" name="tel_ref_lab" id="tel_ref_lab" class="form-control" placeholder="Teléfono Ref. Laboral" value="{{old("tel_ref_lab", $data->laboral->tel_ref_lab ?? "")}}" required>
            </div>
            <label for="tel_ref_lab2" class="col-sm-3 text-right control-label col-form-label">Otro Teléfono Ref. Laboral</label>
            <div class="col-sm-3 input-group">
                <input type="text" name="tel_ref_lab2" class="form-control" placeholder="Otro Teléfono Ref. Laboral" value="{{old("tel_ref_lab2", $data->laboral->tel_ref_lab2 ?? "")}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="constancia" class="col-sm-2 text-right control-label col-form-label requerido">Constancia</label>
            <div class="custom-file col-sm-4">
                @if (@$data->laboral->constancia)
                    <a href="{{asset($data->laboral->constancia)}}" target="_blank">
                    <i class="fas fa-file-pdf text-primary fa-2x"></i></a>
                    <input type="file" name="constancia" id="constancia">
                    <label>Escoja el archivo...</label>
                    <div><i><small>Archivo PDF hasta 5Mb</small></i></div>
                @else
                    <input type="file" name="constancia" id="constancia" required>
                    <label>Escoja el archivo...</label>
                    <div><i><small>Archivo PDF hasta 5Mb</small></i></div>
                @endif
            </div>
            <label for="tipo_doc_id" class="col-sm-2 text-right control-label col-form-label requerido">Tipo de Documento</label>
            <div class="col-sm-4">
                <select name="tipo_doc_id" class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                    @if (@$data->laboral)
                    <option value="{{ $data->laboral->tipo_doc_id }}">{{ $data->laboral->tipoDoc->documento }}</option>
                    <option>Escoja otro Tipo de Documento</option>
                    @else
                    <option>Escoja un Tipo de Documento</option>
                    @endif
                    @foreach($data->tipos_doc as $d)
                    <option value="{{ $d->id }}">{{ $d->documento }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
