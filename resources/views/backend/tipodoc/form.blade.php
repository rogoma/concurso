<div class="card-body">
    <div class="form-group row">
        <label for="tipo" class="col-sm-1 text-right control-label col-form-label requerido">Tipo</label>
        <div class="col-md-2">
            <select name="tipo">
                @if (@$data->tipo)
                @php
                    switch ($data->tipo) {
                        case 'A':
                            $name = "Académico";
                            break;
                        case 'C':
                            $name = "Capacitación";
                            break;
                        case 'L':
                            $name = "Laboral";
                            break;
                        case 'P':
                            $name = "Personal";
                            break;
                        default:
                            $name = "";
                            break;
                    }
                @endphp
                <option value="{{ old('tipo', $data->tipo ?? "") }}">{{ $name }}</option>
                <option value="">Escoja otro tipo...</option>
                @endif
                <option value="A">Académico</option>
                <option value="C">Capacitación</option>
                <option value="L">Laboral</option>
                <option value="P">Personal</option>
            </select>
        </div>
        <label for="documento" class="col-sm-2 text-right control-label col-form-label requerido">Documento</label>
        <div class="col-sm-7">
            <input type="text" name="documento" id="documento" class="form-control" maxlength="50" value="{{old("documento", $data->documento ?? "")}}" placeholder="Documento" required>
        </div>
    </div>
</div>
