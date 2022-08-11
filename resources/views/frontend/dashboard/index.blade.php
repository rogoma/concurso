@extends('layout.app')

@section('titulo', 'Dashboard')

@section('scripts')
    <script src="{{asset("assets/concursos/js/index.js")}}"></script>
@endsection

@section('contenido')
<div class="row">
	<div class="col-12">
		<div class="card">
        @if(session('perfil') == true && session('ci'))
            <div class="card-header bg-info">
                <h5 class="text-white float-left">Listado de Concursos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="data-table">
                        <thead>
                            <tr>
                                <th>Concurso</th>
                                <th>Cargo</th>
                                <th>Vacantes</th>
                                <th>Salario</th>
                                <th>Postulación</th>
                                <th>Postularse</th>
                            </tr>
                        </thead>
                        <tbody>
                            @include('frontend.dashboard.data-table')
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Concurso</th>
                                <th>Cargo</th>
                                <th>Vacantes</th>
                                <th>Salario</th>
                                <th>Postulación</th>
                                <th>Postularse</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
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
                }, 3000);

            </script>
        @else
            <div class="alert alert-success" alert-dismissible role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" aria-hidden="true">&times;</button>
                <h4 class="alert-heading"><i class="icon fas fa-check"></i> Concursos MSPyBS</h4>
                <p>Usuario Activado. Favor reingrese al sistema</p>
            </div>
        @endif
        </div>
	</div>
</div>
@endsection

