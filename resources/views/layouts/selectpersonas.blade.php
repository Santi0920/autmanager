@foreach ($cargos as $cargo)
@php
    if($cargo->rol == "Consultante"){
        $codigo = $cargo->NumAgencia;
    }else{
        $codigo = $cargo->codigo;
    }
@endphp
<option value="{{ $cargo->id }}">{{ $cargo->name . " - " . $cargo->agenciau . " - " . $codigo}}</option>
@endforeach
