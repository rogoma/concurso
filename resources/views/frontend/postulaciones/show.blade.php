@extends('layout.app')

@section('titulo', 'Postulaciones')

@section('scripts')
    <script src="{{asset("assets/concursos/js/index.js")}}"></script>
@endsection

@section('contenido')
<div class="row">
	<div class="col-12">
		<div class="card">
            @if (session('role_slug') == 'postulantes')
            <div class="card-body">
                <div class="col-md-12">
                    <div class="accordion" id="accordionExample">
                        <div class="card m-b-0">
                            <div class="card-header bg-info" id="headingOne">
                              <h5 class="mb-0 text-white">
                                <a  data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <i class="m-r-5 fas fa-edit" aria-hidden="true"></i>
                                    <span>Datos del Concurso</span>
                                </a>
                                <a href="{{route('postulacion.print', $datos->postulacion->concurso_id)}}" target="_blank" class="btn btn-outline-light btn-sm float-right" title="Imprimir"><i class="fas fa-print"></i></a>
                                <a href="{{route('concursos')}}" class="btn btn-outline-light btn-sm float-right"><i class="fas fa-eye"></i> Concursos</a>
                              </h5>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="row text-center">
                                        <label class="col-md-4"><b>Concurso</b></label>
                                        <label class="col-md-4"><b>Cargo</b></label>
                                        <label class="col-md-4"><b>Fecha Postulación</b></label>
                                    </div>
                                    <div class="row text-center">
                                        <label class="col-md-4">{{ $datos->postulacion->concurso->decripcion }}</label>
                                        <label class="col-md-4">{{ $datos->postulacion->concurso->cargos->descripcion }}</label>
                                        <label class="col-md-4">{{ date('d/m/Y H:i', strtotime($datos->postulacion->fecha_post)) }}</label>
                                    </div>
                                    <div class="row">
                                        <hr>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card m-b-0 border-top">
                            <div class="card-header bg-success" id="headingTwo">
                              <h5 class="mb-0 text-white">
                                <a class="collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="m-r-5 fas fa-address-book" aria-hidden="true"></i>
                                    <span>Datos Personales y Otros</span>
                                </a>
                              </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                              <div class="card-body">
                                <div class="row">
                                    <label class="col-md-4"><b>Tipo Documento</b></label>
                                    <label class="col-md-4"><b>Fecha</b></label>
                                    <label class="col-md-4"><b>Dato Adicional</b></label>
                                </div>
                                @if (count($datos->postulacion->postDatosPersonales)>0)
                                    @foreach ($datos->postulacion->postDatosPersonales as $p)
                                    <div class="row">
                                        <div class="col-md-4"><span>{{$p->tipoDoc->documento}}</span></div>
                                        <div class="col-md-4"><span>{{date('d/m/Y', strtotime($p->fecha))}}</span></div>
                                        <div class="col-md-4"><span>{{$p->dato_adic}}</span></div>
                                    </div>
                                    @endforeach
                                @endif
                              </div>
                            </div>
                        </div>
                        <div class="card m-b-0 border-top">
                            <div class="card-header bg-primary" id="headingThree">
                              <h5 class="mb-0 text-white">
                                <a class="collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <i class="m-r-5 fas fa-book" aria-hidden="true"></i>
                                    <span>Datos Académicos</span>
                                </a>
                              </h5>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                              <div class="card-body">
                                <div class="row">
                                    <label class="col-md-3"><b>Tipo Documento</b></label>
                                    <label class="col-md-3"><b>Título</b></label>
                                    <label class="col-md-3"><b>Fecha</b></label>
                                    <label class="col-md-3"><b>Institución</b></label>
                                </div>
                                @if (count($datos->postulacion->postDatosAcads)>0)
                                    @foreach ($datos->postulacion->postDatosAcads as $a)
                                    <div class="row">
                                        <div class="col-md-3"><span>{{$a->tipoDoc->documento}}</span></div>
                                        <div class="col-md-3"><span>{{$a->titulo}}</span></div>
                                        <div class="col-md-3"><span>{{date('d/m/Y', strtotime($a->fecha))}}</span></div>
                                        <div class="col-md-3"><span>{{$a->institucion}}</span></div>
                                    </div>
                                    @endforeach
                                @endif
                              </div>
                            </div>
                        </div>
                        <div class="card m-b-0 border-top">
                            <div class="card-header bg-secondary" id="headingFive">
                              <h5 class="mb-0 text-white">
                                <a class="collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFour">
                                    <i class="m-r-5 fas fa-dna" aria-hidden="true"></i>
                                    <span>Capacitaciones</span>
                                </a>
                              </h5>
                            </div>
                            <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                              <div class="card-body">
                                <div class="row">
                                    <label class="col-md-4"><b>Curso</b></label>
                                    <label class="col-md-4"><b>Institución</b></label>
                                    <label class="col-md-4"><b>Fechas</b></label>
                                </div>
                                @if (count($datos->postulacion->postCapacitaciones)>0)
                                    @foreach ($datos->postulacion->postCapacitaciones as $c)
                                    <div class="row">
                                        <div class="col-md-4"><span>{{$c->tema_curso}}</span></div>
                                        <div class="col-md-4"><span>{{$c->institucion}}</span></div>
                                        <div class="col-md-4"><span>{{date('d/m/Y', strtotime($c->fecha_ini))}} - {{date('d/m/Y', strtotime($c->fecha_fin))}}</span></div>
                                    </div>
                                    @endforeach
                                @endif
                              </div>
                            </div>
                        </div>
                        <div class="card m-b-0 border-top">
                            <div class="card-header bg-cyan" id="headingFour">
                              <h5 class="mb-0 text-white">
                                <a class="collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    <i class="m-r-5 fas fa-building" aria-hidden="true"></i>
                                    <span>Experiencia Laboral</span>
                                </a>
                              </h5>
                            </div>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                              <div class="card-body">
                                <div class="row">
                                    <label class="col-md-4"><b>Puesto</b></label>
                                    <label class="col-md-4"><b>Institución</b></label>
                                    <label class="col-md-4"><b>Fechas</b></label>
                                </div>
                                @if (count($datos->postulacion->postExpLaborales)>0)
                                    @foreach ($datos->postulacion->postExpLaborales as $l)
                                    <div class="row">
                                        <div class="col-md-4"><span>{{$l->puesto}}</span></div>
                                        <div class="col-md-4"><span>{{$l->institucion}}</span></div>
                                        <div class="col-md-4"><span>{{date('d/m/Y', strtotime($l->fecha_ini))}} - {{date('d/m/Y', strtotime($l->fecha_fin))}}</span></div>
                                    </div>
                                    @endforeach
                                @endif
                              </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @else
            <div class="card-header bg-success">
                <h5 class="text-white float-left">Postulación</h5>
                <a href="{{route('perfil')}}" class="btn btn-outline-light btn-sm float-right"><i class="far fa-arrow-alt-circle-left"></i> Regresar</a>
            </div>
            <div class="card-body">
                <h3>Su tipo de Usuario no hace Postulaciones</h3>
            </div>
            @endif
        </div>
	</div>
</div>
@include('layout.modal')
@endsection

