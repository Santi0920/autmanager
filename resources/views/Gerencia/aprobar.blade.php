<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Autorizaciones</title>

    <!-- Fonts -->
    <link href="ResourcesAll/Bootstrap/Bootstrap.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/logoo.png" type="image/png">
    <script src="ResourcesAll/jquery/jquery-3.6.0.js"></script>
    <script src="ResourcesAll/jquery/jquery-ui.js"></script>

    <script src="ResourcesAll/fontawesome/fontawesome.js"></script>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <script src="ResourcesAll/Sweetalert/sweetalert2.js"></script>
    <link rel="stylesheet" href="ResourcesAll/Sweetalert/sweetalert2.css">
    <link rel="stylesheet" href="ResourcesAll/Bootstrap/Bootstrap2.css">
    <link rel="stylesheet" href="ResourcesAll/Bootstrap/dataTablesbootstrap5.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>

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
                <div class="" style="margin-top: 8px; margin-right: -14px;">

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

    <script>
        var table = $('#personas').DataTable({
            "ajax": "{{ route('datager.solicitudes') }}",
            "order": [
                [0, 'desc']
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
                            `<span class='badge bg-secondary fw-bold'>${row.CodigoAutorizacion}</span> - ${row.Concepto}</span>`

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
                        if (row.Estado == 1) {
                            var Estado = `<div class="btn btn-warning shadow" style="padding: 0.4rem 1.4rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">
                                <i class="fa-solid fa-check"></i> VALIDADO</label></div>`
                        } else if (row.Estado == 4) {
                            var Estado =
                                '<div class="btn btn-success blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">APROBADO</div>'
                        } else {
                            var Estado =
                                '<div class="btn btn-info shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">SOLICITUD DE COORDINACIÓN</div>'
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

                        const fecha = row
                            .Fecha; // Suponiendo que row.Fecha contiene la fecha en formato de cadena

                        // Obtener las primeras dos letras de la fecha
                        let primerasDosLetras = fecha.substring(0, 19);

                        // Convertir la primera letra a mayúscula
                        primerasDosLetras = primerasDosLetras.charAt(0).toUpperCase() + primerasDosLetras
                            .slice(1);

                        let fechaValidacion = "";
                        if (row.FechaValidacion !== null) {
                            fechaValidacion = row.FechaValidacion.substring(0,
                                19); // Obtener los primeros 19 dígitos
                            fechaValidacion = fechaValidacion.charAt(0).toUpperCase() + fechaValidacion
                                .slice(1); // Convertir la primera letra a mayúscula
                        }

                        const cedula = row.Cedula;
                        const cedulaFormateada = new Intl.NumberFormat().format(cedula);



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
                                                            `<button class="btn btn-danger shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">A - ANULADO</button>` :
                                                            row.Estado == 1 || row.Estado == 2 ?
                                                            `<button class="btn btn-success shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">V - VALIDADO</button>` :
                                                            row.Estado == 3 ?
                                                            `<button class="btn btn-primary shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">C - CORREGIR</button>` :
                                                            row.Estado == 4 ?
                                                            `<button class="btn btn-success  shadow blink" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">AP - APROBADO</button>` :
                                                            row.Estado == 5 ?
                                                            `<button class="btn btn-danger shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">R - RECHAZADO POR GERENCIA</button>` :
                                                            '<button class="btn btn-info shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">T - SOLICITUD DE COORDINACIÓN</button>'
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
                                                        <span class="mb-0 fs-5">${primerasDosLetras}</span>
                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="row g-0 row-cols-2 d-flex justify-content-start">
                                                    <div
                                                    class="col-sm-6 col-md-9 col-lg-9 d-flex align-items-center justify-content-start border p-2">
                                                    <span class="fs-5">${row.CodigoAutorizacion} - ${row.Concepto}</span>
                                                    </div>
                                                    <div
                                                    class="col-sm-6 col-md-3 col-lg-3 d-flex align-items-center justify-content-center border p-3">
                                                    ${row.CodigoAutorizacion == "19B" ?
                                                    `<span class="fs-5 fw-bold mb-0">${row.Convencion}</span>`:``
                                                    }
                                                    </div>
                                                </div>
                                                <div class="row g-0">
                                                    <div class="col-md-12 d-flex justify-content-start border p-2">
                                                        <span class="fs-5">${cedulaFormateada}
                                                            ${row.CodigoAutorizacion === '11D' || row.CodigoAutorizacion == '11G' ?
                                                            `- ${row.CuentaAsociado} `
                                                            : ``}- ${row.NombrePersona}</span>

                                                    </div>
                                                </div>
                                                <div class="row g-0">
                                                    <div class="col-sm-12 col-md-9 text-start border p-2 fs-5">
                                                            <span class="mb-0">${row.Detalle}</span>
                                                        </div>
                                                        <button type="button" id="soportePDF" onclick="modal('${row.DocumentoSoporte}')"
                                                        class="col-sm-12 col-md-3 d-flex align-items-center justify-content-center btn btn-outline-info rounded-0 p-3" data-bs-target="#pdf_${id}"
                                                        data-id="${id}">
                                                            <span class="h1 fw-bold mb-0">
                                                                <img src="img/pdf.png" style="height: 4.5rem">
                                                            </span>
                                                        </button>


                                                </div>
                                            </div>
                                        </div>
                                        <div class=" row g-0 text-center ">
                                            ${row.Estado ==6 && row.Validacion == 1 ?
                                            ``:
                                            `<div
                                                                class="col-sm-12 col-md-12 col-lg-2 d-flex  flex-column  align-items-center justify-content-center ${row.Estado == 0 ?`bg-danger-subtle`:row.Estado == 1 ? `bg-success-subtle`: row.Estado == 3 ? `bg-info-subtle`:`bg-dark-subtle`} border p-1 border border-dark" id="fondo">

                                                                <span class="h1 fw-bold mb-0">V<br><span class="fs-5 fw-normal">VALIDADO<span></span>




                                                            </div>

                                                            <div class="col-sm-12 col-md-12 col-lg-10 h-100 d-flex flex-column">
                                                                    <div class="row g-0 border ">

                                                                        <div class="text-start col-md-9 d-flex border p-2 flex-grow-4">
                                                                            <span class=" fs-5 fw-bold mb-0">${row.Coordinacion} - ${row.ValidadoPor}</span>
                                                                        </div>
                                                                        <div class="col-md-3 d-flex align-items-center justify-content-center border p-3 ">
                                                                            <span class="mb-0 fs-5">${fechaValidacion}</span>
                                                                        </div>



                                                                    </div>
                                                                    <div class="row g-0">

                                                                            <div class="text-start fs-5 col-md-12 d-flex border p-3 w-100 text-center" style="resize: horizontal;" id="Observaciones" name="Observaciones" onkeydown="return event.key != 'Enter';">
                                                                                <span>${row.Observaciones == null ?
                                                                                `Ninguna.`:`${row.Observaciones}`
                                                                                }</span>
                                                                            </div>


                                                                    </div>
                                                            </div>`}
                                        </div>
                                        <form enctype="multipart/form-data" id="formEditarAutorizacion${row.IDAutorizacion}" data-id="${row.IDAutorizacion}">
                                                @csrf
                                            <div class="row g-0 text-center">
                                                <div
                                                    class="col-sm-6 col-md-12 col-lg-2 d-flex flex-column align-items-center justify-content-center bg-dark-subtle border p-3 border border-dark">
                                                    <label class="label">
                                                        <input value="4" type="radio" name="Estado" id="Estado" required>
                                                        <span>APROBAR</span>
                                                    </label>
                                                    <label class="label">
                                                        <input value="5" type="radio" name="Estado" id="Estado" required>
                                                        <span>RECHAZAR</span>
                                                    </label>
                                                    <label class="label">
                                                        <input value="3" type="radio" name="Estado" id="Estado" required>
                                                        <span>CORREGIR</span>
                                                    </label>

                                                </div>
                                                <div class="col-md-12 col-lg-10">
                                                    <div class="row g-0">
                                                        <div class="col-md-9 d-flex text-start border p-3">
                                                            <span class="fs-5 fw-bold mb-0">DIRECCION GENERAL</span>
                                                        </div>
                                                        <div class="col-md-3 border p-2">
                                                            <span class="mb-0 fs-5">${row.FechaAprobacion == null ? `Pendiente...`:`${row.FechaAprobacion}`}</span>
                                                        </div>
                                                    </div>

                                                        <input class="row g-0 border text-start p-4 mb-0 fw-semibold fs-5 w-100" id="Observaciones" name="Observaciones" onkeydown="return event.key != 'Enter';" placeholder="Escribe aquí tu Observación." ${row.ObservacionesGer == null ?``:`value="${row.ObservacionesGer}"`}>
                                                        </input>

                                                </div>
                                            </div>
                                            </form>

                                        </div>
                                        <div class=" text-center p-3">
                                            <button id="boton${row.IDAutorizacion}" type="button" class="btn btn-outline-success fs-5 fw-bold w-50" name="btnregistrar" onclick="formEditarAutorizacion(${row.IDAutorizacion}, event)">GUARDAR</button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="modal fade" id="modalSoportePdf" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title" id="exampleModalLongTitle"
                                            style="color: #646464;font-weight: 700;font-size: 22px">PDF</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-0">
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe class="embed-responsive-item" src="Storage/files/soporteautorizaciones/${row.DocumentoSoporte}" frameborder="0" style="width:90%; height: 680px;"></iframe>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary fs-5" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            `;

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
            $('#modalSoportePdf').modal('show');
        }


    </script>
    <script>
        function formEditarAutorizacion(id, event) {

            var form = $("#formEditarAutorizacion" + id);

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
                } else if (input.name === "Observaciones") {
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
                url: "{{ route('updateger.autorizacion', ['id' => ':id']) }}".replace(':id', id),
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
                        $('#personas').DataTable().ajax.reload();
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

    <script></script>


    </div>

    </div>
    <style>
        .custom-buttons {
            display: inline-block;
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
    </style>
    </div>

    @include('layouts.footer')

</body>

</html>
