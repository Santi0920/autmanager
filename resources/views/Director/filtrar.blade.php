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

    <div class="col-11" style="margin-left:3.5%">
        <div class="">
            <div class="" style="margin-top: 0px; margin-right: -14px;">
                <h2 class="p-2 mb-0 text-secondary text-start mt-2"><b><span class="text-warning" style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);"></span>  <span class="text-end" id="fechaActual"></b></span></h2>
                    <div class=" mt-3 mb-5">
                        <div class="d-flex justify-content-center">
                            <form id="calculadoraForm" class="text-center" method="POST" action="{{ route('buscarautorizacion') }}">
                            @csrf
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
                                <div>
                                    <button type="submit" class="btn btn-dark boton-buscar mt-3" style="font-size: 40px;" onclick="buscar()">BUSCAR</button>
                                </div>
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>


    @include('layouts.footer')



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
