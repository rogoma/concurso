@extends('layout.app')

@section('titulo', 'Experiencias Laborales')

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
<script src="{{asset("assets/concursos/pages/laborales/crear.js")}}"></script>
@endsection

@section('contenido')
<div class="row">
	<div class="col-md-12">
		<div class="card">
            @if ($errors->any())
              <x-alerts tipo="danger" :mensaje="$errors" />
            @endif
            @if (session('role_slug') == 'postulantes')
            <div class="card-header bg-success">
                <h4 class="card-title text-white">Crear Experiencia Laboral</h4>
            </div>
			<form
                action="{{route('laborales')}}"
                id="form-general"
                class="form-horizontal"
                method="POST"
                enctype="multipart/form-data"
                autocomplete="off"
            >
				@csrf
                <input type="hidden" name="usuario_id" value="{{$data->usuario_id}}">
                <input type="hidden" name="user_crea" value="{{$data->user}}">
                @include('frontend.laborales.form')
                <div class="border-top">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success">Crear</button>
                        <button type="reset" class="btn btn-info" onclick="location.href='{{route('laborales')}}'">Regresar</button>
                    </div>
                </div>
            </form>
            @else
            <div class="card-header bg-cyan">
                <h5 class="text-white float-left">Experiencias Laborales</h5>
                <a href="{{route('perfil')}}" class="btn btn-outline-light btn-sm float-right"><i class="far fa-arrow-alt-circle-left"></i> Regresar</a>
            </div>
            <div class="card-body">
                <h3>Su tipo de Usuario no precisa Datos Personales y Otros</h3>
            </div>
            @endif
		</div>
	</div>
</div>
@endsection
