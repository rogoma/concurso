@extends('layout.app')

@section('titulo', 'Usuarios')

@section('scripts')
<script src="{{asset("assets/concursos/pages/usuarios/crear.js")}}"></script>
@endsection

@section('contenido')
<div class="row">
	<div class="col-md-12">
		<div class="card">
            @if ($errors->any())
              <x-alerts tipo="danger" :mensaje="$errors" />
            @endif
            <div class="card-header bg-success">
                <h4 class="card-title text-white">Crear Usuario</h4>
            </div>
			<form action="{{route('usuarios')}}" id="form-general" class="form-horizontal" method="POST" autocomplete="off">
				@csrf
                <input type="hidden" name="user_crea" value="{{$datos->user}}">
                <input type="hidden" name="activo" value="1">
                @include('backend.usuarios.form')
                <div class="border-top">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success">Crear</button>
                        <button type="reset" class="btn btn-info" onclick="location.href='{{route('usuarios')}}'">Regresar</button>
                    </div>
                </div>
            </form>
		</div>
	</div>
</div>
@endsection
