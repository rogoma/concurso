@extends('layout.app')

@section('titulo', 'Usuarios')

@section('scripts')
    <script src="{{asset("assets/concursos/pages/usuarios/index.js")}}"></script>
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
                <h5 class="text-white float-left">Usuarios</h5>
                <a href="{{route('usuarios.create')}}" class="btn btn-outline-light btn-sm float-right"><i class="fas fa-plus"></i> Crear</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>CI</th>
                                <th>Email</th>
                                <th>Fecha Registro</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @include('backend.usuarios.data-table')
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>CI</th>
                                <th>Email</th>
                                <th>Fecha Registro</th>
                                <th>Rol</th>
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

