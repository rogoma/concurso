@foreach ($datos->preguntas['preguntas'] as $d)
    <tr>
        <td>{{$d['pregunta_id']}}</td>
        <td>{{$d['pregunta']}}</td>
        <td>{{$d['puntaje']}}</td>
        <td>
            @foreach($d['respuestas'] as $r)
                @php
                    $correcto = ($r['correcto'] == "true") ? "correcta" : "incorrecta";
                @endphp
                {{ $r['respuesta_id'] }}: {{ $r['respuesta'] }} - {{ $correcto }}<br />
            @endforeach
        </td>
        <td class="text-center py-0 align-middle">
            <form action="{{route("examen.destroyq", $d['pregunta_id'])}}" class="form-eliminar d-inline" method="POST">
                @csrf @method('delete')
                <button type="button" class="btn-accion-tabla boton-eliminar" data-toggle="modal" title="Eliminar Concurso" data-target="#modal-delconcurso" style="color: red; border: none; background: none;"><i class="fas fa-trash fa-2x"></i></button>
            </form>
        </td>
    </tr>
@endforeach

