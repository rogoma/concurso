@foreach ($datos->matriz as $d)
    @php
        $punt = '';
        $arr  = array();
        $docs = array();
        $min = number_format($d->puntaje_min);
        $max = number_format($d->puntaje_max);

        switch ($d->itemevalcurric->tipo) {
            case 'B':
                if ($datos->postulante->eval_desemp == null) {
                    $punt = 0;
                } else {
                    $punt = number_format($datos->postulante->eval_desemp, 2, '.', ',');
                }
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
                foreach ($docus as $doc) {
                    if($doc->pdf) {
                        $file = $doc->pdf;
                    } else {
                        $file = $doc->constancia;
                    }
                    array_push($docs, array(
                        'id' => $doc->id,
                        'pdf' => strval($file)
                    ));
                }
            } elseif (count($docus) == 1) {
                //echo var_dump($docus[6]->pdf); exit;
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
        <td>{{$d->id}}</td>
        <input type="hidden" name="item_eval_curric_id[]" value="{{ $d->itemevalcurric->id }}">
        <td>{{$d->itemevalcurric->item}}</td>
        <td class="text-center">
            {{ $d->puntaje_min }} - {{ $d->puntaje_max }}
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
            <input type="number" style="text-align:right; width:80px" id="puntaje" name="puntaje[]" value="{{ $punt }}" min="{{ $min }}" max="{{ $max }}" step="0.01" required>
        </td>
        <td>
            <textarea name="obs[]" cols="20" rows="2"></textarea>
        </td>
    </tr>
@endforeach
