@include('layouts/head')

    <body class="antialiased">
        @include('layouts/nav')
        @if(session('rol') == 'Gerencia')
            <div class="col-11 mx-auto">
                <!-- Header Sección -->
                <div class="row align-items-center my-3">
                    <div class="col-12 col-lg-6">
                        <h1 class="text-gradient-primary fw-bold mb-1"
                            style="font-size: 2rem; text-shadow: 1px 1px 3px rgba(0,0,0,0.25);">
                            ⭐DIRECCIÓN GENERAL⭐@include('layouts.usuario.corteyid')
                        </h1>
                    </div>
                    <div class="col-12 col-lg-6 d-flex justify-content-lg-end align-items-center flex-wrap mt-2 mt-lg-0">

                        <a href="estadisticas" id="btnAgencias"
                        class="btn btn-gradient-primary fw-bold me-3 mb-2"
                        title="ESTADÍSTICAS AUTORIZACIONES">
                            <i class="fa-solid fa-chart-bar text-white me-2"></i> ESTADÍSTICAS
                        </a>

                        <span class="text-secondary fw-semibold fs-2 fw-bold" id="fechaActual"></span>
                        @include('layouts.usuario.modalautorizacion.modalenviara')
                    </div>

                </div>

                <!-- Script Fecha y Hora -->
                <script>
                    function obtenerFechaActual() {
                        const fecha = new Date();
                        const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                        const mes = meses[fecha.getMonth()];
                        const dia = fecha.getDate();
                        const anio = fecha.getFullYear();
                        let horas = fecha.getHours();
                        const amPm = horas >= 12 ? 'PM' : 'AM';
                        horas = horas % 12 || 12;
                        const minutos = fecha.getMinutes();
                        const segundos = fecha.getSeconds();
                        return `${mes} ${dia}, ${anio} - ${horas}:${minutos.toString().padStart(2,'0')}:${segundos.toString().padStart(2,'0')} ${amPm}`;
                    }
                    function actualizarFechaActual() {
                        document.getElementById('fechaActual').textContent = obtenerFechaActual();
                    }
                    setInterval(actualizarFechaActual, 1000);
                    actualizarFechaActual();
                </script>

                <!-- Tabla Profesional -->
                <div class="table-responsive mt-4">
                    <table id="personas" class="table table-hover table-bordered shadow-sm align-middle" style="border-radius: 12px; overflow: hidden;">
                        <thead style="background: linear-gradient(90deg, #343a40, #495057); color: #ffc107; font-weight: 600;">
                            <tr class="text-center">
                                <th>#</th>
                                <th class="w-50">CONCEPTO</th>
                                <th>ESTADO</th>
                                <th>DETALLE</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <!-- Contenido dinámico -->
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
                class="col m-3 premium-form"
            >
                @csrf

                <!-- Título principal estilo corporativo -->
                <div class="title-section mb-4">
                    <h2 class="form-title">
                        <i class="fa-solid fa-file-shield me-2"></i> Solicitar Autorización
                    </h2>
                    <p class="form-description">Por favor completa los datos requeridos para continuar</p>
                </div>

                <!-- Select Tipo de Autorización -->
                <div class="mb-4 w-100" id="id">
                    <label class="premium-label">
                        <i class="fa-solid fa-list-check me-1"></i> Tipo de Autorización 
                        <span class="required">*</span>
                    </label>

                    <select class="form-select premium-select" name="tautorizacion" id="autorizaciones" required>
                        <option selected disabled>Selecciona una opción</option>

                        @foreach ($grupos as $no => $items)
                            @php
                                $area = isset($items[0]->Areas) ? strtoupper($items[0]->Areas) : 'GLOBAL';
                            @endphp

                            <option disabled class="group-title">
                                ━━ {{ $area }} ━━
                            </option>

                            @foreach ($items as $autorizacion)
                                <option value="{{ $autorizacion->ID }}">
                                    {{ $autorizacion->Concepto }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>

                    <small class="text-muted ms-1 fst-italic">
                        Selecciona el tipo según la necesidad del proceso
                    </small>
                </div>

                <!-- Aquí se cargan dinámicamente los campos según selección -->
                <div id="cuerpo"></div>

            </form>




                <!-- FECHA -->
                <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9 col-xxl-9">
                    
                    <div class="">
                            
                            <div class="" style="margin-top: 8px; margin-right: -14px;">
                                <h2 class="p-3 mb-0 text-secondary text-end fw-bold"><b><span id="fechaActual"></span></b></h2>
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
                        <table id="personas" class="table table-hover table-bordered shadow-sm align-middle" style="border-radius: 12px; overflow: hidden;">
                            <thead style="background: linear-gradient(90deg, #343a40, #495057); color: #ffc107; font-weight: 600;">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th class="w-50">CONCEPTO</th>
                                    <th>ESTADO</th>
                                    <th>DETALLE</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <!-- Contenido dinámico -->
                            </tbody>
                        </table>
                    </div>
                    @include('layouts.usuario.corteyid')
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
        <!--  -->

        <!-- Solicitar celular si la cuenta no tiene vinculado un numero -->
        @include('layouts.celular')
        @include('layouts.footer')
        
        <!-- si se cierra la sesion que retorne -->
        @include('layouts.retornar')
    </body>
    <!-- alertas -->
    @if(session('bienvenida'))
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

        <script>
            // Lanzar confetti sutil al login
            confetti({
                particleCount: 80,
                spread: 60,
                gravity: 0.6,
                origin: { y: 0.6 },
                colors: ['#FFD700', '#ffffff', '#1E3C72', '#2A5298']
            });

            // Modal tipo “login exitoso” sin toast
            Swal.fire({
                title: '{{ session('bienvenida') }}',
                html: '<i class="fa-solid fa-user-check" style="font-size: 3rem; color: #FFD700; display:block; margin-bottom:1rem;" class="animate__animated animate__bounce"></i>',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                background: 'linear-gradient(135deg, #fff6a2ff, #FFFFFF, #a7a7a7ff)', // colores claros y suaves
                color: '#1E3C72', // texto en azul oscuro para contraste
                customClass: {
                    popup: 'swal-premium-modal'
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });

        </script>

        <style>
            .swal-premium-modal {
                font-family: 'Poppins', sans-serif;
                font-weight: 700;
                font-size: 1.2rem;
                box-shadow: 0 8px 20px rgba(0,0,0,0.35);
                border-radius: 1rem;
                padding: 2rem 2.5rem;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                text-align: center;
            }

            .swal-premium-modal .swal2-title {
                font-size: 1.5rem;
                text-shadow: 1px 1px 3px rgba(0,0,0,0.4);
            }

            .animate__bounce {
                animation: bounce 1s infinite;
            }

            @keyframes bounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }
        </style>
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
