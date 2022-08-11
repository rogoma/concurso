@foreach ($datos->postulacion as $d)
    <tr>
        <td>{{$d->id}}</td>
        <td>{{$d->usuario->ci}}</td>
        <td>{{ $d->usuario->perfil->apellidos }}, {{ $d->usuario->perfil->nombres }}</td>
        <td>{{date('d/m/Y H:i:s', strtotime($d->fecha_post))}}</td>
        <td>{{ $d->eval_curric }}</td>
        <td>{{$d->eval_curric_user}}</td>
        <td>
            @if($d->eval_curric_fecha !== null)
                {{date('d/m/Y H:i:s', strtotime($d->eval_curric_fecha))}}
            @endif
            </td>
        <td class="text-center">
            @if($d->eval_curric_fecha !== null)
                <form action="{{route('curricular.edit')}}" method="POST">
            @else
                <form action="{{ route('curricular.matriz') }}" method="POST">
            @endif
                    @csrf
                    <input type="hidden" name="concurso_id" value="{{ $datos->concurso->id }}">
                    <input type="hidden" name="estado" value="{{ $datos->estado }}">
                    <input type="hidden" name="postulacion_id" value="{{$d->id}}">
                    @if ($datos->fechas !== null)
                    <input  type="hidden" name="fechas" value="{{ $datos->fechas }}">
                    @endif
                    <button type="submit" style="border: none; background: none;">
                        <i class="fas fa-table text-cyan fa-2x"></i>
                    </button>
                </form>
        </td>
    </tr>
@endforeach
