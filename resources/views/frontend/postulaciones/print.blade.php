<!DOCTYPE html>
<html dir="ltr" lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postulación</title>
    <link href="{{asset("assets/dist/css/style.min.css")}}" rel="stylesheet" />
</head>
<body>
<div class="row">
	<div class="col-12">
		<div class="card">
            @if (session('role_slug') == 'postulantes')
            <div class="col-4" align="center">
                <button type="button" class="btn btn-secondary" id="btnImprimir">
                    <i class="fas fa-print"></i></button>
                <button type="button" class="btn btn-secondary" onclick="window.close()">
                    <i class="fas fa-times"></i></button>
            </div>
            <div class="card-body" id="imprimible">
                <div class="col-md-12">

                    <div class="card border">
                        <div class="card-header bg-secondary">
                          <h5 class="mb-0 text-white">
                            <span>{{ $datos->postulacion->usuario->perfil->nombres }} {{ $datos->postulacion->usuario->perfil->apellidos }} - Postulación Nº {{ $datos->postulacion->id }}</span>
                          </h5>
                        </div>
                        <div class="row">
                            <div class="card-body">
                                <div class="row text-center">
                                    <table width="100%">
                                        <thead>
                                            <tr>
                                                <th>Concurso</th>
                                                <th>Cargo</th>
                                                <th>Fecha Postulación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $datos->postulacion->concurso->decripcion }}</td>
                                                <td>{{ $datos->postulacion->concurso->cargos->descripcion }}</td>
                                                <td>{{ date('d/m/Y H:i', strtotime($datos->postulacion->fecha_post)) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card m-b-0 border">
                        <div class="card-header bg-secondary">
                          <h5 class="mb-0 text-white"><span>Datos Personales y Otros</span></h5>
                        </div>
                        <div class="row">
                          <div class="card-body">
                            <div class="row text-center">
                                <table width="80%" align="center">
                                    <thead>
                                        <tr>
                                            <th>Tipo Documento</th>
                                            <th>Fecha</th>
                                            <th>Dato Adicional</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($datos->postulacion->postDatosPersonales)>0)
                                        @foreach ($datos->postulacion->postDatosPersonales as $p)
                                        <tr class="text-left">
                                            <td><span>{{$p->tipoDoc->documento}}</span></td>
                                            <td><span>{{date('d/m/Y', strtotime($p->fecha))}}</span></td>
                                            <td><span>{{$p->dato_adic}}</span></td>
                                        </tr>
                                         @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                          </div>
                        </div>
                    </div>

                    <div class="card m-b-0 border">
                        <div class="card-header bg-secondary">
                          <h5 class="mb-0 text-white"><span>Datos Académicos</span></h5>
                        </div>
                        <div class="row">
                          <div class="card-body">
                            <div class="row text-center">
                                <table width="80%" align="center">
                                    <thead>
                                        <tr>
                                            <th>Tipo Documento</th>
                                            <th>Título</th>
                                            <th>Fecha</th>
                                            <th>Institución</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($datos->postulacion->postDatosAcads)>0)
                                        @foreach ($datos->postulacion->postDatosAcads as $a)
                                            <tr>
                                                <td><span>{{$a->tipoDoc->documento}}</span></td>
                                                <td><span>{{$a->titulo}}</span></td>
                                                <td><span>{{date('d/m/Y', strtotime($a->fecha))}}</span></td>
                                                <td><span>{{$a->institucion}}</span></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                          </div>
                        </div>
                    </div>

                    <div class="card m-b-0 border">
                        <div class="card-header bg-secondary">
                          <h5 class="mb-0 text-white"><span>Capacitaciones</span></h5>
                        </div>
                        <div class="row">
                          <div class="card-body">
                            <div class="row text-center">
                                <table width="80%" align="center">
                                    <thead>
                                        <tr>
                                            <th>Curso</th>
                                            <th>Institución</th>
                                            <th>Fechas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($datos->postulacion->postCapacitaciones)>0)
                                        @foreach ($datos->postulacion->postCapacitaciones as $c)
                                            <tr>
                                                <td><span>{{$c->tema_curso}}</span></td>
                                                <td><span>{{$c->institucion}}</span></td>
                                                <td><span>{{date('d/m/Y', strtotime($c->fecha_ini))}} - {{date('d/m/Y', strtotime($c->fecha_fin))}}</span></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                          </div>
                        </div>
                    </div>

                    <div class="card m-b-0 border">
                        <div class="card-header bg-secondary">
                          <h5 class="mb-0 text-white"><span>Experiencia Laboral</span></h5>
                        </div>
                        <div class="row">
                          <div class="card-body">
                            <div class="row text-center">
                                <table width="80%" align="center">
                                    <thead>
                                        <tr>
                                            <th>Puesto</th>
                                            <th>Institución</th>
                                            <th>Fechas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($datos->postulacion->postExpLaborales)>0)
                                        @foreach ($datos->postulacion->postExpLaborales as $l)
                                            <tr>
                                                <td><span>{{$l->puesto}}</span></td>
                                                <td><span>{{$l->institucion}}</span></td>
                                                <td><span>{{date('d/m/Y', strtotime($l->fecha_ini))}} - {{date('d/m/Y', strtotime($l->fecha_fin))}}</span></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                          </div>
                        </div>
                    </div>

                </div>
            </div>
            @else
            <div class="card-header bg-danger">
                <h5 class="text-white float-left">Postulación</h5>
            </div>
            <div class="card-body">
                <h1>Acceso restringido</h1>
            </div>
            <script type="text/javascript">
                window.onload = setTimeout(function() {
                    document.forms['logout'].submit();
                }, 3000);
            </script>
            @endif
        </div>
	</div>
</div>
<script type="text/javascript">
    function imprimirElemento(elemento) {
        var ventana = window.open('', 'PRINT', 'height=400,width=600');
        ventana.document.write('<html><head><title>' + document.title + '</title>');
        ventana.document.write('<link rel="stylesheet" href="{{asset("assets/dist/css/style.min.css")}}">');
        ventana.document.write('</head><body >');
        ventana.document.write(elemento.innerHTML);
        ventana.document.write('</body></html>');
        ventana.document.close();
        ventana.focus();
        ventana.onload = function() {
            ventana.print();
            ventana.close();
        };
        return true;
    }
    document.querySelector("#btnImprimir").addEventListener("click", function() {
        var div = document.querySelector("#imprimible");
        imprimirElemento(div);
    });

    /*document.addEventListener("DOMContentLoaded", function(event) {
        //printHTML();
        var div = document.querySelector("#imprimible");
        imprimirElemento(div);
    });*/
</script>
</body>
</html>