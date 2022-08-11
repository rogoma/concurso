@extends('layout.app')

@section('titulo', 'Validación Documental')

@section('scripts')
<script src="{{asset("assets/concursos/pages/evaldoc/eval.js")}}"></script>
@endsection

@section('contenido')
@php
    $postular_salario = true;
    if ($datos->concurso->tipo_concurso == 'A') {
        $max_sal_post = ($datos->postulante->salario *10/100) + $datos->postulante->salario;
        $postular_salario = ($datos->concurso->salario <= $max_sal_post) ? true : false;
    }
@endphp
@if ($datos->concurso->tipo_concurso == 'A' && $postular_salario == false)
<script type="text/javascript">
    window.onload = (function() {
        $('#mensaje-rechazo').modal('toggle')
    });
</script>
@endif
<div class="row">
	<div class="col-12">
		<div class="card">
            @if ($mensaje = session("mensaje"))
              <x-alerts tipo="success" :mensaje="$mensaje" />
            @endif
            @if ($errors->any())
              <x-alerts tipo="danger" :mensaje="$errors" />
            @endif
            <div class="card-header bg-info">
                <h5 class="text-white float-left">Evaluación de la Matriz Documental</h5>
                <a href="{{route('concursos')}}" class="btn btn-outline-light btn-sm float-right"><i class="fas fa-plus"></i> Concursos</a>
            </div>
            <div class="card-body">
                <div class="row border-top">
                    <div class="col-sm-12 card-header bg-cyan">
                        @if ($datos->concurso !== null)
                            <h5 class="text-white float-left">Concurso: {{ $datos->concurso->decripcion }} - {{ $datos->concurso->cargos->descripcion }}</h5>
                            <span class="text-white float-right">
                                Postulante: <b>{{ $datos->postulacion->usuario->ci }} - {{ $datos->postulacion->usuario->perfil->apellidos }}, {{ $datos->postulacion->usuario->perfil->nombres }}</b>
                            </span>
                        @endif
                    </div>
                </div>
                <br />
                <span class="float-right">
                    <label for="yesAll">Si a Todos</label>
                    <input type="radio" id="siAll" name="All" value="1" onclick="selectAll(matriz); remRequiredAll();"/>
                    <label for="notAll">No a Todos</label>
                    <input type="radio" id="noAll" name="All" value="0" onclick="selectAll(matriz); putRequiredAll();" checked/>
                </span>
                <div class="table-responsive row">
                    <form action="{{ route('documental.store') }}" method="POST" name="matriz" class="form-horizontal" id="form-horizontal">
                    @csrf
                    <input type="hidden" name="estado" value="{{ $datos->estado }}">
                    <input type="hidden" name="concurso" value="{{ $datos->concurso->id }}">
                    <input type="hidden" name="postulacion_id" value="{{ $datos->postulacion->id }}">
                    <input type="hidden" name="fechas" value="{{ $datos->fechas }}">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo</th>
                                    <th>Requerido</th>
                                    <th>Doc. Presentados</th>
                                    <th>Cumple</th>
                                    <th>Rechazo</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($datos->matriz !== null)
                                    @include('frontend.validar.documental.data-table-matriz')
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo</th>
                                    <th>Requerido</th>
                                    <th>Doc. Presentados</th>
                                    <th>Cumple</th>
                                    <th>Rechazo</th>
                                    <th>Observación</th>
                                </tr>
                            </tfoot>
                        </table>
                        <button type="submit" class="btn btn-success float-right">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
	</div>
</div>
@php
    $mensaje_header = 'Rechazo por Salario';
    $mensaje_body = 'El Salario de la Postulación excede el 10% de su Salario Actual';
@endphp
<div class="modal fade" id="mensaje-rechazo" tabindex="-1" role="dialog" aria-labelledby="mensaje-rechazo" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="mensaje-rechazo">{{ $mensaje_header }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ $mensaje_body }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection
