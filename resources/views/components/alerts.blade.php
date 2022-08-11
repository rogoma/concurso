@php
    if ($tipo == 'success') {
        $icono = 'fa-check';
    } elseif ($tipo == 'warning') {
        $icono = 'fa-exclamation-triangle';
    } elseif ($tipo == 'info') {
        $icono = 'fa-info';
    } elseif ($tipo == 'danger') {
        $icono = 'fa-ban';
    }
@endphp
<div class="alert alert-{{$tipo}}" alert-dismissible role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" aria-hidden="true">&times;</button>
    <h4 class="alert-heading"><i class="icon fas {{$icono}}"></i> Concursos MSPyBS</h4>
    <p>
        @if(is_object($mensaje))
            <ul>
                @foreach($mensaje->all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
        @else
            {{$mensaje}}
        @endif
    </p>
</div>