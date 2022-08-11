<div class="card-body">
    <div class="form-group row">
        <label for="rol" class="col-sm-2 text-right control-label col-form-label requerido">Tema del Curso</label>
        <div class="col-sm-4">
            <input type="text" name="tema_curso" class="form-control" placeholder="Tema del Curso" value="{{old("tema_curso", $data->capacitacion->tema_curso ?? "")}}" required>
        </div>
        <label for="carga_horaria" class="col-sm-2 text-right control-label col-form-label requerido">Carga Horaria</label>
        <div class="col-sm-4 input-group">
            <input type="text" name="carga_horaria" class="form-control" placeholder="Carga Horaria" value="{{old("carga_horaria", $data->capacitacion->carga_horaria ?? "")}}" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="fecha" class="col-sm-2 text-right control-label col-form-label requerido">Fecha de Inicio</label>
        <div class="col-sm-4 input-group">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
            </div>
            @if (@$data->capacitacion->fecha_ini)
                <input type="text" class="form-control" id="datepicker-autoclose_ini" placeholder="dd-mm-yyyy" value="{{ date('d-m-Y', strtotime($data->capacitacion->fecha_ini)) }}" required>
            @else
                <input type="text" class="form-control" id="datepicker-autoclose_ini" placeholder="dd-mm-yyyy" required>
            @endif
            <input type="hidden" name="fecha_ini" id="fecha_ini" value="{{old("fecha_ini", $data->capacitacion->fecha_ini ?? "")}}">
        </div>
        <label for="fecha" class="col-sm-2 text-right control-label col-form-label requerido">Fecha de Finalización</label>
        <div class="col-sm-4 input-group">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
            </div>
            @if (@$data->capacitacion->fecha_fin)
                <input type="text" class="form-control" id="datepicker-autoclose_fin" placeholder="dd-mm-yyyy" value="{{ date('d-m-Y', strtotime($data->capacitacion->fecha_fin)) }}" required>
            @else
                <input type="text" class="form-control" id="datepicker-autoclose_fin" placeholder="dd-mm-yyyy" required>
            @endif
            <input type="hidden" name="fecha_fin" id="fecha_fin" value="{{old("fecha_fin", $data->capacitacion->fecha_fin ?? "")}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="institucion" class="col-sm-2 text-right control-label col-form-label requerido">Institución</label>
        <div class="col-sm-10 input-group">
            <input type="text" name="institucion" id="institucion" class="form-control" placeholder="Institución" value="{{old("institucion", $data->capacitacion->institucion ?? "")}}" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="constancia" class="col-sm-2 text-right control-label col-form-label requerido">Constancia</label>
        <div class="custom-file col-sm-4">
            @if (@$data->capacitacion->constancia)
                <a href="{{asset($data->capacitacion->constancia)}}" target="_blank">
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
        <label for="rol" class="col-sm-2 text-right control-label col-form-label requerido">Tipo de Documento</label>
        <div class="col-sm-4">
            <select name="tipo_doc_id" class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                @if (@$data->capacitacion)
                <option value="{{ $data->capacitacion->tipo_doc_id }}">{{ $data->capacitacion->tipoDoc->documento }}</option>
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
