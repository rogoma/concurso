<div class="form-group row">
    <label for="concurso" class="col-sm-2 text-right control-label col-form-label requerido">Concurso</label>
    <div class="col-sm-6">
        <select name="concurso_id" id="concurso_id" class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
            <option value="">Escoja el concurso...</option>
            @foreach ($datos->concursos as $c)
                <option value="{{ $c->id }}">{{ $c->id }} - {{ $c->decripcion }} - {{ $c->cargos->descripcion }}</option>
            @endforeach
        </select>
    </div>
    <label for="estado" class="col-sm-2 text-right control-label col-form-label requerido">Estado</label>
    <div class="col-sm-2">
        <select name="estado" id="estado" class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
            <option value="">Escoja una opción...</option>
            <option value="NE">No Evaluado</option>
            <option value="AD">Admitido</option>
            <option value="NA">No Admitido</option>
        </select>
    </div>
</div>
<div class="form-group row">
    <label for="fecha_ini" class="col-sm-2 text-right">Desde:</label>
    <div class="col-sm-4">
        <div class="input-group">
            <div class="input-group-append">
                <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
            </div>
            <input type="text" class="form-control" id="datepicker-autoclose_desde" placeholder="dd-mm-yyyy" value="{{old("fecha_desde")}}">
            <input type="hidden" name="fecha_desde" id="fecha_desde" value="{{old("fecha_desde")}}">
        </div>
    </div>
    <label for="fecha_fin" class="col-sm-2 text-right">Hasta:</label>
    <div class="col-sm-4">
        <div class="input-group">
            <div class="input-group-append">
                <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
            </div>
            <input type="text" class="form-control" id="datepicker-autoclose_hasta" placeholder="dd-mm-yyyy" value="{{old("fecha_hasta")}}">
            <input type="hidden" name="fecha_hasta" id="fecha_hasta" value="{{old("fecha_hasta")}}">
        </div>
    </div>
</div>

