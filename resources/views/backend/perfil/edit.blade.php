@extends('layout.app')

@section('titulo', 'Perfil')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{asset("assets/assets/libs/select2/dist/css/select2.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("assets/assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css")}}">
@endsection

@section('scriptsPluggins')
    <script src="{{asset("assets/assets/libs/select2/dist/js/select2.full.min.js")}}"></script>
    <script src="{{asset("assets/assets/libs/select2/dist/js/select2.min.js")}}"></script>
    <script src="{{asset("assets/assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js")}}"></script>
@endsection

@section('scripts')
    <script src="{{asset("assets/concursos/pages/perfil/crear.js")}}"></script>
@endsection

@section('contenido')
<div class="row">
	<div class="col-md-12">
		<div class="card">
            @if ($errors->any())
              <x-alerts tipo="danger" :mensaje="$errors" />
            @endif
			<div class="card-header bg-info">
                <h4 class="card-title text-white">Editar Perfil: <b>{{$datos->perfil->nombres}} {{$datos->perfil->apellidos}}</b></h4>
            </div>
			<form
                action="{{route('perfil.update', $datos->perfil->id)}}"
                class="form-horizontal"
                id="form-general"
                method="POST"
                enctype="multipart/form-data"
                autocomplete="off"
            >
				@csrf @method('put')
				<input type="hidden" name="user_mod" value="{{$datos->user}}">
                @include('backend.perfil.form')
                <div class="border-top">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="reset" class="btn btn-info" onclick="location.href='{{route('perfil')}}'">Regresar</button>
                    </div>
                </div>
            </form>
		</div>
	</div>
</div>
@endsection
