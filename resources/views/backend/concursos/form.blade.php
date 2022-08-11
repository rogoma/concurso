<div class="card-body">
    <div class="form-group row">
        @php
            if(@$datos->concurso->tipo_concurso == 'A') {
                $a = 'checked';
                $b = '';
            } elseif(@$datos->concurso->tipo_concurso == 'B') {
                $a = '';
                $b = 'checked';
            } else {
                $a = '';
                $b = '';
            }
        @endphp
        <label for="tipo_concurso" class="col-sm-2 text-right control-label col-form-label requerido">Tipo de Concurso</label>
        <div class="col-sm-3 custom-control custom-radio">
            <input type="radio" class="custom-control-input" name="tipo_concurso" id="tipo_concurso_A" value="A" {{ @$a }} required>
            <label class="custom-control-label" for="tipo_concurso_A">Administrativo</label>
        </div>
        <div class="col-sm-3 custom-control custom-radio">
            <input type="radio" class="custom-control-input" name="tipo_concurso" id="tipo_concurso_B" value="B" {{ @$b }}>
            <label class="custom-control-label" for="tipo_concurso_B">Personal de Blanco</label>
        </div>
    </div>
    <div class="form-group row">
        <label for="descripcion" class="col-sm-2 text-right control-label col-form-label requerido">Descripción</label>
        <div class="col-sm-6">
            <input type="text" name="decripcion" id="decripcion" class="form-control" maxlength="50" value="{{old("decripcion", $datos->concurso->decripcion ?? "")}}" placeholder="Descripción" required>
        </div>
        <label for="cantidad_vacanc" class="col-sm-2 text-right control-label col-form-label requerido">Cantidad de Vacantes</label>
        <div class="col-sm-2">
            <input type="text" name="cantidad_vacanc" id="cantidad_vacanc" class="form-control" maxlength="15" value="{{old("cantidad_vacanc", $datos->concurso->cantidad_vacanc ?? "")}}" placeholder="Vacantes" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="cargo_id" class="col-sm-1 text-right control-label col-form-label requerido">Cargo</label>
        <select name="cargo_id" class="col-sm-6 select2 form-control custom-select" style="height:36px;" required>
            @if(@$datos->concurso->cargo_id)
            <option value="{{ $datos->concurso->cargo_id }}">{{ $datos->concurso->cargos->descripcion }}</option>
            @endif
            <option value="">Escoja un Cargo...</option>
            @foreach($datos->cargos as $d)
                <option value="{{ $d->id }}">{{ $d->descripcion }}</option>
            @endforeach
        </select>
        <label for="salario" class="col-sm-1 text-right control-label col-form-label requerido">Salario</label>
        <div class="col-sm-2">
            <input type="text" name="salario" id="salario" class="form-control" maxlength="15" value="{{old("salario", $datos->concurso->salario ?? "")}}" placeholder="Salario" required>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-left"><b>Postulacion</b></label>
        <label for="ini_post" class="col-sm-1 text-right requerido">Inicio</label>
        <div class="col-sm-3">
            <div class="input-group">
                @php
                    $ini_post = (@$datos->concurso->ini_post) ? date('d-m-Y', strtotime($datos->concurso->ini_post)) : '';
                    $fin_post = (@$datos->concurso->fin_post) ? date('d-m-Y', strtotime($datos->concurso->fin_post)) : '';
                @endphp
                <div class="input-group-append">
                    <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
                </div>
                <input type="text" class="form-control" id="datepicker-autoclose_ini_post" placeholder="dd-mm-yyyy" value="{{ $ini_post }}" required>
                <input type="hidden" name="ini_post" id="ini_post" value="{{ old("ini_post", $datos->concurso->ini_post ?? "")}}">
            </div>
        </div>
        <label for="fin_post" class="col-sm-2 text-right requerido">Finalización</label>
        <div class="col-sm-3">
            <div class="input-group">
                <div class="input-group-append">
                    <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
                </div>
                <input type="text" class="form-control" id="datepicker-autoclose_fin_post" placeholder="dd-mm-yyyy" value="{{ $fin_post }}" required>
                <input type="hidden" name="fin_post" id="fin_post" value="{{old("fin_post", $datos->concurso->fin_post ?? "")}}">
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 text-left"><b>Evaluación Documental</b></label>
        <label for="ini_eva_doc" class="col-sm-1 text-right requerido">Inicio</label>
        <div class="col-sm-3">
            <div class="input-group">
                @php
                    $ini_eva_doc = (@$datos->concurso->ini_eva_doc) ? date('d-m-Y', strtotime($datos->concurso->ini_eva_doc)) : '';
                    $fin_eva_doc = (@$datos->concurso->fin_eva_doc) ? date('d-m-Y', strtotime($datos->concurso->fin_eva_doc)) : '';
                @endphp
                <div class="input-group-append">
                    <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
                </div>
                <input type="text" class="form-control" id="datepicker-autoclose_ini_eva_doc" placeholder="dd-mm-yyyy" value="{{ $ini_eva_doc }}" required>
                <input type="hidden" name="ini_eva_doc" id="ini_eva_doc" value="{{old("ini_eva_doc", $datos->concurso->ini_eva_doc ?? "")}}">
            </div>
        </div>
        <label for="fin_eva_doc" class="col-sm-2 text-right requerido">Finalización</label>
        <div class="col-sm-3">
            <div class="input-group">
                <div class="input-group-append">
                    <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
                </div>
                <input type="text" class="form-control" id="datepicker-autoclose_fin_eva_doc" placeholder="dd-mm-yyyy" value="{{ $fin_eva_doc }}" required>
                <input type="hidden" name="fin_eva_doc" id="fin_eva_doc" value="{{old("fin_eva_doc", $datos->concurso->fin_eva_doc ?? "")}}">
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 text-left"><b>Evaluación Curricular</b></label>
        <label for="ini_eva_cur" class="col-sm-1 text-right requerido">Inicio</label>
        <div class="col-sm-3">
            <div class="input-group">
                @php
                    $ini_eva_cur = (@$datos->concurso->ini_eva_cur) ? date('d-m-Y', strtotime($datos->concurso->ini_eva_cur)) : '';
                    $fin_eva_cur = (@$datos->concurso->fin_eva_cur) ? date('d-m-Y', strtotime($datos->concurso->fin_eva_cur)) : '';
                @endphp
                <div class="input-group-append">
                    <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
                </div>
                <input type="text" class="form-control" id="datepicker-autoclose_ini_eva_cur" placeholder="dd-mm-yyyy" value="{{ $ini_eva_cur }}" required>
                <input type="hidden" name="ini_eva_cur" id="ini_eva_cur" value="{{old("ini_eva_cur", $datos->concurso->ini_eva_cur ?? "")}}">
            </div>
        </div>
        <label for="fin_eva_cur" class="col-sm-2 text-right requerido">Finalización</label>
        <div class="col-sm-3">
            <div class="input-group">
                <div class="input-group-append">
                    <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
                </div>
                <input type="text" class="form-control" id="datepicker-autoclose_fin_eva_cur" placeholder="dd-mm-yyyy" value="{{ $fin_eva_cur }}" required>
                <input type="hidden" name="fin_eva_cur" id="fin_eva_cur" value="{{old("fin_eva_cur", $datos->concurso->fin_eva_cur ?? "")}}">
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 text-left"><b>Examen</b></label>
        <label for="ini_examen" class="col-sm-1 text-right requerido">Inicio</label>
        <div class="col-sm-3">
            <div class="input-group">
                @php
                    $ini_examen = (@$datos->concurso->ini_examen) ? date('d-m-Y', strtotime($datos->concurso->ini_examen)) : '';
                    $fin_examen = (@$datos->concurso->fin_examen) ? date('d-m-Y', strtotime($datos->concurso->fin_examen)) : '';
                @endphp
                <div class="input-group-append">
                    <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
                </div>
                <input type="text" class="form-control" id="datepicker-autoclose_ini_examen" placeholder="dd-mm-yyyy" value="{{ $ini_examen }}" required>
                <input type="hidden" name="ini_examen" id="ini_examen" value="{{old("ini_examen", $datos->concurso->ini_examen ?? "")}}">
            </div>
        </div>
        <label for="fin_examen" class="col-sm-2 text-right requerido">Finalización</label>
        <div class="col-sm-3">
            <div class="input-group">
                <div class="input-group-append">
                    <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
                </div>
                <input type="text" class="form-control" id="datepicker-autoclose_fin_examen" placeholder="dd-mm-yyyy" value="{{ $fin_examen }}" required>
                <input type="hidden" name="fin_examen" id="fin_examen" value="{{old("fin_examen", $datos->concurso->fin_examen ?? "")}}">
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 text-left"><b>Entrevista</b></label>
        <label for="ini_entrevista" class="col-sm-1 text-right requerido">Inicio</label>
        <div class="col-sm-3">
            <div class="input-group">
                @php
                    $ini_entrevista = (@$datos->concurso->ini_entrevista) ? date('d-m-Y', strtotime($datos->concurso->ini_entrevista)) : '';
                    $fin_entrevista = (@$datos->concurso->fin_entrevista) ? date('d-m-Y', strtotime($datos->concurso->fin_entrevista)) : '';
                @endphp
                <div class="input-group-append">
                    <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
                </div>
                <input type="text" class="form-control" id="datepicker-autoclose_ini_entrevista" placeholder="dd-mm-yyyy" value="{{ $ini_entrevista }}" required>
                <input type="hidden" name="ini_entrevista" id="ini_entrevista" value="{{old("ini_entrevista", $datos->concurso->ini_entrevista ?? "")}}">
            </div>
        </div>
        <label for="fin_entrevista" class="col-sm-2 text-right requerido">Finalización</label>
        <div class="col-sm-3">
            <div class="input-group">
                <div class="input-group-append">
                    <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
                </div>
                <input type="text" class="form-control" id="datepicker-autoclose_fin_entrevista" placeholder="dd-mm-yyyy" value="{{ $fin_entrevista }}" required>
                <input type="hidden" name="fin_entrevista" id="fin_entrevista" value="{{old("fin_entrevista", $datos->concurso->fin_entrevista ?? "")}}">
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="perfil" class="col-sm-2 text-right control-label col-form-label requerido">Perfil</label>
        <div class="col-sm-10">
            <textarea name="perfil" id="perfil" class="form-control" maxlength="300" value="" placeholder="Perfil" required>{{old("perfil", $datos->concurso->perfil ?? "")}}</textarea>
        </div>
    </div>

    <div class="form-group row">
        <label for="proceso" class="col-sm-2 text-right control-label col-form-label requerido">Proceso</label>
        <div class="col-sm-10">
            <textarea name="proceso" id="proceso" class="form-control" maxlength="300" value="" placeholder="Perfil" required>{{old("proceso", $datos->concurso->proceso ?? "")}}</textarea>
        </div>
    </div>
</div>
