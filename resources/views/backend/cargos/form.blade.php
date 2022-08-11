<div class="card-body">
    <div class="form-group row">
        <label for="tipo" class="col-sm-1 text-right control-label col-form-label requerido">Cargo</label>
        <div class="col-sm-7">
            <input type="text" name="descripcion" id="descripcion" class="form-control" maxlength="50" value="{{old("descripcion", $data->cargo->descripcion ?? "")}}" placeholder="Cargo" required>
        </div>
    </div>
</div>
