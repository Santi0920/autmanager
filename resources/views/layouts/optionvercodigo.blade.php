
<div class="tooltip-container" >
    <span class="text">
        <svg class="w-10 h-10 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
            <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
        </svg>
    </span>
    <span class="tooltip">
        <div class="container text-justify">
            <div class="row align-items-start">
                <div class="col"  style="font-size: 18px; overflow-y: auto; max-height: 140px;">
                    <span class="text-light">⬇--- TALENTO HUMANO ---⬇</span><br>
                    @foreach ($user as $autorizacion)
                        @if ($autorizacion->No == 10)
                            <span class="text-dark">{{ $autorizacion->Concepto }}</span><br>
                        @endif
                    @endforeach

                    <span class="text-light">⬇--- COORDINACIÓN ---⬇</span><br>
                    @foreach ($user as $autorizacion)
                        @if ($autorizacion->No == 11)
                            <span class="text-dark">{{ $autorizacion->Concepto }}</span><br>
                        @endif
                    @endforeach

                    <span class="text-light">⬇--- SEGUROS COOPSERP ---⬇</span><br>
                    @foreach ($user as $autorizacion)
                        @if ($autorizacion->No == 23)
                            <span class="text-dark">{{ $autorizacion->Concepto }}</span><br>
                        @endif
                    @endforeach

                    <span class="text-light">⬇--- SISTEMAS ---⬇</span><br>
                    @foreach ($user as $autorizacion)
                        @if ($autorizacion->No == 19)
                            <span class="text-dark">{{ $autorizacion->Concepto }}</span><br>
                        @endif
                    @endforeach

                    <span class="text-light">⬇--- JURIDICO ZONA CENTRO ---⬇</span><br>
                    @foreach ($user as $autorizacion)
                        @if ($autorizacion->No == 2150)
                            <span class="text-dark">{{ $autorizacion->Concepto }}</span><br>
                        @endif
                    @endforeach

                    <span class="text-light">⬇--- JURIDICO ZONA NORTE ---⬇</span><br>
                    @foreach ($user as $autorizacion)
                        @if ($autorizacion->No == 2250)
                            <span class="text-dark">{{ $autorizacion->Concepto }}</span><br>
                        @endif
                    @endforeach

                    <span class="text-light">⬇--- JURIDICO ZONA SUR ---⬇</span><br>
                    @foreach ($user as $autorizacion)
                        @if ($autorizacion->No == 2350)
                            <span class="text-dark">{{ $autorizacion->Concepto }}</span><br>
                        @endif
                    @endforeach

                    <span class="text-light">⬇--- TESORERIA ---⬇</span><br>
                    @foreach ($user as $autorizacion)
                        @if ($autorizacion->No == 15)
                            <span class="text-dark">{{ $autorizacion->Concepto }}</span><br>
                        @endif
                    @endforeach

                    <span class="text-light">⬇--- MERIDIAN ---⬇</span><br>
                    @foreach ($user as $autorizacion)
                        @if ($autorizacion->No == 24)
                            <span class="text-dark">{{ $autorizacion->Concepto }}</span><br>
                        @endif
                    @endforeach

                    <span class="text-light">⬇--- FUNDACIÓN ---⬇</span><br>
                    @foreach ($user as $autorizacion)
                        @if ($autorizacion->No == 14)
                            <span class="text-dark">{{ $autorizacion->Concepto }}</span><br>
                        @endif
                    @endforeach

                    <span class="text-light">⬇--- CONSEJO ---⬇</span><br>
                    @foreach ($user as $autorizacion)
                        @if ($autorizacion->No == 21)
                            <span class="text-dark">{{ $autorizacion->Concepto }}</span><br>
                        @endif
                    @endforeach
                    </div>
            </div>
        </div>
    </span>
</div>
</span>
