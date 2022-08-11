@foreach ($datos->concursos as $idx => $d)
    {{-- <pre>{{ $key.' => '. $d->cantidad_vacanc }}</pre> --}}
    <tr>
        <td>{{$d->id}}</td>
        <td>{{$d->decripcion}}</td>
        <td>{{@$d->cargos->descripcion}}</td>
        <td>{{$d->cantidad_vacanc}}</td>
        <td>{{number_format($d->salario, 0, ',', '.')}}</td>
        <td>{{date('d/m/Y', strtotime($d->ini_post))}} - {{date('d/m/Y', strtotime($d->fin_post))}}</td>
        <td class="text-center py-0 align-middle">
            <a href="{{ @$d->perfil }}" target="_blank">
                <i class="fas fa-user-tie text-primary fa-2x"></i></a>
        </td>
        <td class="text-center py-0 align-middle">
            <a href="{{ @$d->proceso }}" target="_blank">
                <i class="fas fa-users-cog text-info fa-2x"></i></a>
        </td>
        <td class="text-center py-0 align-middle">
            @if(session('role_slug') != 'postulantes')
                    <i class="fas fa-hand-point-right text-secundary fa-2x" title='No Puede Postularse'></i>
            @else
                @if(date('N',strtotime(date('Y-m-d')))!==6||date('N',strtotime(date('Y-m-d')))!==7)
                    @php
                        unset($postular);
                        if ($datos->postulaciones == false) {
                            $postular = true;
                        } else {
                            $postular = (in_array($d->id, $datos->postulaciones) == true) ? false : true;
                        }

                        unset($matrix);
                        if ($datos->tiposDoc == false) {
                            $matrix = false;
                        } else {
                            if (count($d->matrizdocs)>0) {
                                foreach ($d->matrizdocs as $md) {
                                    if ($md->requerido == true) {
                                        if($md->tipoDoc->tipo) {
                                            if (in_array($md->tipo_doc_id, $datos->tiposDoc)) {
                                                $matrix = true;
                                            } else {
                                                $matrix = false;
                                                $documento = $md->tipo_doc_id.'-'.$md->tipoDoc->documento;
                                                break;
                                            }
                                        }
                                    } else {
                                        $matrix = true;
                                    }
                                }
                            } else {
                                $matrix = true;
                            }
                        }

                        $mensaje_header = '';
                        $mensaje_body = '';

                        /*$postular_salario = true;
                        if ($d->tipo_concurso == 'A') {
                            $max_sal_post = ($datos->postulante->salario *10/100) + $datos->postulante->salario;
                            $postular_salario = ($d->salario <= $max_sal_post) ? true : false;
                        }*/
                    @endphp

                    {{--  @if ($d->tipo_concurso == 'A' && $postular_salario == false)
                        <i class="fas fa-search-dollar text-secundary fa-2x" title='No Puede Postularse' class="btn-accion-tabla boton-mensaje" data-toggle="modal" data-target="#mensaje-rechazo{{ $d->id }}"></i>
                        @php
                            $mensaje_header = 'Rechazo por Salario';
                            $mensaje_body = 'El Salario de la Postulación excede el 10% de su Salario Actual';
                        @endphp
                        --}}
                    @if ($matrix == false)
                        <i class="fas fa-search-minus text-secundary fa-2x" title='No Puede Postularse' class="btn-accion-tabla boton-mensaje" data-toggle="modal" data-target="#mensaje-rechazo{{ $d->id }}"></i>
                        @php
                            $mensaje_header = 'Rechazo por Matriz';
                            $mensaje_body = "No cumple con la matriz documental:"
                        @endphp

                    @elseif ($postular == false)
                        <a href="{{ route('postulacion.show',$d->id) }}">
                            <i class="fas fa-eye text-warning fa-2x"></i></a>
                        @php
                            $mensaje_header = '';
                            $mensaje_body = '';
                        @endphp

                    @elseif((session('role_slug') == 'postulantes') && ($postular == true)
                            && (date('H:i') > $datos->configuracion->hora_ini_post
                                && date('H:i') < $datos->configuracion->hora_fin_post))
                        @php
                            $mensaje_header = '';
                            $mensaje_body = '';
                        @endphp
                        <form action="{{route('postulacion')}}"
                            method="POST"
                            id="form-general"
                            class="form-postulacion d-inline">
                            @csrf
                            <input type="hidden" name="concurso_id" value="{{ $d->id }}">
                            <button type="submit" class="btn-accion-tabla boton-postulacion" data-toggle="modal" title="Postularse a Concurso" data-target="#modal-postulacion" style="border: none; background: none;">
                                <i class="fas fa-hand-point-right text-success fa-2x"></i>
                            </button>
                        </form>

                    @else
                        <i class="fas fa-hand-point-right text-secundary fa-2x" title='No Puede Postularse'></i>
                    @endif
                @else
                    <i class="fas fa-hand-point-right text-secundary fa-2x" title='No Puede Postularse'></i>
                @endif
            @endif
        </td>
        @if(session('role_slug') == 'postulantes')
        <div class="modal fade" id="mensaje-rechazo{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="mensaje-rechazo" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="mensaje-rechazo">{{ $mensaje_header }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ $mensaje_body }}<br />
                        @if($mensaje_header !== '' && strstr($mensaje_header, 'Matriz'))
                            <b>{{ @$documento }}</b>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </tr>
@endforeach

