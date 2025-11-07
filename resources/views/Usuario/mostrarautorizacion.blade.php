@include('layouts/head')

<body class="antialiased">
    @include('layouts/nav')

    <div class="col-11 mt-5 mb-5" style="margin-left:3.5%">
        <div class="container">
            <div class="row justify-content-center align-items-center m-0">
               <div class="col-12 col-sm-10 col-md-9 p-0 shadow-lg border border-dark">
                  <div class="row m-0">

                     <div class="row g-0 text-center">
                        <div class="col-sm-none col-md-none col-lg-2 bg-primary-subtle">
                            <a class="" href="filtrar"><i class="mt-5 fa-solid fa-xmark fs-2 text-dark" title="REGRESAR"></i></a>
                        </div>
                        <div class="col-md-12 col-lg-10">
                           <div class="row g-0 text-center ">
                              <div class="col-md-7 col-lg-9 bg-primary-subtle d-flex align-items-center justify-content-center p-3">
                                 <span class="h2 fw-bold">SOLICITUD</span>
                              </div>
                              <div class="col-md-5 col-lg-3">
                                 <div class="row g-0 justify-content-center border p-2">
                                    <span class="h3 fw-bold mb-0 text-danger">No.{{ $id }}</span>
                                 </div>
                                 <div class="row g-0 align-items-center justify-content-center border p-2">
                                    @if ($autorizacion->EstadoAutorizacion == 0)
                                        <button class="btn btn-danger shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">R - RECHAZADO</button>
                                    @elseif ($autorizacion->EstadoAutorizacion == 1)
                                        <button class="btn btn-success shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">V - VALIDADO</button>
                                    @elseif ($autorizacion->EstadoAutorizacion == 2)
                                        <button class="btn btn-warning shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">T - EN TRAMITE</button>
                                    @elseif ($autorizacion->EstadoAutorizacion == 3)
                                        <button class="btn btn-primary shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">C - CORREGIR</button>
                                    @elseif ($autorizacion->EstadoAutorizacion == 4)
                                        <button class="btn btn-success  shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">AP - APROBADO</button>
                                    @elseif ($autorizacion->EstadoAutorizacion == 5)
                                        <button class="btn btn-danger shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">R - RECHAZADO POR GERENCIA</button>
                                    @elseif ($autorizacion->EstadoAutorizacion == 6)
                                    <button class="btn btn-warning shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">T - EN TRAMITE</button>
                                    @elseif ($autorizacion->EstadoAutorizacion == 7)
                                    <button class="btn btn-info shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">AN - ANULADO</button>
                                    @endif
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>

                     <div class="row g-0 text-center">
                        <div class="col-sm-12 col-md-12 col-lg-2 d-flex align-items-center justify-content-center rounded-0 bg-warning-subtle border p-3 border border-dark">
                            <span class="h1 fw-bold mb-0">S<br><span class="fs-5 fw-normal">SOLICITUD<span></span>
                        </div>

                        <div class="col-sm-12 col-md-12 col-lg-10">
                           <div class="row g-0 justify-content-start">
                              <div class="row g-0 row-cols-2 justify-content-center">
                                 <div class="col-md-9 d-flex align-items-center justify-content-start border p-2">
                                    <span class="fs-5">{!! $autorizacion->NumAgencia . ' - ' . $autorizacion->NomAgencia . ' - <b>' . $autorizacion->SolicitadoPor . '</b>' !!}</span>
                                 </div>
                                 <div class="col-md-3 d-flex align-items-center justify-content-center border p-2">
                                    <span class="mb-0 fs-5">{{ $autorizacion->Fecha }}</span>
                                 </div>
                              </div>
                           </div>
                           <div class="row g-0 row-cols-2 d-flex justify-content-start">
                              <div class="col-sm-6 col-md-9 col-lg-9 d-flex align-items-center justify-content-start border p-2">
                                 <span class="fs-5">{{ $autorizacion->Concepto }}</span>
                              </div>
                              <div class="col-sm-6 col-md-3 col-lg-3 d-flex align-items-center justify-content-center border p-3">

                                @if ($autorizacion->CodigoAutorizacion == "11K")
                                    <span class="fs-5 fw-bold mb-0">{{ $autorizacion->Convencion }}</span>
                                @endif
                              </div>
                           </div>
                           <div class="row g-0">
                              <div class="col-md-12 d-flex justify-content-start border p-2 fs-5">
                                 <span>{{ $autorizacion->CedulaAutorizacion }} -
                                    @if ($autorizacion->CuentaAsociado == null)
                                        N/A
                                    @else
                                        {{$autorizacion->CuentaAsociado}}
                                    @endif
                                    - {{$autorizacion->NombrePersona}}
                                    @if (in_array($autorizacion->CodigoAutorizacion, ['11A', '11D', '11L']))
                                        @if ($autorizacion->Score == 'N/A')
                                            - <span class="badge badge-pill badge-danger bg-warning text-light fw-bold fs-5">{{ $autorizacion->Score }}</span> - {!! $estado !!}
                                        @elseif ($autorizacion->Score == 'S/E')
                                            - <span class="badge badge-pill badge-danger bg-warning text-dark fw-bold fs-5">{{ $autorizacion->Score }}</span> - {!! $estado !!}
                                        @elseif ($autorizacion->Score >= 650)
                                            - <span class="badge badge-pill badge-danger bg-success text-light fw-bold fs-5">{{ $autorizacion->Score }}</span> - {!! $estado !!}
                                        @elseif ($autorizacion->Score < 650)
                                        - <span class="badge badge-pill badge-danger bg-danger text-light fw-bold fs-5">{{ $autorizacion->Score }}</span> - {!! $estado !!}
                                        @endif
                                    @endif
                                </span>
                              </div>
                           </div>
                           <div class="row g-0">
                                <div class="col-sm-12 col-md-9 text-start border p-2 fs-5" style="max-width: 100%; overflow: auto;">
                                    {{$autorizacion->Detalle}}
                                </div>
                            <a href="Storage/files/soporteautorizaciones/{{$autorizacion->DocumentoSoporte}}" class="col-sm-12 col-md-3 d-flex align-items-center justify-content-center btn btn-outline-info rounded-0 p-3" target="__blank">
                                <span class="h1 fw-bold mb-0">
                                    <img src="img/pdf.png" style="height: 4.5rem">
                                </span>
                            </a>
                           </div>
                        </div>
                     </div>
                     {{-- estado 6 es remitido a gerencia pero SOLO APLICA PARA COORDINADORES --}}
                    @if(($autorizacion->NumAgencia != 'C1' && $autorizacion->NumAgencia != 'C2' && $autorizacion->NumAgencia != 'C3' && $autorizacion->NumAgencia != 'C4' && $autorizacion->NumAgencia != 'C5') && ($autorizacion->Validacion == 1 || $autorizacion->EstadoAutorizacion == 0 || $autorizacion->EstadoAutorizacion == 5))
                        <div class="row g-0 text-center">
                            @if ($autorizacion->Validacion == 0 && $autorizacion->EstadoAutorizacion == 0)
                                <div class="col-sm-12 col-md-12 col-lg-2 d-flex align-items-center justify-content-center bg-danger-subtle border p-3 border border-dark">
                                    <span class="h1 fw-bold mb-0">R<br><span class="fs-5 fw-normal">RECHAZADO<span>
                                </div>
                            @elseif ($autorizacion->Validacion == 1 || $autorizacion->EstadoAutorizacion == 5)
                                <div class="col-sm-12 col-md-12 col-lg-2 d-flex align-items-center justify-content-center bg-success-subtle border p-3 border border-dark">
                                    <span class="h1 fw-bold mb-0">V<br><span class="fs-5 fw-normal">VALIDADO<span>
                                </div>
                            @endif
                                <div class="col-sm-12 col-md-12 col-lg-10">
                                    <div class="row g-0">
                                        <div class="text-start col-md-9 d-flex align-items-center border p-2">
                                            <span class="fs-5 mb-0">{{$autorizacion->Coordinacion}} - <b>{{$autorizacion->ValidadoPor}}</b></span>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-center justify-content-center border p-3">
                                            <span class="mb-0 fs-5">{{$autorizacion->FechaValidacion}}</span>
                                        </div>
                                        <div class="text-start col-md-9 d-flex align-items-center border p-2 w-100">
                                            <span class="fs-5 fw-bold mb-0">
                                                @if($autorizacion->Observaciones !== null)
                                                    {{$autorizacion->Observaciones}}
                                                @else
                                                    Ninguna.
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    @else
                    @endif

                    @if($autorizacion->Aprobacion ==  1 || $autorizacion->EstadoAutorizacion == 5 || $autorizacion->Aprobacion == 0)
                        <div class="row g-0 text-center">
                            @if ($autorizacion->EstadoAutorizacion == 4)
                                <div class="col-sm-6 col-md-12 col-lg-2 d-flex align-items-center justify-content-center bg-success-subtle border p-3 border border-dark">
                                    <span class="h1 fw-bold mb-0">A<br><span class="fs-5 fw-normal">APROBADO<span></span>
                                </div>
                            @elseif ($autorizacion->EstadoAutorizacion == 5)
                                <div class="col-sm-6 col-md-12 col-lg-2 d-flex align-items-center justify-content-center bg-danger-subtle border p-3 border border-dark">
                                    <span class="h1 fw-bold mb-0">R<br><span class="fs-5 fw-normal">RECHAZADO<span></span>
                                </div>
                            @elseif ($autorizacion->EstadoAutorizacion == 7)
                                <div class="col-sm-6 col-md-12 col-lg-2 d-flex align-items-center justify-content-center bg-info-subtle border p-3 border border-dark">
                                    <span class="h1 fw-bold mb-0">AN<br><span class="fs-5 fw-normal">ANULADO<span></span>
                            </div>
                            @endif

                            @if ($autorizacion->EstadoAutorizacion != 0)
                            <div class="col-md-12 col-lg-10">
                                <div class="row g-0">
                                    <div class="col-md-9 d-flex text-start border p-2">
                                        <span class="h5 fw-bold mb-0">DIRECCION GENERAL</span>
                                    </div>
                                    <div class="col-md-3 border p-2">
                                        <span class="mb-0 fs-5">{{$autorizacion->FechaAprobacion}}</span>
                                    </div>
                                </div>
                                <div class="row g-0 border text-start p-2">
                                    <p class="mb-0 fw-semibold fs-5">
                                        @if($autorizacion->ObservacionesGer == null)
                                            Ninguna.
                                        @else
                                            {{$autorizacion->ObservacionesGer}}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @else
                            @endif
                        </div>
                    @else
                    @endif

                  </div>
               </div>
            </div>
         </div>
    </div>

    @if (session('rol') == 'Gerencia')
    @else
        @include('layouts.notification')
    @endif
    @include('layouts.celular')
    @include('layouts.footer')
    @include('layouts.retornar')



    <script>
        function obtenerFechaActual() {
            const fecha = new Date();
            const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
            const mes = meses[fecha.getMonth()];
            const dia = fecha.getDate();
            const anio = fecha.getFullYear();
            let horas = fecha.getHours();
            let amPm = 'AM';

            // AM/PM
            if (horas > 12) {
                horas -= 12;
                amPm = 'PM';
            } else if (horas === 0) {
                horas = 12;
            }

            const minutos = fecha.getMinutes();
            const segundos = fecha.getSeconds();


            return `${mes} ${dia}, ${anio} - ${horas}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')} ${amPm}`;
        }


        function actualizarFechaActual() {
            const elementoFecha = document.getElementById('fechaActual');
            elementoFecha.textContent = `${obtenerFechaActual()}`;
        }


        setInterval(actualizarFechaActual, 1000);


        function agregarNumero(numero) {
            let inputNumero = document.getElementById('numero');
            if (inputNumero.value.length < 2) {
                inputNumero.value += numero;
            }
        }

        function borrar() {
            // Borra el último dígito del campo de entrada
            var inputNumero = document.getElementById('numero');
            var valor = inputNumero.value;
            if (valor.length > 0) {
                inputNumero.value = valor.slice(0, -1);
            }
        }

        function csesion() {
                var respuesta = confirm("¿Estas seguro que deseas cerrar sesión?")
                return respuesta
            }
    </script>

    <style>
        .boton-numero {
        background-color: #646464;
        color: rgb(255, 255, 255);
        font-size: 40px;
        width: 100px;
        transition: background-color 0.3s ease;
        }

        .boton-numero:hover {
        background-color: #7a7a7a; /* Cambia a un color más claro en hover */
        }

        .boton-buscar {
        background-color: #646464;
        color: white;
        font-size: 40px;
        transition: background-color 0.3s ease;
        }

        .boton-buscar:hover {
        background-color: #7a7a7a; /* Cambia a un color más claro en hover */
        }
    </style>

</body>

</html>
