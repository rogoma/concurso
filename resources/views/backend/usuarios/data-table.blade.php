@foreach ($data->usuarios as $d)
    <tr>
        <td>{{$d->id}}</td>
        <td>{{$d->ci}}</td>
        <td>{{$d->email}}</td>
        <td>{{date('d/m/Y', strtotime($d->fecha_crea))}}</td>
        <td>
            @foreach ($d->rol as $r)
                {{$r->rol}}
            @endforeach
        </td>
        <td class="text-center py-0 align-middle">
            <form action="" class="form-eliminar-rol d-inline">
                @if ($d->activo == 1)
                <i class="fas fa-check-circle text-success"></i>
                @else
                <i class="fas fa-check-circle text-danger"></i>
                @endif
            </form>
            @if ($d->idusuario == 1)
            <i class="fas fa-edit text-secundary"></i>
            <i class="fas fa-trash text-secundary"></i>
            @else
            <a href="{{route('usuarios.edit', $d->id)}}">
                <i class="fas fa-edit text-info"></i></a>
            <form action="{{route("usuarios.destroy", $d->id)}}" class="form-eliminar d-inline" method="POST">
                @csrf @method('delete')
                <button type="button" class="btn-accion-tabla boton-eliminar" data-toggle="modal" title="Eliminar Rol" data-target="#modal-delrol" style="color: red; border: none; background: none;"><i class="fas fa-trash"></i></button>
            </form>
            @endif
        </td>
    </tr>
@endforeach
