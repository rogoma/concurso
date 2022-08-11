<div class="card-body">
    <div class="form-group row">
        <label for="rol" class="col-sm-2 text-right control-label col-form-label requerido">Tipo de Documento</label>
        <div class="col-sm-4">
            <select name="tipo_doc_id" class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                @if (@$data->academico)
                <option value="{{ $data->academico->tipo_doc_id }}">{{ $data->academico->tipoDoc->documento }}</option>
                <option>Escoja otro Tipo de Documento</option>
                @else
                <option>Escoja un Tipo de Documento</option>
                @endif
                @foreach($data->tipos_doc as $d)
                <option value="{{ $d->id }}">{{ $d->documento }}</option>
                @endforeach
            </select>
        </div>
        <label for="titulo" class="col-sm-2 text-right control-label col-form-label requerido">Título</label>
        <div class="col-sm-4 input-group">
            <input type="text" name="titulo" class="form-control" placeholder="Título" value="{{old("titulo", $data->academico->titulo ?? "")}}" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="fecha" class="col-sm-2 text-right control-label col-form-label requerido">Fecha Graduación</label>
        <div class="col-sm-4 input-group">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
            </div>
            @if (@$data->academico->fecha_graduac)
                <input type="text" class="form-control" id="datepicker-autoclose" placeholder="dd-mm-yyyy" value="{{old("fecha_graduac", date('d-m-Y', strtotime($data->academico->fecha_graduac)) ?? "")}}" required>
            @else
                <input type="text" class="form-control" id="datepicker-autoclose" placeholder="dd-mm-yyyy" required>
            @endif
            <input type="hidden" name="fecha_graduac" id="fecha_graduac" value="{{old("fecha_graduac", $data->academico->fecha_graduac ?? "")}}">
        </div>
        <label for="profesion" class="col-sm-2 text-right control-label col-form-label">Profesión</label>
        <div class="col-sm-4 input-group">
            <input type="text" name="profesion" id="profesion" class="form-control" placeholder="Profesión" value="{{old("profesion", $data->academico->profesion ?? "")}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="institucion" class="col-sm-2 text-right control-label col-form-label requerido">Institución</label>
        <div class="col-sm-10 input-group">
            <input type="text" name="institucion" id="institucion" class="form-control" placeholder="Institución" value="{{old("institucion", $data->academico->institucion ?? "")}}" required>
        </div>

    </div>
    <div class="">
        <label for="pdf" class="col-sm-2 text-right text-a control-label col-form-label requerido">Documento</label>
        <div class="custom-file col-sm-8">
            @if (@$data->academico->pdf)
                <a href="{{asset($data->academico->pdf)}}" target="_blank">
                <i class="fas fa-file-pdf text-primary fa-2x"></i></a>
                <input type="file" name="pdf" id="pdf">
                <label>Escoja el archivo...</label>
                <div><i><small>Archivo PDF hasta 5Mb</small></i></div>
            @else
                <input type="file" name="pdf" id="pdf" required>
                <label>Escoja el archivo...</label>
                <div><i><small>Archivo PDF hasta 5Mb</small></i></div>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label for="dato_adic" class="col-sm-2 text-right control-label col-form-label">Datos Adicionales</label>
        <div class="col-sm-10">
            <textarea name="dato_adic" id="dato_adic" class="form-control">{{old("dato_adic", $data->academico->dato_adic ?? "")}}</textarea>
        </div>
    </div>
</div>
