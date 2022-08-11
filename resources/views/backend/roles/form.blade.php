<div class="card-body">
    <div class="form-group row">
        <label for="rol" class="col-sm-2 text-right control-label col-form-label requerido">Rol</label>
        <div class="col-sm-6">
            <input type="text" name="rol" id="rol" class="form-control" maxlength="25" value="{{old("rol", $data->rol ?? "")}}" placeholder="Rol" @if (@$data->id == 1) {{disabled}} @endif required>
        </div>
        <div class="col-sm-4">
            <input type="hidden" name="slug" id="slug" class="form-control" maxlength="25" value="{{old("slug", $data->slug ?? "")}}" placeholder="Slug" @if (@$data->id == 1) {{disabled}} @endif required readonly>
        </div>
    </div>
</div>
