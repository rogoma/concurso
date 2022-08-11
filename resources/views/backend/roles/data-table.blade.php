@foreach ($data as $d)
    <tr>
        <td>{{$d->id}}</td>
        <td>{{$d->rol}}</td>
    </tr>
@endforeach


{{--

    <td class="text-center py-0 align-middle">
            <form action="" class="form-eliminar-rol d-inline">
                @if ($d->activo == 1)
                <i class="fas fa-check-circle text-success"></i>
                @else
                <i class="fas fa-check-circle text-danger"></i>
                @endif
            </form>
            @if ($d->id == 1)
            <i class="fas fa-edit text-secundary"></i>
            <i class="fas fa-trash text-secundary"></i>
            @else
            <a href="{{route('roles.edit', $d->id)}}">
                <i class="fas fa-edit text-info"></i></a>
            <form action="{{route("roles.destroy", $d->id)}}" class="form-eliminar d-inline" method="POST">
                @csrf @method('delete')
                <button type="button" class="btn-accion-tabla boton-eliminar" data-toggle="modal" title="Eliminar Rol" data-target="#modal-delrol" style="color: red; border: none; background: none;"><i class="fas fa-trash"></i></button>
            </form>
            @endif
        </td>



    --}}
