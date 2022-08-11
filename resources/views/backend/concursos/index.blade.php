@extends('layout.app')

@section('titulo', 'Concurso MSPyBS')

@section('scripts')
    <script src="{{asset("assets/concursos/js/index.js")}}"></script>
@endsection

@section('contenido')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <h5 class="text-white float-left">Listado de Concurso MSPyBS</h5>
                <a href="{{route('concourse.create')}}" class="btn btn-outline-light btn-sm float-right"><i class="fas fa-plus"></i> Crear</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Concurso</th>
                                <th>Cargo</th>
                                <th>Postulación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @include('backend.concursos.data-table')
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Concurso</th>
                                <th>Cargo</th>
                                <th>Postulación</th>
                                <th>Acciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.modal')
@endsection


