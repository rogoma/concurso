@extends('layout.app')

@section('titulo', 'Datos Académicos')

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
<script src="{{asset("assets/concursos/pages/capacitaciones/crear.js")}}"></script>
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
                <h4 class="card-title text-white">Editar Capacitación</h4>
            </div>
            <form action="{{route('capacitacion.update', $data->capacitacion->id)}}" class="form-horizontal" id="form-general" method="POST" enctype="multipart/form-data" autocomplete="off">
				@csrf @method('put')
                <input type="hidden" name="usuario_id" value="{{$data->usuario_id}}">
                <input type="hidden" name="user_mod" value="{{$data->user}}">
                @include('frontend.capacitaciones.form')
                <div class="border-top">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="reset" class="btn btn-info" onclick="location.href='{{route('capacitacion')}}'">Regresar</button>
                    </div>
                </div>
            </form>
            @else
            <div class="card-header bg-secondary">
                <h5 class="text-white float-left">Capacitaciones</h5>
                <a href="{{route('perfil')}}" class="btn btn-outline-light btn-sm float-right"><i class="far fa-arrow-alt-circle-left"></i> Regresar</a>
            </div>
            <div class="card-body">
                <h3>Su tipo de Usuario no precisa Capacitaciones</h3>
            </div>
            @endif
		</div>
	</div>
</div>
@endsection
