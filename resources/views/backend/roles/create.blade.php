@extends('layout.app')

@section('titulo', 'Roles')

@section('scripts')
<script src="{{asset("assets/concursos/pages/roles/crear.js")}}"></script>
@endsection

@section('contenido')
<div class="row">
	<div class="col-md-12">
		<div class="card">
            @if ($errors->any())
              <x-alerts tipo="danger" :mensaje="$errors" />
            @endif
            <div class="card-header bg-success">
                <h4 class="card-title text-white">Crear Rol</h4>
            </div>
			<form action="{{route('roles')}}" id="form-general" class="form-horizontal" method="POST" autocomplete="off">
				@csrf
                <input type="hidden" name="user_crea" value="{{$datos->user}}">
                <input type="hidden" name="fecha_crea" value="{{$datos->fecha}}">
                @include('backend.roles.form')
                <div class="border-top">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success">Crear</button>
                        <button type="reset" class="btn btn-info" onclick="location.href='{{route('roles')}}'">Regresar</button>
                    </div>
                </div>
            </form>
		</div>
	</div>
</div>
@endsection