@foreach ($datos->concursos as $d)
    <tr>
        <td>{{$d->decripcion}}</td>
        <td>{{@$d->cargos->descripcion}}</td>
        <td>{{$d->cantidad_vacanc}}</td>
        <td>{{number_format($d->salario, 0, ',', '.')}}</td>
        <td>{{$d->ini_post}} - {{$d->fin_post}}</td>
        <td class="text-center py-0 align-middle">
            <a href="">
                <i class="fas fa-hand-point-right text-success"></i></a>
        </td>
    </tr>
@endforeach
