@extends('layout.app')

@section('titulo', 'Concurso MSPyBS')

@section('scripts')
    <script src="{{asset("assets/concursos/pages/examen/index.js")}}"></script>
@endsection

@section('contenido')
<div class="row">
	<div class="col-12">
		<div class="card">
        @if(session('perfil') == true && session('ci'))
            <div class="card-header bg-info">
                <h5 class="text-white float-left">Listado de Concurso MSPyBS en Evaluación</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Concurso</th>
                                <th>Cargo</th>
                                <th>Vacantes</th>
                                <th>Salario</th>
                                <th>Postulación</th>
                                <th>Perfil</th>
                                <th>Proceso</th>
                                <th>Visualizar</th>
                                <th>Examinar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @include('frontend.concursos.data-table-evaluacion')
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Concurso</th>
                                <th>Cargo</th>
                                <th>Vacantes</th>
                                <th>Salario</th>
                                <th>Postulación</th>
                                <th>Perfil</th>
                                <th>Proceso</th>
                                <th>Visualizar</th>
                                <th>Examinar</th>
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
<div class="modal fade" id="confirmar-rendir" tabindex="-1" role="dialog" aria-labelledby="confirmar-rendir" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="confirmar-rendir">Confirmar acción</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Seguro que desea Examinarse a este Concurso?<br />
                Le informamos que tiene {{ $datos->configuracion->max_intento_examen }} intentos para examinarse, verifique los intentos en la grilla.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="button" id="accion-rendir" class="btn btn-success">Si</button>
            </div>
        </div>
    </div>
</div>
@endsection


