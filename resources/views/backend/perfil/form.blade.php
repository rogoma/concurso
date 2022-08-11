<div class="card-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group row">
                <label for="nombres" class="col-sm-12 text-center control-label col-form-label">Foto de Perfil</label>
            </div>
            <div class="form-group row">
                <div class="text-center">
                    @if($datos->perfil->foto === null)
                        <i class="fas fa-user-circle fa-10x"></i>
                    @else
                        <img width="100px" class="profile-user-img img-fluid img-circle" src="{{asset($datos->perfil->foto)}}" alt="Foto de Perfil">
                    @endif
                    <div class="custom-file">
                        <input
                            name="foto"
                            type="file"
                            @if (@$datos->perfil->id == 1) disabled @endif />
                        <label
                            for="validatedCustomFile">Archivo...</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group row">
                <label for="nombres" class="col-sm-2 text-right control-label col-form-label requerido">Nombres</label>
                <div class="col-sm-10">
                    @if ($datos->rol->slug == 'postulante' && $datos->perfil->postulante == 'I')
                        <input type="text" name="nombres" id="nombres" class="form-control" maxlength="60" value="{{old("nombres", $datos->perfil->nombres ?? "")}}" placeholder="Nombres" disabled>
                    @else
                        <input type="text" name="nombres" id="nombres" class="form-control" maxlength="60" value="{{old("nombres", $datos->perfil->nombres ?? "")}}" placeholder="Nombres" @if (@$datos->perfil->id == 1) disabled @endif required>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="apellidos" class="col-sm-2 text-right control-label col-form-label requerido">Apellidos</label>
                <div class="col-sm-10">
                    @if ($datos->rol->slug == 'postulante' && $datos->perfil->postulante == 'I')
                        <input type="text" name="apellidos" id="apellidos" class="form-control" maxlength="60" value="{{old("apellidos", $datos->perfil->apellidos ?? "")}}" placeholder="Apellidos" disabled required>
                    @else
                        <input type="text" name="apellidos" id="apellidos" class="form-control" maxlength="60" value="{{old("apellidos", $datos->perfil->apellidos ?? "")}}" placeholder="Apellidos" @if (@$datos->perfil->id == 1) disabled @endif required>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="genero" class="col-sm-2 text-right control-label col-form-label requerido">Genero</label>
                <div class="col-sm-2">
                    <select class="select2 form-select shadow-none" style="width: 100%; height: 36px" name="genero" @if (@$datos->perfil->id == 1) disabled @endif required>
                        @if ($datos->perfil->genero == 'M')
                            <option value="{{old("genero", $datos->perfil->genero ?? "")}}">Másculino</option>
                            <option value="F">Femenino</option>
                        @else
                            <option value="{{old("genero", $datos->perfil->genero ?? "")}}">Femenino</option>
                            <option value="M">Másculino</option>
                        @endif
                    </select>
                </div>
                <label for="fecha_nac" class="col-sm-3 text-right control-label col-form-label requerido">Fecha de Nacimiento</label>
                <div class="col-sm-5 input-group">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                    </div>
                    @if (@$datos->perfil->fecha_nac)
                    <input type="text" class="form-control" id="datepicker-autoclose" value="{{old("fecha_nac", date('d-m-Y', strtotime($datos->perfil->fecha_nac)) ?? "")}}" placeholder="dd-mm-yyyy" @if (@$datos->perfil->id == 1) disabled @endif required>
                    @else
                        <input type="text" class="form-control" id="datepicker-autoclose" placeholder="dd-mm-yyyy" required>
                    @endif
                    <input type="hidden" name="fecha_nac" id="fecha_nac" value="{{old("fecha_nac", $datos->perfil->fecha_nac ?? "")}}">

                </div>
            </div>
            <div class="form-group row">
                <label for="direccion" class="col-sm-2 text-right control-label col-form-label requerido">Dirección</label>
                <div class="col-sm-10">
                    <input type="text" name="direccion" id="direccion" class="form-control" maxlength="100" value="{{old("direccion", $datos->perfil->direccion ?? "")}}" placeholder="Dirección" @if (@$datos->perfil->id == 1) disabled @endif required>
                </div>
            </div>
            <div class="form-group row">
                <label for="ciudad" class="col-sm-2 text-right control-label col-form-label requerido">Ciudad</label>
                <div class="col-sm-4">
                    <input type="text" name="ciudad" id="Ciudad" class="form-control" maxlength="60" value="{{old("ciudad", $datos->perfil->ciudad ?? "")}}" placeholder="Ciudad" @if (@$datos->perfil->id == 1) disabled @endif required>
                </div>
                <label for="dpto" class="col-sm-2 text-right control-label col-form-label requerido">Departamento</label>
                <div class="col-sm-4">
                    <input type="text" name="dpto" id="dpto" class="form-control" maxlength="60" value="{{old("dpto", $datos->perfil->dpto ?? "")}}" placeholder="Departamento" @if (@$datos->perfil->id == 1) disabled @endif required>
                </div>
            </div>
            <div class="form-group row">
                <label for="telef_cel" class="col-sm-2 text-right control-label col-form-label requerido">Teléfono Movil</label>
                <div class="col-sm-4">
                    <input type="text" name="telef_cel" id="telef_cel" class="form-control" maxlength="60" value="{{old("telef_cel", $datos->perfil->telef_cel ?? "")}}" placeholder="Teléfono Movil" @if (@$datos->perfil->id == 1) disabled @endif required>
                </div>
                <label for="telef_cel" class="col-sm-2 text-right control-label col-form-label">Otro Teléfono</label>
                <div class="col-sm-4">
                    <input type="text" name="telef_2" id="telef_2" class="form-control" maxlength="60" value="{{old("telef_2", $datos->perfil->telef_2 ?? "")}}" placeholder="Otro Teléfono" @if (@$datos->perfil->id == 1) disabled @endif>
                </div>
            </div>
        </div>
    </div>
</div>
