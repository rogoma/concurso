@foreach ($datos->examenes as $idx => $d)
    {{-- <pre>{{ $key.' => '. $d->cantidad_vacanc }}</pre> --}}
    <tr>
        <td>{{$d->id}}</td>
        <td>{{$d->concurso->decripcion}}</td>
        <td>{{$d->hora_ini}} - {{$d->hora_fin}}</td>
        <td>{{$d->puntaje}}</td>
        <td>{{$d->cant_preg_examen}}</td>
        <td class="text-center py-0 align-middle">
            @if($d->preguntas)
                <a href="{{route('examen.show', $d->id)}}">
                    <i class="fas fa-eye text-success"></i></a>
            @else
                <form action="{{route("examen.questions")}}" class="d-inline" method="POST">
                    @csrf
                    <input type="hidden" name="examen_id" value="{{ $d->id }}">
                    <button type="button" title="Crear Preguntas" style="border: none; background: none;"><i class="fas fa-folder-plus text-info"></i></button>
                </form>
            @endif
        </td>
    </tr>
@endforeach

