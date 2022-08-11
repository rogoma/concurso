@foreach ($data as $d)
    <tr>
        <td>{{$d->id}}</td>
        <td>{{ $d->tipoDoc->documento }}</td>
        <td>{{$d->rechazo}}</td>
        <td class="text-center py-0 align-middle">
            <a href="{{route('rechazodoc.edit', $d->id)}}">
                <i class="fas fa-edit text-info"></i></a>
            <form action="{{route("rechazodoc.destroy", $d->id)}}" class="form-eliminar d-inline" method="POST">
                @csrf @method('delete')
                <button type="button" class="btn-accion-tabla boton-eliminar" data-toggle="modal" title="Eliminar Rechazo" data-target="#modal-delrechazo" style="color: red; border: none; background: none;"><i class="fas fa-trash"></i></button>
            </form>
        </td>
    </tr>
@endforeach
{{--  --}}
