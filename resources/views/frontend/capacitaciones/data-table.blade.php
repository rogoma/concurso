@foreach ($data as $d)
    <tr>
        <td>{{$d->id}}</td>
        <td>{{$d->tema_curso}}</td>
        <td>{{$d->carga_horaria}}</td>
        <td>{{date('d/m/Y', strtotime($d->fecha_ini))}} - {{date('d/m/Y', strtotime($d->fecha_fin))}}</td>
        <td>{{$d->institucion}}</td>
        <td class="text-center align-middle py-0">
            @if($d->constancia != null)
            <a href="{{asset($d->constancia)}}" target="_blank">
                <i class="fas fa-file-pdf text-primary"></i></a>
            @else
            <i class="fas fa-file-pdf text-secundary"></i>
            @endif
            <a href="{{route('capacitacion.edit', $d->id)}}">
                <i class="fas fa-edit text-info"></i></a>
            <form action="{{route("capacitacion.destroy", $d->id)}}" class="form-eliminar d-inline" method="POST">
                @csrf @method('delete')
                <button type="button" class="btn-accion-tabla boton-eliminar" data-toggle="modal" title="Eliminar Dato Académico" data-target="#modal-delrol" style="color: red; border: none; background: none;"><i class="fas fa-trash"></i></button>
            </form>
        </td>
    </tr>
@endforeach
