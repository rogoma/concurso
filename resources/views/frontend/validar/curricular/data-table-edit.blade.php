@foreach ($datos->evaluacion as $d)
    @php
        $punt = number_format($d->puntaje, 2, '.', ',');
        $idx = array();
        $arr  = array();
        $docs = array();

        foreach ($datos->matriz as $key => $value) {
            array_push($idx, array(
                'idx' => $key,
                'id_item' => $value->item_eval_curric_id
            ));
        }

        $idxP = (array_search($d->itemevalcurric->id, array_column($idx, 'id_item')));
        $min = number_format($datos->matriz[$idxP]->puntaje_min, 2);
        $max = number_format($datos->matriz[$idxP]->puntaje_max, 2);

        switch ($d->itemevalcurric->tipo) {
            case 'B':
                $punt = $datos->postulante->eval_desemp;
                $docus = null;
                $docusP = null;
                break;
            case 'N':
                $docus = null;
                $docusP = null;
                break;
            case 'A':
                $docus = $datos->postulacion->postDatosAcads;
                $docusP = null;
                break;
            case 'L':
                $docus = $datos->postulacion->postExpLaborales;
                $docusP = array();
                foreach ($datos->postulacion->postDatosPersonales as $dp) {
                    if ($dp->tipo_doc_id == 7 || $dp->tipo_doc_id == 8) {
                        array_push($docusP, array('id' => $dp->id, 'pdf' => $dp->pdf));
                    }
                }
                if (count($docusP) < 1) {
                    $docusP = null;
                }
                break;
            case 'C':
                $docus = $datos->postulacion->postCapacitaciones;
                $docusP = null;
                break;
            default:
                $arr = null;
                $docus = null;
                $docusP = null;
                break;
        }

        if ($docus !== null) {
            if (count($docus) > 1) {
                foreach ($docus as $p) {
                    if($p->pdf) {
                        $file = $p->pdf;
                    } else {
                        $file = $p->constancia;
                    }
                    array_push($docs, array(
                        'id' => $p->id,
                        'pdf' => strval($file)
                    ));
                }
            } elseif (count($docus) == 1) {
                if($docus[0]->pdf) {
                    $file = $docus[0]->pdf;
                } else {
                    $file = $docus[0]->constancia;
                }
                array_push($docs, array(
                    'id' => $docus[0]->id,
                    'pdf' => strval($file)
                ));
            } else {
                $docs = null;
            }
        } else {
            $docs = null;
        }

        if($docusP !== null) {
            if (count($docusP) > 1) {
                foreach ($docusP as $pe) {
                    $file = $pe['pdf'];
                    array_push($docs, array(
                        'id' => $pe['id'].'P',
                        'pdf' => strval($file)
                    ));
                }
            } elseif (count($docusP) == 1) {
                $file = $docusP[0]['pdf'];
                array_push($docs, array(
                    'id' => $docusP[0]['id'].'P',
                    'pdf' => strval($file)
                ));
            }
        }
        $col = 1;
    @endphp
    <tr>
        <input type="hidden" name="id[]" value="{{$d->id}}">
        <td>{{$d->id}}</td>
        <input type="hidden" name="item_eval_curric_id[]" value="{{$d->itemevalcurric->id}}">
        <td>{{$d->itemevalcurric->item}}</td>
        <td class="text-center">
            {{ $min }} - {{ $max }}
        </td>

        <td>
            @if ($docs !== null)
                @foreach ($docs as $doc)
                    @if($col > 5) <br>
                        @php
                            $col = 1;
                        @endphp
                    @endif
                        @if($doc['pdf'] !== null)
                            <a href="{{ url($doc['pdf']) }}" target="_blank">{{ $doc['id'] }}</a>&nbsp;&nbsp;
                        @endif
                    @php
                        $col++;
                    @endphp
                @endforeach
            @endif
        </td>
        <td>
            <input type="number" style="text-align:right; width:80px;" name="puntaje[]" value="{{ $punt }}" min="{{ $min }}" max="{{ $max }}" step="0.01" required>
        </td>
        <td>
            @php
                if ($d->obs !== null) {
                    $obs = strval($d->obs);
                } else {
                    $obs = '';
                }
            @endphp
            <textarea name="obs[]" cols="20" rows="2">{{ $obs }}</textarea>
        </td>
    </tr>
@endforeach
