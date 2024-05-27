<option disabled class="fw-bold">--------GLOBAL--------</option>
@foreach ($user as $autorizacion)
    @if ($autorizacion->No == 0)
        <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
            {{ $autorizacion->Concepto }}
        </option>
    @endif
@endforeach
<option disabled class="fw-bold">--------TALENTO HUMANO--------</option>
    @foreach ($user as $autorizacion)
        @if ($autorizacion->No == 10)
            <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                {{ $autorizacion->Concepto }}
            </option>
        @endif
    @endforeach
<option disabled class="fw-bold">--------COORDINACION--------</option>
@foreach ($user as $autorizacion)
    @if ($autorizacion->No == 11)
        <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
            {{ $autorizacion->Concepto }}
        </option>
    @endif
@endforeach

<option disabled class="fw-bold">--------SEGUROS COOPSERP--------</option>
@foreach ($user as $autorizacion)
    @if ($autorizacion->No == 23)
        <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
            {{ $autorizacion->Concepto }}
        </option>
    @endif
@endforeach


<option disabled class="fw-bold">--------SISTEMAS--------</option>
@foreach ($user as $autorizacion)
    @if ($autorizacion->No == 19)
        <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
            {{ $autorizacion->Concepto }}
        </option>
    @endif
@endforeach
{{-- <option disabled class="fw-bold">--------JURIDICO ZONA CENTRO--------</option>
    @foreach ($user as $autorizacion)
        @if ($autorizacion->No == 2150)
            <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                {{ $autorizacion->Concepto }}
            </option>
        @endif
    @endforeach
    <option disabled class="fw-bold">--------JURIDICO ZONA NORTE--------</option>
    @foreach ($user as $autorizacion)
        @if ($autorizacion->No == 2250)
            <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                {{ $autorizacion->Concepto }}
            </option>
        @endif
    @endforeach
    <option disabled class="fw-bold">--------JURIDICO ZONA SUR--------</option>
    @foreach ($user as $autorizacion)
        @if ($autorizacion->No == 2350)
            <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                {{ $autorizacion->Concepto }}
            </option>
        @endif
    @endforeach --}}
    <option disabled class="fw-bold">--------TESORERIA--------</option>
    @foreach ($user as $autorizacion)
        @if ($autorizacion->No == 15)
            <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                {{ $autorizacion->Concepto }}
            </option>
        @endif
    @endforeach

    <option disabled class="fw-bold">--------MERIDIAN--------</option>
    @foreach ($user as $autorizacion)
        @if ($autorizacion->No == 24)
            <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                {{ $autorizacion->Concepto }}
            </option>
        @endif
    @endforeach

    <option disabled class="fw-bold">--------FUNDACIÓN--------</option>
    @foreach ($user as $autorizacion)
        @if ($autorizacion->No == 14)
            <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                {{ $autorizacion->Concepto }}
            </option>
        @endif
    @endforeach

    <option disabled class="fw-bold">--------CONSEJO--------</option>
    @foreach ($user as $autorizacion)
        @if ($autorizacion->No == 21)
            <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                {{ $autorizacion->Concepto }}
            </option>
        @endif
    @endforeach
