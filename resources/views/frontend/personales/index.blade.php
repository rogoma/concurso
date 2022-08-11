@extends('layout.app')

@section('titulo', 'Datos Personales')

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
            @if (session('role_slug') == 'postulantes')
            <div class="card-header bg-info">
                <h5 class="text-white float-left">Datos Personales y Otros</h5>
                <a href="{{route('personales.create')}}" class="btn btn-outline-light btn-sm float-right"><i class="fas fa-plus"></i> Crear</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo Documentos</th>
                                <th>Fecha</th>
                                <th>Dato Adicional</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @include('frontend.personales.data-table')
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Tipo Documentos</th>
                                <th>Fecha</th>
                                <th>Dato Adicional</th>
                                <th>Acciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @else
            <div class="card-header bg-success">
                <h5 class="text-white float-left">Datos Personales y Otros</h5>
                <a href="{{route('perfil')}}" class="btn btn-outline-light btn-sm float-right"><i class="far fa-arrow-alt-circle-left"></i> Regresar</a>
            </div>
            <div class="card-body">
                <h3>Su tipo de Usuario no precisa Datos Personales y Otros</h3>
            </div>
            @endif
        </div>
	</div>
</div>
@include('layout.modal')
@endsection

