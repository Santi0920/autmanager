@include('layouts/head')

<body class="antialiased">
    @include('layouts/nav')

    <div class="col-11 mt-5 mb-5" style="margin-left:3.5%">
        <div class="container">
            <div class="row justify-content-center align-items-center m-0">
               <div class="col-12 col-sm-10 col-md-8 p-0 shadow-lg border border-dark">
                  <div class="row m-0">

                     <div class="row g-0 text-center">
                        <div class="col-sm-none col-md-none col-lg-2 bg-info">

                        </div>
                        <div class="col-md-12 col-lg-10">
                           <div class="row g-0 text-center ">
                              <div class="col-md-7 col-lg-9 bg-info d-flex align-items-center justify-content-center p-3">
                                 <span class="h2 fw-bold">SOLICITUD</span>
                              </div>
                              <div class="col-md-5 col-lg-3">
                                 <div class="row g-0 justify-content-center border p-2">
                                    <span class="h3 fw-bold mb-0 text-danger">No.{{ $id }}</span>
                                 </div>
                                 <div class="row g-0 align-items-center justify-content-center bg-danger-subtle border p-2">
                                    <span class="h5 fw-bold mb-0 text-danger">C-CORREGIR</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>

                     <div class="row g-0 text-center">
                        <div class="col-sm-12 col-md-12 col-lg-2 d-flex align-items-center justify-content-center rounded-0 bg-warning-subtle border p-3 border border-dark">
                           <span class="h1 fw-bold mb-0">S</span>
                        </div>

                        <div class="col-sm-12 col-md-12 col-lg-10">
                           <div class="row g-0 justify-content-start">
                              <div class="row g-0 row-cols-2 justify-content-center">
                                 <div class="col-md-9 d-flex align-items-center justify-content-start border p-2">
                                    <span>31 - CALI WALTER CRUZ BRICEÑO</span>
                                 </div>
                                 <div class="col-md-3 d-flex align-items-center justify-content-center border p-2">
                                    <span class="mb-0">10 ABR 2024 3:32PM</span>
                                 </div>
                              </div>
                           </div>
                           <div class="row g-0 row-cols-2 d-flex justify-content-start">
                              <div class="col-sm-6 col-md-9 col-lg-9 d-flex align-items-center justify-content-start border p-2">
                                 <span class="">11B - ASOCIACION MAYOR A 90 DIAS</span>
                              </div>
                              <div class="col-sm-6 col-md-3 col-lg-3 d-flex align-items-center justify-content-center border p-3">
                                 <!-- <span class="h1 fw-bold mb-0">1</span> -->
                              </div>
                           </div>
                           <div class="row g-0">
                              <div class="col-md-12 d-flex justify-content-start border p-2">
                                 <span>1144029855 - 85504 - CAROLINA GONZALEZ GONZALEZ</span>
                              </div>
                           </div>
                           <div class="row g-0">
                              <div class="col-sm-12 col-md-9 text-start border p-2">
                                 <p class="mb-0">Se solicita vinculacion antes de los 90 dias</p>
                                 <p class="mb-0">Fecha de Retiro: 05-FEB-2024</p>
                                 <p class="mb-0">Fecha Dev. Apo: 28-FEB-2024</p>
                                 <p class="mb-0">Sin Derecho a Bono</p>
                              </div>
                              <div class="col-sm-12 col-md-3 d-flex align-items-center justify-content-center btn btn-outline-info rounded-0 p-5">
                                 <span class="h1 fw-bold mb-0">
                                    PDF
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                       fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                       stroke-linejoin="round" class="lucide lucide-file-text">
                                       <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                       <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                       <path d="M10 9H8" />
                                       <path d="M16 13H8" />
                                       <path d="M16 17H8" />
                                    </svg>
                                 </span>
                              </div>
                           </div>
                        </div>
                     </div>

                     <div class="row g-0 text-center">
                        <div class="col-sm-12 col-md-12 col-lg-2 d-flex align-items-center justify-content-center bg-success-subtle border p-3 border border-dark">
                           <span class="h1 fw-bold mb-0">V</span>
                        </div>

                        <div class="col-sm-12 col-md-12 col-lg-10">
                           <div class="row g-0">
                              <div class="col-md-9 d-flex align-items-center justify-content-center border p-4">
                                 <span class="h5 fw-bold mb-0">C3-CAROLINA GONZALEZ GONZALEZ</span>
                              </div>
                              <div class="col-md-3 d-flex align-items-center justify-content-center border p-3">
                                 <span class="mb-0">10 ABR 2024 7:00PM</span>
                              </div>
                           </div>
                        </div>
                     </div>

                     <div class="row g-0 text-center">
                        <div class="col-sm-6 col-md-12 col-lg-2 d-flex align-items-center justify-content-center bg-danger-subtle border p-3 border border-dark">
                           <span class="h1 fw-bold mb-0">C</span>
                        </div>
                        <div class="col-md-12 col-lg-10">
                           <div class="row g-0">
                              <div class="col-md-9 d-flex align-items-center justify-content-center border p-2">
                                 <span class="h5 fw-bold mb-0">DIRECCION GENERAL</span>
                              </div>
                              <div class="col-md-3 border p-2">
                                 <span class="mb-0">10 ABR 2024</span>
                                 <span class="mb-0">7:00PM</span>
                              </div>
                           </div>
                           <div class="row g-0 border text-center p-2">
                              <p class="mb-0 fw-semibold">Investigar por que se devolvio el 28 de Febrero si el Acta tiene
                                 Fecha de 01 de Marzo 2024</p>
                           </div>
                        </div>
                     </div>

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
