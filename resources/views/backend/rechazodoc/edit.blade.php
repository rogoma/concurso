@extends('layout.app')

@section('titulo', 'Tipo de Documentos')

@section('scripts')
<script src="{{asset("assets/concursos/pages/tipodoc/crear.js")}}"></script>
@endsection

@section('contenido')
<div class="row">
	<div class="col-md-12">
		<div class="card">
			@if ($errors->any())
              <x-alerts tipo="danger" :mensaje="$errors" />
            @endif
			<div class="card-header bg-info">
                <h4 class="card-title text-white">Editar Tipo de Documento: <b>{{$data->rechazo->rechazo}}</b></h4>
            </div>
			<form action="{{route('rechazodoc.update', $data->rechazo)}}" class="form-horizontal" id="form-general" method="POST" autocomplete="off">
				@csrf @method('put')
				<input type="hidden" name="user_mod" value="{{$data->user}}">
                @include('backend.rechazodoc.form')
                <div class="border-top">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="reset" class="btn btn-info" onclick="location.href='{{route('rechazodoc')}}'">Regresar</button>
                    </div>
                </div>
            </form>
		</div>
	</div>
</div>
@endsection
