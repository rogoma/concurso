@extends('layout.app')

@section('titulo', 'Concurso MSPyBS')

@section('scripts')
    <script src="{{asset("assets/concursos/pages/concursos/index.js")}}"></script>
@endsection

@section('contenido')
<div class="row">
	<div class="col-12">
		<div class="card">
        @if(session('perfil') == true && session('ci'))
            <div class="card-header bg-info">
                <h5 class="text-white float-left">Examen del Concurso: {{ $datos->concurso->id }} : {{ $datos->concurso->decripcion }} : {{ $datos->concurso->cargos->descripcion }}</h5>
            </div>
            <div class="card-body">
                <form action="{{route('examen.finish')}}"
                    method="POST"
                    id="form-general"
                    class="form-postulacion d-inline">
                    @csrf
                    <input type="hidden" name="postulacion_id" value="{{ $datos->postulacion->id }}">
                    <input type="hidden" name="examen_id" value="{{ $datos->examen->id }}">
                    @include('frontend.examenes.form-rendir')
                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-success">Finalizar</button>
                            <button type="reset" class="btn btn-info" onclick="location.href='{{route('concursos.evaluacion')}}'">Regresar</button>
                        </div>
                    </div>
                </form>
            </div>
        @elseif(session('perfil') == 'E')
            <div class="alert alert-danger" alert-dismissible role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" aria-hidden="true">&times;</button>
                <h4 class="alert-heading"><i class="icon fas fa-ban"></i> Concursos MSPyBS</h4>
                <p>Usuario No Activado. Sistema no Habilitado para su Usuario</p>
            </div>
            <script type="text/javascript">
                window.onload = setTimeout(function() {
                    document.forms['logout'].submit();
                }, 4000);

            </script>
        @else
            <div class="alert alert-success" alert-dismissible role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" aria-hidden="true">&times;</button>
                <h4 class="alert-heading"><i class="icon fas fa-check"></i> Concursos MSPyBS</h4>
                <p>Usuario Activado. Favor reingrese al sistema</p>
            </div>
            <script type="text/javascript">
                window.onload = setTimeout(function() {
                    document.forms['logout'].submit();
                }, 4000);

            </script>
        @endif
        </div>
	</div>
</div>
@endsection


