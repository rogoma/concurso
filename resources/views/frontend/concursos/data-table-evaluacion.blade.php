@foreach ($datos->concursos as $idx => $d)
    @php
        if (!empty($datos->idPostulaciones)) {
            $postulacion = in_array($d->id, array_column($datos->idPostulaciones, 'idC'));
            $idxPost = array_search($d->id, array_column($datos->idPostulaciones, 'idC'));
            $idxPost = $datos->idPostulaciones[$idxPost]['idx'];
        }
        $fecha_actual = strtotime(date("Y-m-d"));
        $fecha_ini_examen = strtotime($d->ini_examen);
        $fecha_fin_examen = strtotime($d->fin_examen);
    @endphp
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
                    <i class="fas fa-eye-slash text-secundary fa-2x" title='No Postulado'></i>
            @else
                @if(@$postulacion == true)
                    <a href="{{ route('postulacion.show',$d->id) }}">
                        <i class="fas fa-eye text-warning fa-2x"></i></a>
                @else
                    <i class="fas fa-eye-slash text-secundary fa-2x" title='No Postulado'></i>
                @endif
            @endif
        </td>
        <td class="text-center py-0 align-middle">
        @if($fecha_actual < $fecha_ini_examen)
            {{date('d/m/Y', strtotime($d->ini_examen))}} - {{date('d/m/Y', strtotime($d->fin_examen))}}
        @elseif($fecha_actual >= $fecha_ini_examen && $fecha_actual <= $fecha_fin_examen)
            @if($postulacion == true)
                @if($datos->postulaciones[$idxPost]->examen_user == session('ci'))
                    <i class="fas fa-book-reader text-secundary fa-2x" title='Examen Realizado'></i>
                @elseif($datos->postulaciones[$idxPost]->act_intento_examen < $datos->configuracion->max_intento_examen)
                    <form action="{{route('examen.rendir')}}"
                        method="POST"
                        class="form-rendir d-inline">
                        @csrf
                        <input type="hidden" name="concurso_id" value="{{ $d->id }}">
                        <input type="hidden" name="postulacion_id" value="{{ $datos->postulaciones[$idxPost]->id }}">
                        {{ $datos->postulaciones[$idxPost]->act_intento_examen }} /
                        <button type="submit" class="btn-accion-tabla boton-rendir" title="Examinar Concurso" data-toggle="modal" data-target="#confirmar-rendir" style="border: none; background: none;">
                            <i class="fas fa-book-reader text-success fa-2x"></i>
                        </button>
                    </form>
                @else
                    <i class="fas fa-ban text-secundary fa-2x" title='Intentos Excedidos'></i>
                @endif
            @else
                <i class="fas fa-ban text-secundary fa-2x" title='No Postulado'></i>
            @endif
        @else
            <i class="fas fa-ban text-danger fa-2x" title='No se puede examinar'></i>
        @endif
        </td>
    </tr>
@endforeach

