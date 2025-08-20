<div class="mb-3 w-100" title="Este campo es obligatorio" id="id">
    <label for="input1" class="form-label col-form-label-lg fw-semibold">
        TIPO DE AUTORIZACIÓN <span class="text-danger" style="font-size:20px;">*</span>
    </label>
    <select class="form-select form-select-lg" name="tautorizacion" id="autorizaciones" required>
        <option selected disabled>Selecciona una opción</option>

        @foreach ($grupos as $no => $items)
            @php
                $area = isset($items[0]->Areas) ? strtoupper($items[0]->Areas) : 'GLOBAL';
            @endphp

            <option disabled class="fw-bold">
                --------{{ $area }}--------
            </option>

            @foreach ($items as $autorizacion)
                <option class="fw-semibold" value="{{ $autorizacion->ID }}">
                    {{ $autorizacion->Concepto }}
                </option>
            @endforeach
        @endforeach
    </select>
</div>
