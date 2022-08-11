@extends('layout.app')

@section('titulo', 'Concursos')

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
<script src="{{asset("assets/concursos/pages/concursos/crear.js")}}"></script>
@endsection

@section('contenido')
<div class="row">
	<div class="col-md-12">
		<div class="card">
            @if ($errors->any())
              <x-alerts tipo="danger" :mensaje="$errors" />
            @endif
            <div class="card-header bg-success">
                <h4 class="card-title text-white">Crear Concurso</h4>
            </div>
			<form action="{{route('concourse')}}" id="form-general" class="form-horizontal" method="POST" autocomplete="off">
				@csrf
                <input type="hidden" name="user_crea" value="{{$datos->user}}">
                <input type="hidden" name="fecha_crea" value="{{date('Y-m-d H:i:s')}}">
                @include('backend.concursos.form')
                <div class="border-top">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success">Crear</button>
                        <button type="reset" class="btn btn-info" onclick="location.href='{{route('concourse')}}'">Regresar</button>
                    </div>
                </div>
            </form>
		</div>
	</div>
</div>
@endsection
