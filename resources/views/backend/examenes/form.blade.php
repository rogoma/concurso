<div class="card-body">
    <div class="form-group row">
        <label for="concurso_id" class="col-sm-2 text-right control-label col-form-label requerido">Concursos</label>
        <select name="cargo_id" class="col-sm-10 select2 form-control custom-select" style="height:36px;" required>
            @if(@$datos->examen->concurso_id)
            <option value="{{ $datos->examen->concurso_id }}">{{ $datos->concurso->cargos->descripcion }}</option>
            @endif
            <option value="">Escoja un Concurso...</option>
            @foreach($datos->concursos as $c)
                <option value="{{ $c->id }}">{{ $c->id }}: {{ $c->decripcion }} : {{ $c->cargos->descripcion }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group row">
        <label class="col-sm-1 text-left"><b>Examen</b></label>
        <label for="ini_examen" class="col-sm-1 text-right requerido">Inicio</label>
        <div class="col-sm-3">
            <div class="input-group">
                <div class="input-group-append">
                    <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
                </div>
                <input type="text" class="form-control" name="hora_ini" placeholder="Hora de Inicio " value="{{old("hora_ini",  $datos->examen->hora_ini ?? "" )}}" required>
            </div>
        </div>
        <label for="fin_examen" class="col-sm-2 text-right requerido">Finalización</label>
        <div class="col-sm-3">
            <div class="input-group">
                <div class="input-group-append">
                    <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
                </div>
                <input type="text" class="form-control" name="hora_fin" placeholder="Hora de Finalización" value="{{old("hora_fin",  $datos->examen->hora_fin ?? "")}}" required>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="puntaje" class="col-sm-2 text-right control-label col-form-label requerido">Puntaje</label>
        <div class="col-sm-4">
            <input type="text" name="puntaje" id="puntaje" class="form-control" value="{{old("puntaje", $datos->examen->puntaje ?? "")}}" placeholder="Puntaje" required>
        </div>
        <label for="cant_preg_examen" class="col-sm-2 text-right control-label col-form-label requerido">Cantidad de Vacantes</label>
        <div class="col-sm-4">
            <input type="text" name="cant_preg_examen" id="cant_preg_examen" class="form-control" maxlength="15" value="{{old("cant_preg_examen", $datos->examen->cant_preg_examen ?? "")}}" placeholder="Vacantes" required>
        </div>
    </div>
</div>
