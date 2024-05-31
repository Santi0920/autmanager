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
    {{-- FECHA --}}
    <div class="col-11" style="margin-left:3.5%">
        <div class="">
            <form action="" method="post">
                <div class="d-flex justify-content-between align-items-center" style="margin-top: 8px; margin-right: -14px;">
                    <span class="d-inline mb-0 text-dark text-end" style="font-size: 35px"><b>⭐- COORDINACION 9 -⭐</b></span>
                    <h2 class="p-3 mb-0 text-secondary text-end"><b><span id="fechaActual"></span></b></h2>
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
        <div class="table-responsive mb-5">
            <table id="personas" class="hover table table-striped shadow-lg mt-4 table-hover table-bordered">
                <thead style="background-color: #646464;">
                    <tr class="text-white">
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">CONCEPTO</th>
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
    <script>
        var table = $('#personas').DataTable({
            "ajax": "{{ route('datagercoordi.solicitudes') }}",
            "order": [
                [0, 'asc']
            ],
            scrollY: 420,
            "columns": [{
                data: 'IDAutorizacion',
                        render: function(data, type, row) {
                            var ID = `<span class='text-danger fw-bold'>${row.IDAutorizacion}</span>`

                            return ID
                        },
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).css({
                                'font-weight': '500',
                                'font-size': '30px',
                                'text-align': 'center',
                            });
                        }
                    },
                    {
                        data: 'CodigoAutorizacion',
                        render: function(data, type, row) {
                            var Codigo =
                                `${row.Concepto}`

                            return Codigo
                        },
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).css({
                                'font-weight': '500',
                                'font-size': '20px',
                                'text-align': 'justify'
                            });
                        }
                    },
                    {
                        data: 'Estado',
                        render: function(data, type, row) {
                            // Supongo que deseas mostrar el ID, no un botón de Aprobado, por lo que he cambiado el nombre de la variable a 'IDLabel'
                            if (row.Estado == 0) {
                                var Estado =
                                    '<div class="btn btn-danger shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%;font-weight: 600;font-size: 14px;">RECHAZADO</div>';
                            } else if (row.Estado == 1) {
                                var Estado =
                                    `<button class="btn btn-info shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">REMITIDO A GERENCIA</button>`
                            }  else if (row.Estado == 2) {
                                var Estado =
                                    `<button class="btn btn-warning shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">EN TRÁMITE</button>`
                            } else if (row.Estado == 3) {
                                var Estado =
                                    '<div class="btn btn-primary shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">CORREGIR</div>'
                            } else if (row.Estado == 4) {
                                var Estado =
                                    '<div class="btn btn-success blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">APROBADO POR GERENCIA</div>'
                            }else if (row.Estado == 5) {
                                var Estado =
                                    '<div class="btn btn-danger blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">RECHAZADO POR GERENCIA</div>'
                            } else {
                                var Estado =
                                    '<div class="btn btn-info shadow" style="padding: 0.4rem 1.4rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">REMITIDO A GERENCIA</div>'
                            }

                            return Estado;
                        },
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).css({
                                'font-weight': '500',
                                'font-size': '20px',
                                'text-align': 'center'
                            });
                        }
                    },
                {
                    data: 'Detalle',
                    render: function(data, type, row) {

                        var id = row.IDAutorizacion; // Obtener el ID de la fila
                        var url = "{{ route('updatecoor.autorizacion', ':id') }}";
                        url = url.replace(':id', id);


                        const cedula = row.Cedula;
                        var condiciondenit = esCondicionNit(row.CodigoAutorizacion);
                            var visualizarnit = condicionparamostrarNit(row.CodigoAutorizacion);
                            if(condiciondenit){
                                var cedulaFormateada = cedula;
                            }else{
                                var cedulaFormateada = new Intl.NumberFormat().format(cedula);

                            }

                            const mesesEnEspanol = [
                        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                        ];

                        const fechainsercion = row.FechaInsercion;
                        // Convertir fechainsercion a un objeto Date
                        const fechaInsercionDate = new Date(fechainsercion);

                        // Obtener la fecha actual
                        const fechaActual = new Date();

                        // Calcular la diferencia en milisegundos
                        const diferenciaMilisegundos = fechaActual - fechaInsercionDate;

                        // Convertir la diferencia de milisegundos a días
                        const diferenciaDias = Math.floor(diferenciaMilisegundos / (1000 * 60 * 60 * 24));

                        // Verificar si la diferencia supera los 180 días
                        const estado = fechainsercion == null || fechainsercion === undefined
                        ? `<span class="fs-2">⚪⚪⚪</span>`
                        : diferenciaDias > 179
                            ? `<span class="fs-2">⚪⚪🔴</span>`
                            : diferenciaDias > 169
                                ? `<span class="fs-2">⚪🟡⚪</span>`
                                : `<span class="fs-2">🟢⚪⚪</span>`;

                        const dia = fechaInsercionDate.getDate();
                        const mes = mesesEnEspanol[fechaInsercionDate.getMonth()];
                        const año = fechaInsercionDate.getFullYear();
                        const fechaFormateada = `${mes} ${dia} del ${año}`;


                            var modalEditar = `
                                <a type="button" class="btn btn-outline-secondary" id="modalLink_${id}" data-bs-toggle="modal" data-bs-target="#exampleModal_${id}"
                                            data-id="${id}">
                                            <i class="fa-solid fa-eye fs-5"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" id="modalLink_${id}" data-bs-toggle="modal" data-bs-target="#exampleModal_${id}"
                                            data-id="${id}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" color="black"
                                            class="bi bi-eye" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                            </svg> Ver detallado
                                        </a>
                                    </li>
                                </ul>


                                {{-- MODAL --}}
                                <div class="modal fade bd-example-modal-lg" id="exampleModal_${id}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header text-center">
                                            <h6 class="modal-title" id="exampleModalLongTitle"
                                                style="color: #646464;font-weight: 700;font-size: 22px">DETALLE DE LA AUTORIZACIÓN</h6>
                                            <button type="button" class="btn-close fs-5" data-bs-dismiss="modal" aria-label="Close"
                                                style="outline: none; border: none; font-size:18px">
                                            </button>
                                            </div>
                                            <div class="modal-body p-1">

                                            <div class="row g-0 text-center">
                                                <div class="col-sm-none col-md-none col-lg-2 bg-primary-subtle">

                                                </div>
                                                <div class="col-md-12 col-lg-10">
                                                    <div class="row g-0 text-center">
                                                        <div class="col-md-7 col-lg-9 bg-primary-subtle d-flex align-items-center justify-content-center p-3">
                                                            <span class="h2 fw-bold">SOLICITUD</span>
                                                        </div>
                                                        <div class="col-md-5 col-lg-3">
                                                        <div class="row g-0 justify-content-center border p-2">
                                                            <span class="h3 fw-bold mb-0 text-danger">No.${row.IDAutorizacion}</span>
                                                        </div>

                                                        <div class="row g-0 align-items-center justify-content-center border p-2">
                                                            ${row.Estado == 0 ?
                                                                `<button class="btn btn-danger shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">R - RECHAZADO</button>` :
                                                                row.Estado == 1 ?
                                                                `<button class="btn btn-info shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">T - REMITIDO A GERENCIA</button>` :
                                                                row.Estado == 2 ?
                                                                `<button class="btn btn-warning shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">T - EN TRÁMITE</button>` :
                                                                row.Estado == 3 ?
                                                                `<button class="btn btn-primary shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">C - CORREGIR</button>` :
                                                                row.Estado == 4 ?
                                                                `<button class="btn btn-success  shadow blink" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">AP - APROBADO</button>` :
                                                                row.Estado == 5 ?
                                                                `<button class="btn btn-danger shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">R - RECHAZADO POR GERENCIA</button>` :
                                                                '<button class="btn btn-warning shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">T - EN TRÁMITE</button>'
                                                            }
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-0 text-center">
                                                <div
                                                    class="col-sm-12 col-md-12 col-lg-2 d-flex align-items-center justify-content-center rounded-0 bg-warning-subtle border p-3 border border-dark">
                                                    <span class="h1 fw-bold mb-0">S<br><span class="fs-5 fw-normal">SOLICITUD<span></span>

                                                </div>

                                                <div class="col-sm-12 col-md-12 col-lg-10">
                                                    <div class="row g-0 justify-content-start">
                                                        <div class="row g-0  justify-content-center">
                                                        <div class="col-md-9 d-flex align-items-center justify-content-start border p-2">
                                                            <span class="fs-5">${row.NumAgencia} - ${row.NomAgencia} - <b>${row.SolicitadoPor}</b></span>
                                                        </div>
                                                        <div class="col-md-3 d-flex align-items-center justify-content-center border p-2">
                                                            <span class="mb-0 fs-5">${row.Fecha}</span>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    <div class="row g-0 row-cols-2 d-flex justify-content-start">
                                                        <div
                                                        class="col-sm-6 col-md-9 col-lg-9 d-flex align-items-center justify-content-start border p-2">
                                                        <span class="fs-5">${row.Concepto} - @include('layouts.optionvercodigo')</span>
                                                        </div>
                                                        <div
                                                        class="col-sm-6 col-md-3 col-lg-3 d-flex align-items-center justify-content-center border p-3">
                                                        ${row.CodigoAutorizacion == "11K" ?
                                                        `<span class="fs-5 fw-bold mb-0">${row.Convencion}</span>`:``
                                                        }
                                                        </div>
                                                    </div>
                                                    <div class="row g-0">
                                                        <div class="col-md-12 d-flex justify-content-start border p-2">
                                                            <span class="fs-5">${cedulaFormateada} -
                                                                ${row.CuentaAsociado == null ? `N/A`:`${row.CuentaAsociado}`} - ${row.NombrePersona}
                                                                ${(row.CodigoAutorizacion == '11A' || row.CodigoAutorizacion == '11D') ?
                                                                (row.Score >= 650 ?
                                                                    `- <span class="badge badge-pill badge-danger bg-success text-light fw-bold">${row.Score}</span> - ${estado}` :
                                                                    (row.Score === 'S/E' ? `- <span class="badge badge-pill badge-danger bg-warning text-dark fw-bold">${row.Score}</span>` : `- <span class="badge badge-pill badge-danger bg-danger text-light fw-bold">${row.Score}</span> - ${estado}`)
                                                                ) :
                                                                ``
                                                            }

                                                                </span>

                                                        </div>
                                                    </div>
                                                    <div class="row g-0">
                                                        <div class="col-sm-12 col-md-9 text-start border p-2 fs-5">
                                                                <span class="mb-0">${row.Detalle}</span>
                                                            </div>
                                                            <a href="Storage/files/soporteautorizaciones/${row.DocumentoSoporte}" target="__blank"
                                                            class="col-sm-12 col-md-3 d-flex align-items-center justify-content-center btn btn-outline-info rounded-0 p-3">
                                                                <span class="h1 fw-bold mb-0">
                                                                    <img src="img/pdf.png" style="height: 4.5rem">
                                                                </span>
                                                            </a>
                                                    </div>
                                                </div>
                                            </div>
                                            ${row.Estado != 6 && (row.NumAgencia !== "C1" && row.NumAgencia !== "C2" && row.NumAgencia !== "C3" && row.NumAgencia !== "C4" && row.NumAgencia !== "C5") ?
                                            `<form enctype="multipart/form-data" id="formValidarAutorizacion${row.IDAutorizacion}" data-id="${row.IDAutorizacion}">
                                                    @csrf
                                            <div class=" row g-0 text-center ">
                                                <div
                                                    class="col-sm-12 col-md-12 col-lg-2 d-flex  flex-column  align-items-center justify-content-center ${row.Aprobacion == 1 ?`bg-success-subtle`:row.Estado == 0 ?`bg-danger-subtle`:row.Estado == 1 ? `bg-success-subtle`: row.Estado == 3 ? `bg-info-subtle`:`bg-dark-subtle`} border p-1 border border-dark" id="fondo">

                                                    ${row.Aprobacion == 1 ?
                                                        `
                                                            <span class="h1 fw-bold mb-0">V<br><span class="fs-5 fw-normal">VALIDADO<span></span>
                                                        `:
                                                        `
                                                        ${row.Estado == 1 ?
                                                        ``:
                                                        row.Estado == 0 && row.ValidadoPor !== null ?
                                                        `<span class="h1 fw-bold mb-0">R<br><span class="fs-5 fw-normal">RECHAZADO<span></span>`:
                                                        `<label class="label mt-2">
                                                            <input value="1" type="radio" name="Estado" id="Estado">
                                                            <span>VALIDAR</span>
                                                        </label>`
                                                    }

                                                    ${row.Estado == 0 ?
                                                        ``:
                                                        row.Estado == 1 && row.ValidadoPor !== null ?
                                                        `<span class="h1 fw-bold mb-0">V<br><span class="fs-5 fw-normal">VALIDADO<span></span>`
                                                        :`<label class="label">
                                                            <input value="0" type="radio" name="Estado" id="Estado">
                                                            <span>RECHAZAR</span>
                                                        </label>`
                                                    }

                                                        `
                                                    }




                                                </div>

                                                <div class="col-sm-12 col-md-12 col-lg-10 h-100 d-flex flex-column">
                                                        <div class="row g-0 border">

                                                            <div class="text-start col-md-9 d-flex text-start border p-2 flex-grow-4">
                                                                <span class="fs-5 fw-bold mb-0">${row.Coordinacion} - ${row.ValidadoPor}</span>
                                                            </div>
                                                            <div class="col-md-3 d-flex align-items-center justify-content-center border p-3 ">
                                                                <span class="mb-0 fs-5">${row.FechaValidacion}</span>
                                                            </div>



                                                        </div>
                                                        <div class="row g-0">

                                                                <input class="fs-5 col-md-12 d-flex text-start border p-3 w-100" style="resize: horizontal;" id="Observaciones" name="Observaciones" onkeydown="return event.key != 'Enter';" placeholder="Escribe aquí tu Observación." ${row.Observaciones == null ?``:`value="${row.Observaciones}"`} required></input>


                                                        </div>
                                                </div>
                                            </div>
                                        </form>`:``
                                        }

                                            ${row.Aprobacion == 1 ?
                                                `<div class="row g-0 text-center">
                                                    ${row.Estado == 4 ?
                                                    `<div
                                                        class="col-sm-6 col-md-12 col-lg-2 d-flex align-items-center justify-content-center bg-success-subtle border p-3 border border-dark">
                                                        <span class="h1 fw-bold mb-0">A<br><span class="fs-5 fw-normal">APROBADO<span></span>
                                                    </div>`:
                                                    row.Estado == 5 ?
                                                    `<div
                                                        class="col-sm-6 col-md-12 col-lg-2 d-flex align-items-center justify-content-center bg-danger-subtle border p-3 border border-dark">
                                                        <span class="h1 fw-bold mb-0">R<br><span class="fs-5 fw-normal">RECHAZADO<span></span>
                                                    </div>`:
                                                    row.Estado == 3 ?
                                                    `<div
                                                        class="col-sm-12 col-md-12 col-lg-2 d-flex align-items-center justify-content-center bg-info-subtle border p-2 border border-dark" title="CORREGIR">
                                                        <span class="h1 fw-bold mb-0">C<br><span class="fs-5 fw-normal">CORREGIR<span></span>
                                                    </div>`:
                                                    ``
                                                    }
                                                    <div class="col-md-12 col-lg-10">
                                                        <div class="row g-0">
                                                            <div class="col-md-9 d-flex text-start border p-2">
                                                                <span class="fs-5 fw-bold mb-0">DIRECCION GENERAL</span>
                                                            </div>
                                                            <div class="col-md-3 border p-2">
                                                                <span class="mb-0 fs-5">${row.FechaAprobacion}</span>
                                                            </div>
                                                        </div>
                                                        ${row.Estado == 5 || row.Estado ==3 ?
                                                            `<div class="row g-0 border text-start p-2">
                                                                <p class="mb-0 fw-semibold fs-5">${row.ObservacionesGer}</p>
                                                            </div>`:
                                                            ``
                                                        }
                                                    </div>
                                                </div>`:``
                                            }

                                            </div>
                                            <div class=" text-center p-3">
                                                ${row.Estado == 3  ?
                                                    `<button id="boton${row.IDAutorizacion}" type="button" class="btn btn-outline-success fs-5 fw-bold w-50" name="btnregistrar" onclick="formValidarAutorizacion(${row.IDAutorizacion}, event)">GUARDAR</button></div>`:
                                                    (row.Estado == '1' || row.Estado == '2') && row.Validacion == '0' ?
                                                    `<button id="boton${row.IDAutorizacion}" type="button" class="btn btn-outline-success fs-5 fw-bold w-50" name="btnregistrar" onclick="formValidarAutorizacion(${row.IDAutorizacion}, event)">GUARDAR</button></div>`:``
                                                }
                                            </div>
                                        </div>
                                    </div>
                                </div>`;

                        return modalEditar;
                    },
                    orderable: false,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).css({
                            'text-align': 'center'
                        });
                    }
                }
            ],


            "lengthMenu": [
                [5],
                [5]
            ],
            "drawCallback": function(settings) {
                var api = this.api();
                var noRecordsMessage = api.table().container().querySelector('.dataTables_empty');
                if (noRecordsMessage) {
                    noRecordsMessage.style.textAlign = 'left';
                    noRecordsMessage.style.fontSize = '40px';
                    noRecordsMessage.style.fontWeight = 'bold';
                }
            },
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "<span style='font-size: 40px; text-align: left;'>No existen autorizaciones disponibles!</span>",
                "info": "Mostrando la página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(Filtrado de _MAX_ registros totales)",
                "search": "<span style='font-size: 20px; font-weight: bold'>Buscar:</span>",
                "paginate": {
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "initComplete": function(settings, json) {
                var buttonsHtml = '<div class="custom-buttons">' +
                    '<button id="btnT" class="custom-btn" title="ACTUALIZAR INFORMACIÓN"><i class="fa-solid fa-rotate-right"></i></button>' +
                    //   '<button id="btnFA" class="custom-btn" title="FALTA POR APROBAR">FA</button>' +
                    '</div>';
                $(buttonsHtml).prependTo('.dataTables_filter');
                $('#btnT').on('click', function() {
                    table.ajax.reload(null, false);

                });
            },
        });


        function csesion() {
            var respuesta = confirm("¿Estas seguro que deseas cerrar sesión?")
            return respuesta
        }

        function modal(documentoSoporte) {
            const doc = documentoSoporte;
            // alert(`Storage/files/soporteautorizaciones/${doc}`);
            const iframe = document.querySelector("#modalSoportePdf iframe");
            const url = `Storage/files/soporteautorizaciones/${doc}`;
            iframe.src = url;
            $('#modalSoportePdf').modal('show');
        }


    </script>
    <script>
        function formValidarAutorizacion(id, event) {

            var form = $("#formValidarAutorizacion" + id);
            // Verificar si el formulario ya ha sido enviado
            if (form.data('submitted')) {
                // Si el formulario ya ha sido enviado, no hacer nada
                return;
            }

            // Marcar el formulario como enviado
            form.data('submitted', true);

            var formDataArray = form.serializeArray();

            // Almacenar los valores en variables
            var estado, observaciones;


            // Recorrer el array de objetos y asignar valores a las variables según el nombre del campo
            formDataArray.forEach(function(input) {
                if (input.name === "Estado") {
                    estado = input.value;
                } else if (input.name == "Observaciones") {
                    observaciones = input.value;
                    event.preventDefault();
                }

            });
            console.log(estado + ' ' + observaciones);
            if (typeof estado === 'undefined') {
                // Mostrar un mensaje de error o resaltar los campos de estado
                alert('Por favor, seleccione un estado.');

                // Permitir que el formulario se envíe nuevamente
                form.data('submitted', false);

                return;
            }

            // Realizar la solicitud AJAX para actualizar la autorización
            $.ajax({
                url: "{{ route('updategercoordi.autorizacion', ['id' => ':id']) }}".replace(':id', id),
                type: "POST",
                data: {
                    Observaciones: observaciones,
                    Estado: estado,
                    _token: $('input[name="_token"]').val()
                },
                success: function(response) {
                    if (response) {
                        $(`#exampleModal_${id}`).modal('hide');
                        console.log('¡Éxito!');
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: "¡ACTUALIZADO!",
                            html: "<span class='fw-semibold'>Se actualizó correctamente la autorización No. <span class='badge bg-primary fw-bold'>" +
                                id + "</span></span>",
                            confirmButtonColor: '#646464'
                        });
                    }
                },
                error: function(error) {
                    console.log('Error');
                }
            });
            }


        //PARA QUE CUANDO DE ENTER NO SE ACTUALICE LA PAG
        $(document).on('keypress', 'input[name="Observaciones"]', function(e) {
            // Verificar si la tecla presionada es Enter (código 13)
            if (e.which == 13) {
                e
                    .preventDefault(); // Evitar el comportamiento predeterminado de presionar Enter (recargar la página)
                var id = $(this).closest('form').data('id'); // Obtener el ID de la autorización
                formEditarAutorizacion(id,
                    e); // Llamar a la función para manejar la actualización de la autorización
            }
        });
    </script>

    </div>

    </div>
    {{-- ESTILOS --}}
    <style>
.input-group-text {
                position: relative; /* Añade posicionamiento relativo */
            }

            .tooltip1:hover::after {
                content: "Cédula / NIT";
                position: absolute;
                bottom: calc(100% + 5px); /* Cambia la posición a la parte superior */
                left: 50%;
                transform: translateX(-50%);
                padding: 5px;
                background-color: rgba(0, 0, 0, 0.8);
                color: white;
                border-radius: 5px;
                font-size: 14px;
            }

            .tooltip2:hover::after {
                content: "Cuenta";
                position: absolute;
                bottom: calc(100% + 5px); /* Cambia la posición a la parte superior */
                left: 50%;
                transform: translateX(-50%);
                padding: 5px;
                background-color: rgba(0, 0, 0, 0.8);
                color: white;
                border-radius: 5px;
                font-size: 14px;
            }

            .tooltip3:hover::after {
                content: "Nombre / Nombre Empresa";
                position: absolute;
                bottom: calc(100% + 5px); /* Cambia la posición a la parte superior */
                left: 50%;
                transform: translateX(-50%);
                padding: 5px;
                background-color: rgba(0, 0, 0, 0.8);
                color: white;
                border-radius: 5px;
                font-size: 14px;
            }

            .tooltip4:hover::after {
                content: "Convención";
                position: absolute;
                bottom: calc(100% + 5px); /* Cambia la posición a la parte superior */
                left: 50%;
                transform: translateX(-50%);
                padding: 5px;
                background-color: rgba(0, 0, 0, 0.8);
                color: white;
                border-radius: 5px;
                font-size: 14px;
            }


        .input {
            max-width: 190px;
            display: none;
        }

        .labelFile {
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 250px;
            height: 100px;
            border: 2px dashed #ccc;
            align-items: center;
            text-align: center;
            padding: 5px;
            color: #404040;
            cursor: pointer;
        }

        #uploadMessage {
            display: none;
            color: green;
            font-weight: bold;
        }

        .custom-buttons {
            display: absolute;
            margin-right: 10px;
        }

        .custom-btn {
            background-color: #646464;
            font-weight: bold;
            font-size: 20px;
            color: white;
            padding: 5px 10px;
            margin: 2px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .custom-btn:hover {
            background-color: #aeaeae;
        }

        .label {
            cursor: pointer;
            font-weight: 500;
            position: relative;
            overflow: hidden;
            margin-bottom: 0em;
            font-size: 15px

        }

        .label input {
            position: absolute;
            left: -9999px;
        }

        .label input:checked+span {
            background-color: #646464;
            color: white;
        }

        .label input:checked+span:before {
            box-shadow: inset 0 0 0 0.4375em #393939;
        }

        .label span {
            display: flex;
            align-items: center;
            padding: 0.375em 0.75em 0.375em 0.375em;
            border-radius: 99em;
            transition: 0.25s ease;
            color: #646464;
            font-weight: bold;
        }

        .label span:hover {
            background-color: #d6d6e5;
        }

        .label span:before {
            display: flex;
            flex-shrink: 0;
            content: "";
            background-color: #fff;
            width: 1.5em;
            height: 1.5em;
            border-radius: 50%;
            margin-right: 0.375em;
            transition: 0.25s ease;
            box-shadow: inset 0 0 0 0.125em #393939;
        }

        .input {
            width: 100%;
            height: 52px;
            padding: 12px;
            border-radius: 12px;
            border: 1.5px solid lightgrey;
            outline: none;
            transition: all 0.3s cubic-bezier(0.19, 1, 0.22, 1);
            box-shadow: 0px 0px 20px -18px;
        }

        .input:hover {
            border: 2px solid lightgrey;
            box-shadow: 0px 0px 20px -17px;
        }

        .input:active {
            transform: scale(0.95);
        }

        .input:focus {
            border: 2px solid grey;
        }


        .badge {
            display: inline-block;
            padding: 5px 10px;
            font-size: 20px;
            font-weight: 500;
            color: white;
            background-color: #28a745;
            /* Verde de Bootstrap para éxito */
            border-radius: 10px;
            /* Ajusta según lo que prefieras */
            transition: background-color 0.3s ease;
        }

        .badge:hover {
            background-color: #218838;
            /* Cambia el tono de verde al pasar el mouse */
            cursor: pointer;
            /* Cambia el cursor al pasar el mouse */
        }


        .tooltip-container {
            position: relative;
            display: inline-block;
            margin: 0px;
            }


            .col::-webkit-scrollbar {
            width: 10px;  /*Ancho de la barra de desplazamiento */
            }

            .col::-webkit-scrollbar-track {
            background: #f1eeed; /*Color de fondo de la barra de desplazamiento */
            }

            .col::-webkit-scrollbar-thumb {
            background: #bea232;  /*Color del botón de desplazamiento */
            }




            .text {
            color: #333;
            font-size: 18px;
            cursor: pointer;
            }

            .tooltip {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            visibility: hidden;
            background: #898989;
            color: #fff;
            font-weight: bold;
            padding: 7px;
            border-radius: 4px;
            transition: opacity 0.3s, visibility 0.3s, top 0.3s, background 0.3s;
            z-index: 1;
            width: 500px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);

            }

            .tooltip::before {
            content: "";
            position: absolute;
            bottom: 100%;
            left: 50%;
            border-width: 8px;
            border-style: solid;
            border-color: transparent transparent #898989 transparent;
            transform: translateX(-50%);

            }

            .tooltip-container:hover .tooltip {
            top: 120%;
            opacity: 1;
            visibility: visible;
            background: #898989;
            transform: translate(-50%, 0px);

            }
    </style>
    </div>

    @include('layouts.footer')

</body>

</html>
