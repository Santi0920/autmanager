@include('layouts/head')

<body class="antialiased">
    @include('layouts/nav')

    @if (session('correcto'))
        <div>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: "¡Correcto!",
                    html: "{!! session('correcto') !!}",
                    confirmButtonColor: '#646464'
                });
            </script>
        </div>
    @endif

    @if (session('incorrecto'))
        <div>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: "{{ session('incorrecto') }}",
                    text: '',
                    confirmButtonColor: '#646464',
                    timer: 10000

                });
            </script>
        </div>
    @endif

    @php
        if (session('rol') == 'Consultante'){
            $href = "solicitudes";
        }else if(session('rol') == 'Coordinacion'){
            $href = "validar";
        }else if(session('rol') == 'Gerencia'){
            $href = "aprobar";
        }else if(session('rol') == 'Jefatura'){
            $href = "solicitudesjefatura";
        }

    @endphp

    <div class="col-11" style="margin-left:3.5%">
        <div class="">
            <div class="" style="margin-top: 0px; margin-right: -14px;">

                {{-- <h2 class="p-2 mb-0 text-secondary text-start mt-2"><b><span class="text-warning" style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);"></span>  <span class="text-end" id="fechaActual"></b></span></h2> --}}
                    <div class=" mt-3 mb-5">
                        <div class="d-flex justify-content-center">
                            <form id="calculadoraForm" class="text-center" method="POST" action="{{ route('buscarautorizacion') }}">
                            @csrf
                                <div class="mb-3">
                                    <a type="submit" class="btn btn-warning mt-3 border border-1 border-dark" style="font-size: 40px;" href="{{$href}}"><i class="fa-solid fa-arrow-left"></i> ATRAS</a>
                                    <button type="" class="btn btn-dark boton-buscar mt-3" style="font-size: 40px;">BUSCAR</button>
                                    <button type="submit" class="btn btn-dark boton-asd d-none mt-3" style="font-size: 40px;" onclick="buscar()">BUSCAR</button>
                                </div>

                                <label for="numero" class="form-label me-2 fw-semibold text-secondary" style="font-size: 40px">Ingrese el número de autorización:</label>

                                <div class="mb-3 d-flex align-items-center justify-content-center">
                                    <input type="number" class="form-control me-2 text-center w-50" id="numero" name="idautorizacion" style="font-size: 25px" required maxlength="2" autocomplete="off">
                                    <button type="button" class="btn btn-dark boton-numero" style="background-color: #646464; color: white; font-size: 25px" onclick="borrar()">←</button>
                                </div>
                                <div class="d-grid gap-3">
                                    <div>
                                        <button type="button" class="btn btn-dark boton-numero" style="font-size: 40px; width: 100px;" onclick="agregarNumero(7)">7</button>
                                        <button type="button" class="btn btn-dark boton-numero" style="font-size: 40px; width: 100px;" onclick="agregarNumero(8)">8</button>
                                        <button type="button" class="btn btn-dark boton-numero" style="font-size: 40px; width: 100px;" onclick="agregarNumero(9)">9</button>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-dark boton-numero" onclick="agregarNumero(4)">4</button>
                                        <button type="button" class="btn btn-dark boton-numero" onclick="agregarNumero(5)">5</button>
                                        <button type="button" class="btn btn-dark boton-numero" onclick="agregarNumero(6)">6</button>
                                    </div>

                                    <div>
                                        <button type="button" class="btn btn-dark boton-numero" onclick="agregarNumero(1)">1</button>
                                        <button type="button" class="btn btn-dark boton-numero" onclick="agregarNumero(2)">2</button>
                                        <button type="button" class="btn btn-dark boton-numero" onclick="agregarNumero(3)">3</button>
                                    </div>

                                    <div>
                                        <button type="button" class="btn btn-dark boton-numero" onclick="agregarNumero(0)">0</button>
                                    </div>
                                </div>
                            </form>
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

    <div class="loader d-none">
        <div class="truckWrapper">
          <div class="truckBody">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 198 93"
              class="trucksvg"
            >
              <path
                stroke-width="3"
                stroke="#282828"
                fill="#F83D3D"
                d="M135 22.5H177.264C178.295 22.5 179.22 23.133 179.594 24.0939L192.33 56.8443C192.442 57.1332 192.5 57.4404 192.5 57.7504V89C192.5 90.3807 191.381 91.5 190 91.5H135C133.619 91.5 132.5 90.3807 132.5 89V25C132.5 23.6193 133.619 22.5 135 22.5Z"
              ></path>
              <path
                stroke-width="3"
                stroke="#282828"
                fill="#7D7C7C"
                d="M146 33.5H181.741C182.779 33.5 183.709 34.1415 184.078 35.112L190.538 52.112C191.16 53.748 189.951 55.5 188.201 55.5H146C144.619 55.5 143.5 54.3807 143.5 53V36C143.5 34.6193 144.619 33.5 146 33.5Z"
              ></path>
              <path
                stroke-width="2"
                stroke="#282828"
                fill="#282828"
                d="M150 65C150 65.39 149.763 65.8656 149.127 66.2893C148.499 66.7083 147.573 67 146.5 67C145.427 67 144.501 66.7083 143.873 66.2893C143.237 65.8656 143 65.39 143 65C143 64.61 143.237 64.1344 143.873 63.7107C144.501 63.2917 145.427 63 146.5 63C147.573 63 148.499 63.2917 149.127 63.7107C149.763 64.1344 150 64.61 150 65Z"
              ></path>
              <rect
                stroke-width="2"
                stroke="#282828"
                fill="#FFFCAB"
                rx="1"
                height="7"
                width="5"
                y="63"
                x="187"
              ></rect>
              <rect
                stroke-width="2"
                stroke="#282828"
                fill="#282828"
                rx="1"
                height="11"
                width="4"
                y="81"
                x="193"
              ></rect>
              <rect
                stroke-width="3"
                stroke="#282828"
                fill="#DFDFDF"
                rx="2.5"
                height="90"
                width="121"
                y="1.5"
                x="6.5"
              ></rect>
              <rect
                stroke-width="2"
                stroke="#282828"
                fill="#DFDFDF"
                rx="2"
                height="4"
                width="6"
                y="84"
                x="1"
              ></rect>
            </svg>
          </div>
          <div class="truckTires">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 30 30"
              class="tiresvg"
            >
              <circle
                stroke-width="3"
                stroke="#282828"
                fill="#282828"
                r="13.5"
                cy="15"
                cx="15"
              ></circle>
              <circle fill="#DFDFDF" r="7" cy="15" cx="15"></circle>
            </svg>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 30 30"
              class="tiresvg"
            >
              <circle
                stroke-width="3"
                stroke="#282828"
                fill="#282828"
                r="13.5"
                cy="15"
                cx="15"
              ></circle>
              <circle fill="#DFDFDF" r="7" cy="15" cx="15"></circle>
            </svg>
          </div>
          <div class="road"></div>

          <svg
            xml:space="preserve"
            viewBox="0 0 453.459 453.459"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            xmlns="http://www.w3.org/2000/svg"
            id="Capa_1"
            version="1.1"
            fill="#000000"
            class="lampPost"
          >
            <path
              d="M252.882,0c-37.781,0-68.686,29.953-70.245,67.358h-6.917v8.954c-26.109,2.163-45.463,10.011-45.463,19.366h9.993
                c-1.65,5.146-2.507,10.54-2.507,16.017c0,28.956,23.558,52.514,52.514,52.514c28.956,0,52.514-23.558,52.514-52.514
                c0-5.478-0.856-10.872-2.506-16.017h9.992c0-9.354-19.352-17.204-45.463-19.366v-8.954h-6.149C200.189,38.779,223.924,16,252.882,16
                c29.952,0,54.32,24.368,54.32,54.32c0,28.774-11.078,37.009-25.105,47.437c-17.444,12.968-37.216,27.667-37.216,78.884v113.914
                h-0.797c-5.068,0-9.174,4.108-9.174,9.177c0,2.844,1.293,5.383,3.321,7.066c-3.432,27.933-26.851,95.744-8.226,115.459v11.202h45.75
                v-11.202c18.625-19.715-4.794-87.527-8.227-115.459c2.029-1.683,3.322-4.223,3.322-7.066c0-5.068-4.107-9.177-9.176-9.177h-0.795
                V196.641c0-43.174,14.942-54.283,30.762-66.043c14.793-10.997,31.559-23.461,31.559-60.277C323.202,31.545,291.656,0,252.882,0z
                M232.77,111.694c0,23.442-19.071,42.514-42.514,42.514c-23.442,0-42.514-19.072-42.514-42.514c0-5.531,1.078-10.957,3.141-16.017
                h78.747C231.693,100.736,232.77,106.162,232.77,111.694z"
            ></path>
          </svg>
        </div>
      </div>


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
            if (inputNumero.value.length < 10) {
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

        document.addEventListener('DOMContentLoaded', function () {
            // Obtén el botón BUSCAR por su clase CSS
            const botonBuscar = document.querySelector('.boton-buscar');
            const botonreal = document.querySelector('.boton-asd');
            const loader = document.querySelector('.loader');

            // Agrega un event listener para el evento de clic en el botón BUSCAR
            botonBuscar.addEventListener('click', function (event) {
                // Previene la acción predeterminada del botón (evita la redirección)
                event.preventDefault();
                loader.style.display = 'block';
                setTimeout(() => {
                    botonreal.click();
                }, 1500);
                // Muestra un SweetAlert de carga
                Swal.fire({
                    title: '',
                    html: '<div class="loader">' + loader.innerHTML + '</div><br><p class="text-center">Generando Autorización.</p>',
                    icon: 'info',
                    allowOutsideClick: false, // Evita que el usuario pueda cerrar el SweetAlert haciendo clic fuera de él
                    showConfirmButton: false, // Oculta el botón de confirmación
                    timer: 2000, // Tiempo en milisegundos para que se cierre automáticamente el SweetAlert
                    timerProgressBar: true // Muestra una barra de progreso durante el tiempo del temporizador
                }).then(() => {
                });
            });
        });

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


        .loader {
        width: fit-content;
        height: fit-content;
        display: flex;
        align-items: center;
        justify-content: center;
        }

        .truckWrapper {
        width: 420px;
        height: 100px;
        display: flex;
        flex-direction: column;
        position: relative;
        align-items: center;
        justify-content: flex-end;
        overflow-x: hidden;
        }
        /* truck upper body */
        .truckBody {
        width: 130px;
        height: fit-content;
        margin-bottom: 6px;
        animation: motion 1s linear infinite;
        }
        /* truck suspension animation*/
        @keyframes motion {
        0% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(3px);
        }
        100% {
            transform: translateY(0px);
        }
        }
        /* truck's tires */
        .truckTires {
        width: 130px;
        height: fit-content;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0px 10px 0px 15px;
        position: absolute;
        bottom: 0;
        }
        .truckTires svg {
        width: 24px;
        }

        .road {
        width: 100%;
        height: 1.5px;
        background-color: #282828;
        position: relative;
        bottom: 0;
        align-self: flex-end;
        border-radius: 3px;
        }
        .road::before {
        content: "";
        position: absolute;
        width: 20px;
        height: 100%;
        background-color: #282828;
        right: -50%;
        border-radius: 3px;
        animation: roadAnimation 1.4s linear infinite;
        border-left: 10px solid white;
        }
        .road::after {
        content: "";
        position: absolute;
        width: 10px;
        height: 100%;
        background-color: #282828;
        right: -65%;
        border-radius: 3px;
        animation: roadAnimation 1.4s linear infinite;
        border-left: 4px solid white;
        }

        .lampPost {
        position: absolute;
        bottom: 0;
        right: -0%;
        height: 90px;
        animation: roadAnimation 3.4s linear infinite;
        }

        @keyframes roadAnimation {
        0% {
            transform: translateX(0px);
        }
        100% {
            transform: translateX(-350px);
        }
        }

    </style>

</body>

</html>
