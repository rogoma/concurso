@extends('layout.app')

@section('titulo', 'Validación Curricular')

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
<script src="{{asset("assets/concursos/pages/evaldoc/crear.js")}}"></script>
@endsection

@section('contenido')
<div class="row">
	<div class="col-12">
		<div class="card">
            @if ($mensaje = session("mensaje"))
                @if(strstr($mensaje, 'ERROR'))
                    <x-alerts tipo="danger" :mensaje="$mensaje" />
                @else
                    <x-alerts tipo="success" :mensaje="$mensaje" />
                @endif
            @endif
            @if ($errors->any())
              <x-alerts tipo="danger" :mensaje="$errors" />
            @endif
            <div class="card-header bg-info">
                <h5 class="text-white float-left">Evaluación Curricular</h5>
                <a href="{{route('concursos')}}" class="btn btn-outline-light btn-sm float-right"><i class="fas fa-plus"></i> Concursos</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form action="{{ route('curricular.search') }}" class="form-horizontal" method="POST">@csrf
                            @include('frontend.validar.curricular.form-curricular')
                            <div class="form-group row">
                                <div class="col-sm-12 text-center">
                                    <button type="submit" class="btn btn-success">Buscar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row border-top">
                    <div class="col-sm-12 card-header bg-info">
                        @if ($datos->concurso !== null)
                            <h5 class="text-white float-left">Concurso Nº {{ $datos->concurso->id }} - {{ $datos->concurso->decripcion }} - {{ $datos->concurso->cargos->descripcion }} (Admitidos)</h5>
                            @if ($datos->fechas !== null)
                            <span class="text-white float-right">Fechas: <b>{{ $datos->fechas }}</b></span>
                            @endif
                        @endif
                    </div>
                </div>
                <br />
                <div class="table-responsive row">
                    <table class="table table-striped table-bordered" id="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>CI</th>
                                <th>Postulante</th>
                                <th>Fecha de Postulación</th>
                                <th>Eval. Curricular</th>
                                <th>Evaluador</th>
                                <th>Fecha de Evaluación</th>
                                <th>Evaluación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($datos->postulacion !== null)
                                @include('frontend.validar.curricular.data-table')
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>CI</th>
                                <th>Postulante</th>
                                <th>Fecha de Postulación</th>
                                <th>Eval. Curricular</th>
                                <th>Evaluador</th>
                                <th>Fecha de Evaluación</th>
                                <th>Evaluación</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
