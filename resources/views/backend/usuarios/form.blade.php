<div class="card-body">
    @if(@$datos->usuario->id != 1)
    <div class="form-group row">
        <label for="ci" class="col-sm-1 text-right control-label col-form-label requerido">CI</label>
        <div class="col-sm-3">
            @if (@$datos->usuario->id == true)
            <input type="text" name="ci" id="ci" class="form-control" value="{{old("ci", $datos->usuario->ci ?? "")}}" placeholder="Cédula de Identidad" readonly required>
            @else
            <input type="text" name="ci" id="ci" class="form-control" placeholder="Cédula de Identidad" required>
            @endif
        </div>
        <label for="email" class="col-sm-1 text-right control-label col-form-label requerido">Email</label>
        <div class="col-sm-3">
            <input type="email" name="email" id="email" class="form-control" maxlength="60" value="{{old("email", $datos->usuario->email ?? "")}}" placeholder="Email" @if (@$datos->usuario->id == 1) {{disabled}} @endif required>
        </div>
        <label for="rol" class="col-sm-1 text-right control-label col-form-label requerido">Rol</label>
        <div class="col-sm-3">
            <select name="rol" class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                @if (@count($datos->rol) !== 0)
                <option value="{{ $datos->rol[0]->id }}">{{ $datos->rol[0]->rol }}</option>
                <option>---------</option>
                @else
                <option>Escoja un Rol</option>
                @endif
                @foreach($datos->roles as $r)
                <option value="{{ $r->id }}">{{ $r->rol }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        @if(@$datos->edit !== true)
        <label for="password" class="col-sm-2 text-right control-label col-form-label requerido">Password</label>
        <div class="col-sm-4">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        </div>
        <label for="confirmar" class="col-sm-2 text-right control-label col-form-label requerido">Confirmar Password</label>
        <div class="col-sm-4">
            <input type="password" name="password_confirmation" id="password-confirmation" class="form-control" placeholder="Password" required>
        </div>
        @endif
    </div>
    @endif
</div>
