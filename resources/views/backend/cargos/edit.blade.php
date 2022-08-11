@extends('layout.app')

@section('titulo', 'Cargos')

@section('scripts')
<script src="{{asset("assets/concursos/pages/cargos/crear.js")}}"></script>
@endsection

@section('contenido')
<div class="row">
	<div class="col-md-12">
		<div class="card">
			@if ($errors->any())
              <x-alerts tipo="danger" :mensaje="$errors" />
            @endif
			<div class="card-header bg-info">
                <h4 class="card-title text-white">Editar Cargo: <b>{{$data->cargo->descripcion}}</b></h4>
            </div>
			<form action="{{route('cargos.update', $data->cargo->id)}}" class="form-horizontal" id="form-general" method="POST" autocomplete="off">
				@csrf @method('put')
				<input type="hidden" name="user_mod" value="{{$data->user}}">
                <input type="hidden" name="fecha_mod" value="{{date('Y-m-d')}}">
				@include('backend.cargos.form')
                <div class="border-top">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="reset" class="btn btn-info" onclick="location.href='{{route('cargos')}}'">Regresar</button>
                    </div>
                </div>
            </form>
		</div>
	</div>
</div>
@endsection
