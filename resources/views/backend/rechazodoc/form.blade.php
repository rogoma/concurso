<div class="card-body">
    <div class="form-group row">
        <label for="rol" class="col-sm-2 text-right control-label col-form-label requerido">Tipo de Documento</label>
        <div class="col-sm-4">
            <select name="tipo_doc_id" class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                @if (@$data->rechazo->tipo_doc_id)
                <option value="{{ $data->rechazo->tipo_doc_id }}">{{ $data->rechazo->tipoDoc->documento }}</option>
                <option>Escoja otro Tipo de Documento</option>
                @else
                <option>Escoja un Tipo de Documento</option>
                @endif
                @foreach($data->tipos_doc as $d)
                <option value="{{ $d->id }}">{{ $d->documento }}</option>
                @endforeach
            </select>
        </div>
        <label for="rechazo" class="col-sm-2 text-right control-label col-form-label requerido">Motivo del Rechazo</label>
        <div class="col-sm-4">
            <input type="text" name="rechazo" id="rechazo" class="form-control" maxlength="60" value="{{old("rechazo", $data->rechazo->rechazo ?? "")}}" placeholder="Motivo del Rechazo" required>
        </div>
    </div>
</div>
