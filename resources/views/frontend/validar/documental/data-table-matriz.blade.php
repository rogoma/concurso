@foreach ($datos->matriz as $d)
    <tr>
        <td>{{$d->id}}</td>
        <input type="hidden" name="tipo_doc_id[]" value="{{ $d->tipo_doc_id }}">
        <td>{{$d->tipoDoc->documento}}</td>
        <td class="text-center">
            @if ($d->requerido == true)
                <input type="hidden" name="requerido[]" value="1">
                <i class="far fa-check-circle text-success"></i>
            @else
                <input type="hidden" name="requerido[]" value="0">
                <i class="far fa-times-circle text-danger"></i>
            @endif
        </td>
        @php
            $rechazo = '';
            $arr = array();
            $arz = array();
            $docs = array();
            $drops = array();
            $idTd = $d->tipo_doc_id;
            $tipo = $d->tipoDoc->tipo;

            /*if ($d->requerido == true) {
                $rechazo = 'required';
            }*/

            switch ($tipo) {
                case 'P':
                    foreach ($datos->postulacion->postDatosPersonales as $p) {
                        $arr[] = $p->tipo_doc_id;
                    }
                    $docus = $datos->postulacion->postDatosPersonales;
                    break;
                case 'A':
                    foreach ($datos->postulacion->postDatosAcads as $p) {
                        $arr[] = $p->tipo_doc_id;
                    }
                    $docus = $datos->postulacion->postDatosAcads;
                    break;
                case 'L':
                    foreach ($datos->postulacion->postExpLaborales as $p) {
                        $arr[] = $p->tipo_doc_id;
                    }
                    $docus = $datos->postulacion->postExpLaborales;
                    break;
                case 'C':
                    foreach ($datos->postulacion->postCapacitaciones as $p) {
                        $arr[] = $p->tipo_doc_id;
                    }
                    $docus = $datos->postulacion->postCapacitaciones;
                    break;
                default:
                    $arr = null;
                    $docus = null;
                    break;
            }

            if (in_array($d->tipo_doc_id, $arr)) {
                $idx = array_keys($arr, $d->tipo_doc_id);
                if (count($idx) > 1) {
                    foreach ($idx as $i) {
                        if($docus[$i]->pdf) {
                            $file = $docus[$i]->pdf;
                        } else {
                            $file = $docus[$i]->constancia;
                        }
                        array_push($docs, array(
                            'id' => $docus[$i]->id,
                            'pdf' => strval($file)
                        ));
                    }
                } elseif (count($idx) == 1) {
                    //echo var_dump($docus[6]->pdf); exit;
                    if($docus[$idx[0]]->pdf) {
                        $file = $docus[$idx[0]]->pdf;
                    } else {
                        $file = $docus[$idx[0]]->constancia;
                    }
                    array_push($docs, array(
                        'id' => $docus[$idx[0]]->id,
                        'pdf' => strval($file)
                    ));
                } else {
                    $docs = null;
                }
            } else {
                $docs = null;
            }
            //Rechazados
            foreach ($datos->rechazos as $rz) {
                $arz[] = $rz->tipo_doc_id;
            }

            if (in_array($d->tipo_doc_id, $arz)) {
                $idr = array_keys($arz, $d->tipo_doc_id);
                //echo "<pre>".count($idr)."</pre>"; exit;
                if (count($idr) > 1) {
                    foreach ($idr as $i) {
                        array_push($drops, array(
                            'id' => $datos->rechazos[$i]->id,
                            'rechazo' => strval($datos->rechazos[$i]->rechazo)
                        ));
                    }
                } elseif (count($idr) == 1) {
                        array_push($drops, array(
                            'id' => $datos->rechazos[$idr[0]]->id,
                            'rechazo' => strval($datos->rechazos[$idr[0]]->rechazo)
                        ));
                } else {
                    $drops = null;
                }
            } else {
                $drops = null;
            }
        @endphp
        <td>
            @if ($docs !== null)
                @foreach ($docs as $doc)
                    @if($doc['pdf'] !== null)
                        <a href="{{ url($doc['pdf']) }}" target="_blank">{{ $doc['id'] }}</a><br />
                    @endif
                @endforeach
            @endif
        </td>
        <td class="text-center">
            <label>SI</label>
            <input type="radio" name="cumple{{ $d->tipo_doc_id }}" id="cumple{{ $d->tipo_doc_id }}" value="1" required onclick="remRequired({{ $d->tipo_doc_id }}); selSinMotivo({{ $d->tipo_doc_id }});"><br />
            <label>NO</label>
            <input type="radio" name="cumple{{ $d->tipo_doc_id }}" id="cumple{{ $d->tipo_doc_id }}" value="0" checked onclick="putRequired({{ $d->tipo_doc_id }})">
        </td>
        <td>
            <select style="width: 200px" name="motivo_rechazo_id[]" id="rechazo{{ $d->tipo_doc_id }}" required>
                    <option value="">Sin motivo</option>
                @if ($drops !== null)
                    @foreach ($drops as $drop)
                        <option value="{{ $drop['id'] }}">{{ $drop['rechazo'] }}</option>
                    @endforeach
                @endif
            </select>
        </td>
        <td>
            <textarea name="obs[]" id="obs{{ $d->tipo_doc_id }}" cols="8" rows="2"></textarea>
        </td>
    </tr>
@endforeach
