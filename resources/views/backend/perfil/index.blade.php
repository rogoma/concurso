@extends('layout.app')

@section('titulo', 'Perfil')

@section('contenido')
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    @if (session('perfil') == false)
        <div class="alert alert-danger" alert-dismissible role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" aria-hidden="true">&times;</button>
            <h4 class="alert-heading"><i class="icon fas fa-exclamation-triangle"></i> Concursos MSPyBS</h4>
            <p>Favor de Completar sus datos de Perfil</p>
        </div>
    @endif
    @if ($mensaje = session("mensaje"))
        <x-alerts tipo="success" :mensaje="$mensaje" />
    @endif
    @if ($errors->any())
        <x-alerts tipo="danger" :mensaje="$errors" />
    @endif
    <div class="row">
    	<div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="text-center">
                        @if($data->perfil->foto === NULL)
                            <i class="fas fa-user-circle fa-10x"></i>
                        @else
                            <img width="100px" class="profile-user-img img-fluid img-circle" src="{{asset($data->perfil->foto)}}" alt="Foto de Perfil">
                        @endif
                    </div>
                    <h3 class="card-title">{{ $data->perfil->nombres }} {{ $data->perfil->apellidos }}</h3>
                    <ul class="list-group list-group-unbordered mb-3 text-left">
                        <li class="list-group-item">
                            <i class="fas fa-fingerprint"></i> <span class="float-right">{{$data->usuario->id}}</span>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-id-card"></i> <span class="float-right">{{$data->usuario->ci}}</span>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-envelope-square"></i> <span class="float-right">{{$data->usuario->email}}</span>
                        </li>
                        <li class="list-group-item">
                            @if($data->usuario->activo === true)
                                <i class="fas fa-check-circle"></i> <span class="float-right">Activo</span>
                            @else
                                <i class="fas fa-minus-circle"></i> <span class="float-right">No Activo</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="accordion" id="accordionExample">
                <div class="card m-b-0">
                    <div class="card-header bg-info" id="headingOne">
                      <h5 class="mb-0 text-white">
                        <a  data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <i class="m-r-5 fas fa-edit" aria-hidden="true"></i>
                            <span>Datos del Usuario (Perfil)</span>
                        </a>
                        <a href="{{route('perfil.edit', $data->usuario->perfil->id)}}" class="btn btn-outline-light btn-sm float-right"><i class="fas fa-edit"></i> Editar</a>
                      </h5>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="row">
                                <label class="col-md-3"><b>Genero</b></label>
                                <label class="col-md-3"><b>Fecha Nacimiento</b></label>
                                <label class="col-md-3"><b>Teléfono Movil</b></label>
                                <label class="col-md-3"><b>Otro Teléfono</b></label>
                            </div>
                            <div class="row">
                                @if($data->perfil->genero === 'M')
                                <div class="col-md-3"><i class="fas fa-male"></i> <span> Masculino</span></div>
                                @else
                                <div class="col-md-3"><i class="fas fa-female"></i> <span> Femenino</span></div>
                                @endif
                                <div class="col-md-3"><i class="fas fa-birthday-cake"></i>
                                @if($data->perfil->fecha_nac)
                                    <span> {{date('d-m-Y', strtotime($data->perfil->fecha_nac))}}</span>
                                @else
                                    <span> </span>
                                @endif
                                </div>
                                <div class="col-md-3"><i class="fas fa-mobile-alt"></i> <span> {{$data->perfil->telef_cel}}</span></div>
                                <div class="col-md-3"><i class="fas fa-phone-square"></i> <span> {{$data->perfil->telef_2}}</span></div>
                            </div>
                            <div class="row">
                                <hr>
                            </div>
                            <div class="row">
                                <label class="col-md-6"><b>Dirección Particular</b></label>
                                <label class="col-md-3"><b>Ciudad</b></label>
                                <label class="col-md-3"><b>Departamento</b></label>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><i class="fas fa-home"></i> <span>{{$data->perfil->direccion}}</span></div>
                                <div class="col-md-3"><i class="fas fa-map-marker-alt"></i> <span>{{$data->perfil->ciudad}}.</span></div>
                                <div class="col-md-3"><i class="fas fa-map"></i> <span>{{$data->perfil->dpto}}.</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (@$perfil->id !== 1 && session('perfil') == true)
                <div class="card m-b-0 border-top">
                    <div class="card-header bg-success" id="headingTwo">
                      <h5 class="mb-0 text-white">
                        <a class="collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <i class="m-r-5 fas fa-address-book" aria-hidden="true"></i>
                            <span>Datos Personales y Otros</span>
                        </a>
                        <a href="{{route('personales')}}" class="btn btn-outline-light btn-sm float-right"><i class="fas fa-edit"></i> Ver</a>
                      </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                      <div class="card-body">
                        <div class="row">
                            <label class="col-md-4"><b>Tipo Documento</b></label>
                            <label class="col-md-4"><b>Fecha</b></label>
                            <label class="col-md-4"><b>Dato Adicional</b></label>
                        </div>
                        @if ($data->personales !== false)
                            @foreach ($data->personales as $p)
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
                        <a href="{{route('academicos')}}" class="btn btn-outline-light btn-sm float-right"><i class="fas fa-edit"></i> Ver</a>
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
                        @if ($data->academicos !== false)
                            @foreach ($data->academicos as $a)
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
                        <a href="{{route('capacitacion')}}" class="btn btn-outline-light btn-sm float-right"><i class="fas fa-edit"></i> Ver</a>
                      </h5>
                    </div>
                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                      <div class="card-body">
                        <div class="row">
                            <label class="col-md-4"><b>Curso</b></label>
                            <label class="col-md-4"><b>Institución</b></label>
                            <label class="col-md-4"><b>Fechas</b></label>
                        </div>
                        @if ($data->capacitaciones !== false)
                            @foreach ($data->capacitaciones as $c)
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
                        <a href="{{route('laborales')}}" class="btn btn-outline-light btn-sm float-right"><i class="fas fa-edit"></i> Ver</a>
                      </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                      <div class="card-body">
                        <div class="row">
                            <label class="col-md-4"><b>Puesto</b></label>
                            <label class="col-md-4"><b>Institución</b></label>
                            <label class="col-md-4"><b>Fechas</b></label>
                        </div>
                        @if ($data->laborales !== false)
                            @foreach ($data->laborales as $l)
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
                @endif
            </div>
        </div>
    </div>

	<!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
@endsection
