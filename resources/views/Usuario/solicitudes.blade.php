@include('layouts/head')

    <body class="antialiased">
        @include('layouts/nav')
        @if(session('rol') == 'Gerencia')
            <div class="col-11" style="margin-left:3.5%">
                <div class="">
                    <form action="" method="post">
                        <div class="d-flex justify-content-between align-items-center" style="margin-top: 8px; margin-right: -14px;">
                            <span class="d-inline mb-0 text-dark text-end" style="font-size: 35px"><b>⭐- DIRECCIÓN GENERAL -⭐</b></span>
                            <h2 class="p-3 mb-0 text-secondary text-end"><a href="estadisticas" id="btnAgencias" class="btn btn-dark fw-bold me-2" title="ESTADÍSTICAS AUTORIZACIONES"><i class="fa-solid fa-chart-bar text-white"></i> &nbspESTADÍSTICAS</a><b><span id="fechaActual"></span></b></h2>
                        </div>
                        <script>
                                function obtenerFechaActual() {
                                    const fecha = new Date();
                                    const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                                    const mes = meses[fecha.getMonth()];
                                    const dia = fecha.getDate();
                                    const anio = fecha.getFullYear();
                                    let horas = fecha.getHours();
                                    let amPm = horas >= 12 ? 'PM' : 'AM'; // Se establece 'AM' si horas es menor a 12, de lo contrario, se establece 'PM'

                                    // Convertir 0 a 12 AM
                                    horas = horas % 12 || 12;

                                    const minutos = fecha.getMinutes();
                                    const segundos = fecha.getSeconds();

                                    return `${mes} ${dia}, ${anio} - ${horas}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')} ${amPm}`;
                                }

                                function actualizarFechaActual() {
                                    const elementoFecha = document.getElementById('fechaActual');
                                    elementoFecha.textContent = obtenerFechaActual();
                                }

                                setInterval(actualizarFechaActual, 1000);
                        </script>


                    </form>
                </div>
                <div class="table-responsive mb-5" id="tablepersonas">
                    <table id="personas" class="hover table table-striped shadow-lg mt-4 table-hover table-bordered">
                        <thead style="background-color: #646464;">
                            <tr class="text-white">
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center w-50">CONCEPTO</th>
                                <th scope="col" class="text-center">ESTADO</th>
                                <th scope="col" class="text-center">DETALLE</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">

                        </tbody>
                    </table>
                </div>
            </div>
            <script src="ResourcesAll/dtables/jquery-3.5.1.js"></script>
            <script src="ResourcesAll/dtables/jquerydataTables.js"></script>
            <script src="ResourcesAll/dtables/dataTablesbootstrap5.js"></script>
            <script src="ResourcesAll/dtables/dtable1.min.js"></script>
            <script src="ResourcesAll/dtables/botonesdt.min.js"></script>
            <script src="ResourcesAll/dtables/estilobotondt.min.js"></script>
            <script src="ResourcesAll/dtables/botonimprimir.min.js"></script>
            <script src="ResourcesAll/dtables/imprimir2.min.js"></script>
            <script src="js/condicionNit.js"></script>
            @include('layouts.usuario.datatable')
        @else
            <div class="container-fluid row p-4">
                <!-- REGISTRO DE AUTORIZACIONES POR PARTE DE USUARIOS (Coord,Directores,Jefaturas) -->
                <form 
                    id="pagare"
                    action="{{ route('solicitar.autorizacion') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="col m-3"
                    onsubmit="return enviarFormulario()"
                >
                    @csrf

                    <!-- Título principal -->
                    <h2 class="p-2 text-secondary text-center">
                        <strong>Solicitar Autorización</strong>
                    </h2>

                    <!-- Opciones incluidas -->
                    @include('layouts.option')

                    <!-- Contenedor dinámico -->
                    <div id="cuerpo"></div>

                </form>



                <!-- FECHA -->
                <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9 col-xxl-9">
                    <div class="">
                            <div class="" style="margin-top: 8px; margin-right: -14px;">
                                <h2 class="p-3 mb-0 text-secondary text-end"><b><span id="fechaActual"></span></b></h2>
                            </div>
                            <!-- script para que la fecha se actualice cada segundo -->
                            <script>
                                function obtenerFechaActual() {
                                    const fecha = new Date();
                                    const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                                    const mes = meses[fecha.getMonth()];
                                    const dia = fecha.getDate();
                                    const anio = fecha.getFullYear();
                                    let horas = fecha.getHours();
                                    let amPm = horas >= 12 ? 'PM' :
                                        'AM'; // Se establece 'AM' si horas es menor a 12, de lo contrario, se establece 'PM'

                                    // Convertir 0 a 12 AM
                                    horas = horas % 12 || 12;

                                    const minutos = fecha.getMinutes();
                                    const segundos = fecha.getSeconds();

                                    return `${mes} ${dia}, ${anio} - ${horas}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')} ${amPm}`;
                                }

                                function actualizarFechaActual() {
                                    const elementoFecha = document.getElementById('fechaActual');
                                    elementoFecha.textContent = obtenerFechaActual();
                                }

                                setInterval(actualizarFechaActual, 1000);
                            </script>
                    </div>
                    <div class="table-responsive">
                        <table id="personas" class="hover table table-striped shadow-lg mt-4 table-hover table-bordered">
                            <thead style="background-color: #646464;">
                                <tr class="text-white">
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center" style="width: 35%">CONCEPTO</th>
                                    <th scope="col" class="text-center" style="width: 20%">ESTADO</th>
                                    <th scope="col" class="text-center" style="width: 13%">DETALLE</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">

                            </tbody>
                        </table>
                    </div>
                </div>



                <!-- SCRIPTS -->
                <script src="ResourcesAll/dtables/jquery-3.5.1.js"></script>
                <script src="ResourcesAll/dtables/jquerydataTables.js"></script>
                <script src="ResourcesAll/dtables/dataTablesbootstrap5.js"></script>
                <script src="ResourcesAll/dtables/dtable1.min.js"></script>
                <script src="ResourcesAll/dtables/botonesdt.min.js"></script>
                <script src="ResourcesAll/dtables/estilobotondt.min.js"></script>
                <script src="ResourcesAll/dtables/botonimprimir.min.js"></script>
                <script src="ResourcesAll/dtables/imprimir2.min.js"></script>
                <script src="js/condicionNit.js"></script>
                @include('layouts.usuario.datatable')
            </div>
        @endif


  
        <!-- estilos de usuarios coordinadores, directores y jefaturas -->
        @include('layouts.usuario.style')

        <!-- tooltip para cuando se abren todas los conceptos o dispocisiones -->
        @include('layouts.tooltipstyle')

        <!-- Notificacion inferior -->
        <!-- @include('layouts.notification') -->

        <!-- Solicitar celular si la cuenta no tiene vinculado un numero -->
        @include('layouts.celular')
        @include('layouts.footer')
        
        <!-- si se cierra la sesion que retorne -->
        @include('layouts.retornar')



    </body>
    <!-- alertas -->
    @if(session('bienvenida'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session('bienvenida') }}',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif

    @if (session('correcto'))
        <div>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: "¡Correcto!",
                    html: "{!! session('correcto') !!}",
                    confirmButtonColor: '#646464',

                });
            </script>
        </div>
    @endif

    @if (session('incorrecto'))
        <div>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: "¡Advertencia!",
                    html: "{!! session('incorrecto') !!}",
                    confirmButtonColor: '#646464',

                });
            </script>
        </div>
    @endif

    @if (session('incorrecto2'))
        <div>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: "¡Usted ha sido BLOQUEADO!",
                    html: "{!! session('incorrecto2') !!}",
                    confirmButtonColor: '#646464',

                });
            </script>
        </div>
    @endif

    @error('message')
        <div>
        <script>
        Swal.fire
            ({
                icon: 'error',
                title: "Error al registrar!\n{{$message}}",
                text: '',
                confirmButtonColor: '#005E56'

            });
        </script>
        </div>
    @enderror
</html>
