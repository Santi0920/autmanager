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
