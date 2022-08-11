@foreach ($data as $d)
    <tr>
        <td>{{$d->id}}</td>
        <td>
            @if($d->tipo == 'P')
                Personal
            @elseif ($d->tipo == 'C')
                Capacitación
            @elseif ($d->tipo == 'L')
                Laboral
            @else
                Académico
            @endif
        </td>
        <td>{{$d->documento}}</td>
        <td class="text-center py-0 align-middle">
            <a href="{{route('tipodoc.edit', $d->id)}}">
                <i class="fas fa-edit text-info"></i></a>
            <form action="{{route("tipodoc.destroy", $d->id)}}" class="form-eliminar d-inline" method="POST">
                @csrf @method('delete')
                <button type="button" class="btn-accion-tabla boton-eliminar" data-toggle="modal" title="Eliminar Tipodoc" data-target="#modal-deltipodoc" style="color: red; border: none; background: none;"><i class="fas fa-trash"></i></button>
            </form>
        </td>
    </tr>
@endforeach
