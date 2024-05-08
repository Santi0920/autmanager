<div class="mb-3 w-100" title="Este campo es obligatorio" id="id">
    <label for="input1" class="form-label col-form-label-lg fw-semibold">TIPO DE AUTORIZACIÓN <span
            class="text-danger" style="font-size:20px;">*</span></label>
    <select class="form-select form-select-lg " name="tautorizacion" id="autorizaciones" required>
        <option selected disabled>Selecciona una opción</option>
        <option disabled class="fw-bold">---TALENTO HUMANO---</option>
            @foreach ($user as $autorizacion)
                @if ($autorizacion->No == 10)
                    <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                        {{ $autorizacion->No . $autorizacion->Letra }} -
                        {{ $autorizacion->Concepto }}
                    </option>
                @endif
            @endforeach
        <option disabled class="fw-bold">---COORDINACION---</option>
        @foreach ($user as $autorizacion)
            @if ($autorizacion->No == 11)
                <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                    {{ $autorizacion->No . $autorizacion->Letra }} -
                    {{ $autorizacion->Concepto }}
                </option>
            @endif
        @endforeach

        <option disabled class="fw-bold">---SEGUROS COOPSERP---</option>
        @foreach ($user as $autorizacion)
            @if ($autorizacion->No == 2300)
                <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                    {{ $autorizacion->No . $autorizacion->Letra }} -
                    {{ $autorizacion->Concepto }}
                </option>
            @endif
        @endforeach


        <option disabled class="fw-bold">---SISTEMAS---</option>
        @foreach ($user as $autorizacion)
            @if ($autorizacion->No == 19)
                <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                    {{ $autorizacion->No . $autorizacion->Letra }} -
                    {{ $autorizacion->Concepto }}
                </option>
            @endif
        @endforeach
        <option disabled class="fw-bold">---JURIDICO ZONA CENTRO---</option>
            @foreach ($user as $autorizacion)
                @if ($autorizacion->No == 2150)
                    <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                        {{ $autorizacion->No . $autorizacion->Letra }} -
                        {{ $autorizacion->Concepto }}
                    </option>
                @endif
            @endforeach
            <option disabled class="fw-bold">---JURIDICO ZONA NORTE---</option>
            @foreach ($user as $autorizacion)
                @if ($autorizacion->No == 2250)
                    <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                        {{ $autorizacion->No . $autorizacion->Letra }} -
                        {{ $autorizacion->Concepto }}
                    </option>
                @endif
            @endforeach
            <option disabled class="fw-bold">---JURIDICO ZONA SUR---</option>
            @foreach ($user as $autorizacion)
                @if ($autorizacion->No == 2350)
                    <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                        {{ $autorizacion->No . $autorizacion->Letra }} -
                        {{ $autorizacion->Concepto }}
                    </option>
                @endif
            @endforeach
            <option disabled class="fw-bold">---TESORERIA---</option>
            @foreach ($user as $autorizacion)
                @if ($autorizacion->No == 1500)
                    <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                        {{ $autorizacion->No . $autorizacion->Letra }} -
                        {{ $autorizacion->Concepto }}
                    </option>
                @endif
            @endforeach

            <option disabled class="fw-bold">---MERIDIAN---</option>
            @foreach ($user as $autorizacion)
                @if ($autorizacion->No == 2400)
                    <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                        {{ $autorizacion->No . $autorizacion->Letra }} -
                        {{ $autorizacion->Concepto }}
                    </option>
                @endif
            @endforeach

            <option disabled class="fw-bold">---FUNDACIÓN---</option>
            @foreach ($user as $autorizacion)
                @if ($autorizacion->No == 1400)
                    <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                        {{ $autorizacion->No . $autorizacion->Letra }} -
                        {{ $autorizacion->Concepto }}
                    </option>
                @endif
            @endforeach

            <option disabled class="fw-bold">---CONSEJO---</option>
            @foreach ($user as $autorizacion)
                @if ($autorizacion->No == 2100)
                    <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                        {{ $autorizacion->No . $autorizacion->Letra }} -
                        {{ $autorizacion->Concepto }}
                    </option>
                @endif
            @endforeach
    </select>
</div>
