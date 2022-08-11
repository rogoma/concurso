@extends('layout.app')

@section('titulo', 'Validación Curricular')

@section('scripts')
<script src="{{asset("assets/concursos/pages/evalcurr/eval.js")}}"></script>
@endsection

@section('contenido')
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
                <h5 class="text-white float-left">Evaluación de la Matriz Curricular</h5>
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
                <div class="table-responsive row">
                    <form action="{{ route('curricular.store') }}" method="POST" name="matriz" class="form-horizontal" id="form-horizontal">
                    @csrf
                    <input type="hidden" name="estado" value="{{ $datos->estado }}">
                    <input type="hidden" name="concurso" value="{{ $datos->concurso->id }}">
                    <input type="hidden" name="postulacion_id" value="{{ $datos->postulacion->id }}">
                    <input type="hidden" name="fechas" value="{{ $datos->fechas }}">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Item</th>
                                    <th>Puntuación<br>MIN - MAX</th>
                                    <th>Doc. Presentados</th>
                                    <th>Puntaje<br /><small>( . para decimanles)</small></th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($datos->matriz !== null)
                                    @include('frontend.validar.curricular.data-table-matriz')
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Item</th>
                                    <th>Puntuación<br>MIN - MAX</th>
                                    <th>Doc. Presentados</th>
                                    <th>Puntaje<br /><small>( . para decimanles)</small></th>
                                    <th>Observaciones</th>
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
@endsection
