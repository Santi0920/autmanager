@include('layouts/head')

    <body class="antialiased">
        @include('layouts/nav')

            <div class="col-11 mx-auto">
                <!-- Header Sección -->
                <div class="row align-items-center my-3">
                    <div class="col-12 col-lg-6">
                        <h1 class="text-gradient-primary fw-bold mb-1"
                            style="font-size: 2rem; text-shadow: 1px 1px 3px rgba(0,0,0,0.25);">
                            ⚠️ERRORES/BUGS SOFTWARE⚠️
                        </h1>
                    </div>
                    <div class="col-12 col-lg-6 d-flex justify-content-lg-end align-items-center flex-wrap mt-2 mt-lg-0">
                        <span class="text-secondary fw-semibold fs-2 fw-bold" id="fechaActual"></span>

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
                    <table id="bugs" class="table table-hover table-bordered shadow-sm align-middle" style="border-radius: 12px; overflow: hidden;">
                        <thead style="background: linear-gradient(90deg, #343a40, #495057); color: #ffc107; font-weight: 600;">
                            <tr class="text-center">
                                <th>#</th>
                                <th class="">SOLICITADO POR</th>
                                <th class="">TITULO BUG</th>
                                <th class="W-50">DESCRIPCION</th>
                                <th>IMAGEN</th>
                                <th>ESTADO</th>
                                <th>FECHA</th>
                                <th>ACCIONES</th>
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
            <script>
                var table = $('#bugs').DataTable({ 
                    "ajax": {
                        "url": "{{ route('data.bugs') }}",
                    },
                    processing: true,
                    "order": [
                        [0, 'desc']
                    ],
                    scrollY: 420,
                    "columns": [
                        {
                            data: 'id',
                            render: function(data, type, row) {
                                return `<span class='text-danger fw-bold'>${row.id}</span>`;
                            },
                            createdCell: function(td) {
                                $(td).css({
                                    'font-weight': '500',
                                    'font-size': '28px',
                                    'text-align': 'center',
                                });
                            },
                            "searchable": true, "orderable": true
                        },
                        {
                            data: 'solicitadopor',
                            render: function(data, type, row) {

                                let texto = data ? data.toUpperCase() : "";

                                if (texto.includes("GERENCIA GENERAL")) {
                                    texto = texto.replace("GERENCIA GENERAL", "DIRECCIÓN GENERAL");
                                }

                                return `<span class='fw-semibold'>${texto}</span>`;
                            },
                            searchable: false,
                        },
                        {
                            data: 'title',
                            render: function(data, type, row) {
                                return `<span class='fw-semibold'>${data.toUpperCase()}</span>`;
                            }, "searchable": false,
                        },
                        {
                            data: 'description',
                            render: function(data, type, row) {
                                return `<span class='fw-semibold'>${data.toUpperCase()}</span>`;
                            }, "searchable": false,
                        },
                        {
                            data: 'image',
                            render: function(data, type, row) {
                                if (data) {
                                    return `
                                        <a href="Storage/files/reporteimgs/${data}" target="_blank">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg" 
                                                alt="PDF" 
                                                style="width:35px; height:35px; object-fit:contain;">
                                        </a>
                                    `;
                                }
                                return '';
                            },"searchable": false,
                        },
                        {
                            data: 'status',
                            render: function(data) {
                            let Estado = ''; // Valor por defecto

                            if (data === "pendiente") {
                                Estado = `<div class="btn btn-secondary shadow blink" style="padding: 0.4rem 1.6rem; border-radius: 10%; font-weight: 600; font-size: 14px;">
                                            <label style="margin-bottom: 0px;">${data.toUpperCase()}</label>
                                        </div>`;
                            } else if (data === "corregir") {
                                Estado = `<div class="btn btn-warning shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%; font-weight: 600; font-size: 14px;">
                                            <label style="margin-bottom: 0px;">${'CORREGIDO'.toUpperCase()}</label>
                                        </div>`;
                            } else if (data === "descartar") {
                                Estado = `<div class="btn btn-danger shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%; font-weight: 600; font-size: 14px;">
                                            <label style="margin-bottom: 0px;">${data.toUpperCase()}</label>
                                        </div>`;
                            } else if (data === "en progreso") {
                                Estado = `<div class="btn btn-info shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%; font-weight: 600; font-size: 14px;">
                                            <label style="margin-bottom: 0px;">${data.toUpperCase()}</label>
                                        </div>`;
                            } else if (data === "urgente") {
                                Estado = `<div class="btn btn-danger shadow blink" style="padding: 0.4rem 1.6rem; border-radius: 10%; font-weight: 600; font-size: 14px;">
                                            <label style="margin-bottom: 0px;">${data.toUpperCase()}</label>
                                        </div>`;
                            }

                            return Estado;

                            }
                        },
                        {
                            data: 'created_at',
                            render: function(data) {
                                if (!data) return '';
                                const date = new Date(data);
                                const options = { day: '2-digit', month: 'short', year: 'numeric' };
                                // Ejemplo: Nov 04 2025
                                return `<span class='fw-semibold'>${date.toLocaleDateString('es-ES', options).toUpperCase()}</span>`;
                            }
                        },
                        {
                            data: 'id', // en lugar de null
                            render: function(data, type, row) {
                                if(row.status != 'corregir' && row.status != 'descartar'){
                                    return `
                                    @if(session('rol') == 'Gerencia')
                                        <form method="POST" action="bugs/cambiarestado-${data}">
                                            @csrf
                                            <button type="submit" class="btn btn-warning shadow" title="Corregir" value="corregir" name="accion">
                                                <i class="fas fa-wrench"></i>
                                            </button>
                                            <button type="submit" class="btn btn-danger shadow" title="Descartar" value="descartar" name="accion">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                            <button type="submit" class="btn btn-warning shadow" title="En Progreso / Trabajando" value="en progreso" name="accion">
                                                <i class="fas fa-spinner"></i>
                                            </button>
                                            <button type="submit" class="btn btn-danger shadow" title="Urgente / Crítico" value="urgente" name="accion">
                                                <i class="fas fa-bolt"></i>
                                            </button>
                                        </form>
                                    @else
                                    <span class="h3 fw-bold">N/A</span>
                                    @endif
                                    `;
                                }
                                return `<span class="h3 fw-bold">N/A</span>`
                            }
                        },

                    ],
                    "lengthMenu": [
                        [5,10],
                        [5,10]
                    ],
                    "drawCallback": function() {
                        var api = this.api();
                        var noRecordsMessage = api.table().container().querySelector('.dataTables_empty');
                        if (noRecordsMessage) {
                            noRecordsMessage.style.textAlign = 'center';
                            noRecordsMessage.style.fontSize = '24px';
                            noRecordsMessage.style.fontWeight = '600';
                        }
                    },
                    "language": {
                        "lengthMenu": `
                            <span style="
                                font-size: 15px;
                                font-weight: 800;
                                color: #005e56;
                                text-transform: uppercase;
                                margin-right: 10px;
                                background: rgba(0, 94, 86, 0.08);
                                padding: 6px 10px;
                                border-radius: 6px;
                                border-left: 4px solid #005e56;
                                display: inline-flex;
                                align-items: center;
                                gap: 6px;
                            ">
                                <i class='fa-solid fa-bug'></i> Mostrar:
                            </span> _MENU_
                        `,
                        "zeroRecords": `
                            <div class='py-4 text-center'>
                                <i class='fa-solid fa-bug-slash' style='font-size: 55px; color: #dc3545;'></i>
                                <div style='font-size: 24px; font-weight: 600; margin-top: 10px; color: #343a40;'>
                                    No se encontraron reportes de bugs
                                </div>
                                <p style='font-size: 15px; color: #6c757d; margin-top: 6px;'>
                                    Verifica los filtros o registra un nuevo reporte
                                </p>
                            </div>
                        `,
                        "info": `<span style="font-weight: 700; color: #005e56; font-size: 14.5px;">Página <b>_PAGE_</b> de <b>_PAGES_</b></span>`,
                        "infoEmpty": `<span style="font-weight: 700; color: #a30000; font-size: 14.5px;">No hay reportes disponibles</span>`,
                        "infoFiltered": `<span style="font-size: 13px; font-weight: 500; color: #6c757d;">(Filtrado de <b>_MAX_</b> reportes totales)</span>`,
                        "search": `
                            <span style="
                                font-size: 18px;
                                font-weight: 900;
                                color: #005e56;
                                text-transform: uppercase;
                                padding: 8px 12px;
                                border-radius: 6px;
                                background: rgba(0, 94, 86, 0.08);
                                border-left: 4px solid #005e56;
                                letter-spacing: 1px;
                                margin-right: 8px;
                                display: inline-flex;
                                align-items: center;
                                gap: 8px;
                            ">
                                🔍 Buscar Bug/Error:
                            </span>
                        `,
                        "paginate": {
                            "next": `<span style="font-weight: 600; color: #005e56; font-size: 14px;">Siguiente →</span>`,
                            "previous": `<span style="font-weight: 600; color: #005e56; font-size: 14px;">← Anterior</span>`
                        }
                    }
                });
            </script>


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
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

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
