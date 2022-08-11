@foreach ($data as $d)
    <tr>
        <td>{{$d->id}}</td>
        <td>{{$d->descripcion}}</td>
        <td class="text-center py-0 align-middle">
            <a href="{{route('cargos.edit', $d->id)}}">
                <i class="fas fa-edit text-info"></i></a>
            <form action="{{route("cargos.destroy", $d->id)}}" class="form-eliminar d-inline" method="POST">
                @csrf @method('delete')
                <button type="button" class="btn-accion-tabla boton-eliminar" data-toggle="modal" title="Eliminar Cargo" data-target="#modal-delcargo" style="color: red; border: none; background: none;"><i class="fas fa-trash"></i></button>
            </form>
        </td>
    </tr>
@endforeach
