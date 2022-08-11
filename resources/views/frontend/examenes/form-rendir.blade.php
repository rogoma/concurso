@foreach ($datos->preguntas as $p)
    <div class="border-top">
    <h5 class="card-title"></h5>
        <div class="form-group row">
            <input type="hidden" name="pregunta_id[]" value="{{ $p['pregunta_id'] }}" />
            <label class="col-md-4"><b>{{ $p['pregunta'] }}</b></label>
            <div class="col-md-8">
                @php
                    $s = "A";
                @endphp
                @foreach($p['respuestas'] as $r)
                {{ $s }})&nbsp;
                <div class="form-check d-inline">
                    <input
                        type="radio"
                        class="form-check-input"
                        name="resp_preg{{ $p['pregunta_id'] }}"
                        value="{{ $r['respuesta_id'] }}"
                        required
                    />
                    <label class="form-check-label mb-0 d-inline"
                        for="customControlValidation1">{{ $r['respuesta'] }}</label>
                </div>
                <br />
                @php
                    ++$s;
                @endphp
                @endforeach
            </div>
        </div>
    </div>
@endforeach
