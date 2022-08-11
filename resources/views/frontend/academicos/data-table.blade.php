@foreach ($data as $d)
    <tr>
        <td>{{$d->id}}</td>
        <td>{{$d->tipoDoc->documento}}</td>
        <td>{{date('d/m/Y', strtotime($d->fecha_graduac))}}</td>
        <td>{{$d->institucion}}</td>
        <td>{{$d->dato_adic}}</td>
        <td class="text-center py-0 align-middle">
            @if($d->pdf != null)
            <a href="{{asset($d->pdf)}}" target="_blank">
                <i class="fas fa-file-pdf text-primary"></i></a>
            @else
            <i class="fas fa-file-pdf text-secundary"></i>
            @endif
            <a href="{{route('academicos.edit', $d->id)}}">
                <i class="fas fa-edit text-info"></i></a>
            <form action="{{route("academicos.destroy", $d->id)}}" class="form-eliminar d-inline" method="POST">
                @csrf @method('delete')
                <button type="button" class="btn-accion-tabla boton-eliminar" data-toggle="modal" title="Eliminar Dato Académico" data-target="#modal-delrol" style="color: red; border: none; background: none;"><i class="fas fa-trash"></i></button>
            </form>
        </td>
    </tr>
@endforeach
