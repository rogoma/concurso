@foreach ($datos->concursos as $idx => $d)
    {{-- <pre>{{ $key.' => '. $d->cantidad_vacanc }}</pre> --}}
    <tr>
        <td>{{$d->id}}</td>
        <td>{{$d->decripcion}}</td>
        <td>{{@$d->cargos->descripcion}}</td>
        <td>{{date('d/m/Y', strtotime($d->ini_post))}} - {{date('d/m/Y', strtotime($d->fin_post))}}</td>
        <td class="text-center py-0 align-middle">
            <a href="{{route('concourse.edit', $d->id)}}">
                <i class="fas fa-edit text-info"></i></a>
            <form action="{{route("concourse.destroy", $d->id)}}" class="form-eliminar d-inline" method="POST">
                @csrf @method('delete')
                <button type="button" class="btn-accion-tabla boton-eliminar" data-toggle="modal" title="Eliminar Concurso" data-target="#modal-delconcurso" style="color: red; border: none; background: none;"><i class="fas fa-trash"></i></button>
            </form>
        </td>
    </tr>
@endforeach

