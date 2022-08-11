@extends('layout.app')

@section('titulo', 'Postulaciones')

@section('scripts')
    <script src="{{asset("assets/concursos/js/index.js")}}"></script>
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
                <h5 class="text-white float-left">Validación de Postulaciones</h5>
                <a href="{{route('concursos')}}" class="btn btn-outline-light btn-sm float-right"><i class="fas fa-plus"></i> Concursos</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Concurso</th>
                                <th>Cargo</th>
                                <th>Postulante</th>
                                <th>Fecha de Postulación</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datos->postulacion as $dp)
                                <tr>
                                    <td>{{ $dp->id }}</td>
                                    <td>{{ $dp->concurso->decripcion}}</td>
                                    <td>{{ $dp->concurso->cargos->descripcion }}</td>
                                    <td>{{ $dp->usuario->perfil->nombres }} {{ $dp->usuario->perfil->apellidos }}</td>
                                    <td>{{ date('d/m/Y H:i:s', strtotime($dp->fecha_post)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Concurso</th>
                                <th>Cargo</th>
                                <th>Postulante</th>
                                <th>Fecha de Postulación</th>
                                <th>Acción</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
