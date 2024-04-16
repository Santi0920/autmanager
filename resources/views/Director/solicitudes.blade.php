<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Autorizaciones</title>

    <!-- Fonts -->
    <link href="ResourcesAll/Bootstrap/Bootstrap.css" rel="stylesheet">
    <link type="text/css" href="css/datacredito.css" rel="stylesheet">
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
                    confirmButtonColor: '#005E56'
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
                    confirmButtonColor: '#005E56',
                    timer: 10000

                });
            </script>
        </div>
    @endif
    <div class="container-fluid row p-4">
        <form action="{{ route('solicitar.autorizacion') }}" class="col m-3" method="POST"
            enctype= "multipart/form-data" id="pagare">
            @csrf
            <h2 class="p-2 text-secondary text-center"><b>Solicitar Autorización</b></h2>

            <div class="mb-3 w-100" title="Este campo es obligatorio" id="id">
                <label for="input1" class="form-label col-form-label-lg fw-semibold">TIPO DE AUTORIZACIÓN <span
                        class="text-danger" style="font-size:20px;">*</span></label>
                <select class="form-select form-select-lg " name="tautorizacion" id="autorizaciones" required>
                    <option selected disabled>Selecciona una opción</option>
                    {{-- <option disabled class="fw-bold">---TALENTO HUMANO---</option>
                        @foreach ($user as $autorizacion)
                            @if ($autorizacion->No == 10)
                                <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                                    {{ $autorizacion->No . $autorizacion->Letra }} -
                                    {{ $autorizacion->Concepto }}
                                </option>
                            @endif
                        @endforeach --}}
                    <option disabled class="fw-bold">---COORDINACION---</option>
                    @foreach ($user as $autorizacion)
                        @if ($autorizacion->No == 11)
                            <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                                {{ $autorizacion->No . $autorizacion->Letra }} -
                                {{ $autorizacion->Concepto }}
                            </option>
                        @endif
                    @endforeach
                    <option disabled class="fw-bold">---SISTEMAS---</option>
                    @foreach ($user as $autorizacion)
                        @if ($autorizacion->No == 19)
                            <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                                {{ $autorizacion->No . $autorizacion->Letra }} -
                                {{ $autorizacion->Concepto }}
                            </option>
                        @endif
                    @endforeach
                    {{-- <option disabled class="fw-bold">---JURIDICO ZONA CENTRO---</option>
                        @foreach ($user as $autorizacion)
                            @if ($autorizacion->No == 2150)
                                <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                                    {{ $autorizacion->No . $autorizacion->Letra }} -
                                    {{ $autorizacion->Concepto }}
                                </option>
                            @endif
                        @endforeach
                        <option disabled class="fw-bold">---JURIDICO ZONA NORTE---</option>
                        @foreach ($user as $autorizacion)
                            @if ($autorizacion->No == 2250)
                                <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                                    {{ $autorizacion->No . $autorizacion->Letra }} -
                                    {{ $autorizacion->Concepto }}
                                </option>
                            @endif
                        @endforeach
                        <option disabled class="fw-bold">---JURIDICO ZONA SUR---</option>
                        @foreach ($user as $autorizacion)
                            @if ($autorizacion->No == 2350)
                                <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                                    {{ $autorizacion->No . $autorizacion->Letra }} -
                                    {{ $autorizacion->Concepto }}
                                </option>
                            @endif
                        @endforeach
                        <option disabled class="fw-bold">---TESORERIA---</option>
                        @foreach ($user as $autorizacion)
                            @if ($autorizacion->No == 1500)
                                <option class="fw-semibold" value="{{ $autorizacion->No . $autorizacion->Letra }}">
                                    {{ $autorizacion->No . $autorizacion->Letra }} -
                                    {{ $autorizacion->Concepto }}
                                </option>
                            @endif
                        @endforeach --}}
                </select>
            </div>

            <div id="cuerpo"></div>

        </form>


        {{-- FECHA --}}
        <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9 col-xxl-9">
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
            <div class="table-responsive">
                <table id="personas" class="hover table table-striped shadow-lg mt-4 table-hover table-bordered">
                    <thead style="background-color: #646464;">
                        <tr class="text-white">
                            <th scope="col" class="text-center">#</th>
                            <th scope="col" class="text-center" style="width: 10%">CÉDULA</th>
                            <th scope="col" class="text-center" style="width: 35%">CONCEPTO</th>
                            <th scope="col" class="text-center">FECHA SOLICITUD</th>
                            <th scope="col" class="text-center" style="width: 20%">ESTADO</th>
                            <th scope="col" class="text-center" style="width: 13%">DETALLE</th>
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
                "ajax": "{{ route('data.solicitudes') }}",
                "order": [
                    [0, 'desc']
                ],

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
                        data: 'Cedula',
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).css({
                                'font-weight': '500',
                                'font-size': '20px',
                                'text-align': 'center'
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
                        data: 'Fecha',
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).css({
                                'font-weight': '500',
                                'font-size': '20px',
                                'text-align': 'center'
                            });
                        }
                    },
                    {
                        data: 'Estado',
                        render: function(data, type, row) {
                            // Supongo que deseas mostrar el ID, no un botón de Aprobado, por lo que he cambiado el nombre de la variable a 'IDLabel'
                            if (row.Estado == 0) {
                                var Estado =
                                    '<div class="btn btn-danger shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%;font-weight: 600;font-size: 14px;">ANULADO</div>';
                            } else if (row.Estado == 1 || row.Estado == 2) {
                                var Estado =
                                    `<div class="btn btn-warning shadow" style="padding: 0.4rem 1.4rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">EN TRAMITE</div>`
                            } else if (row.Estado == 3) {
                                var Estado =
                                    '<div class="btn btn-primary shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">CORREGIR</div>'
                            } else if (row.Estado == 4) {
                                var Estado =
                                    '<div class="btn btn-success blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">APROBADO POR GERENCIA</div>'
                            } else {
                                var Estado =
                                    '<div class="btn btn-danger shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">ANULADO POR GERENCIA</div>'
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
                        data: 'IDAutorizacion',
                        render: function(data, type, row) {

                            //para traer por quien fue el que valido COORDINACION
                            if (row.Validacion == 1) {
                                var validadopor = `<th scope="row" class="text-start">VALIDADO POR:</th>
                                                                <td id="aprobado_por" class="fw-bold text-primary">${row.ValidadoPor}</td>
                                                            </tr>`
                            } else {
                                var validadopor = `<th scope="row" class="text-start">VALIDADO POR:</th>
                                                                <td id="aprobado_por" class="fw-bold text-dark">Pendiente...</td>
                                                            </tr>`
                            }

                            //añadir observaciones
                            if (row.Observaciones !== "Ninguna.") {
                                var Observaciones = `<th scope="row"  class="text-start">OBSERVACIONES:</th>
                                                                <td id="aprobado_por" class="fw-semibold">${row.Observaciones}</td>
                                                            </tr>`
                            } else {
                                var Observaciones = `
                                                            <tr>
                                                                <th scope="row"  class="text-start">OBSERVACIONES:</th>
                                                                <td id="aprobado_por" class="fw-semibold">Ninguna.</td>
                                                            </tr>`
                            }



                            //TRAE LA CUENTA SI ES 11D QUE ES AUTORIZACION POR SCORE BAJO CREDITO
                            if (row.CodigoAutorizacion == '11D') {
                                var cuenta = `<th scope="row"  class="text-start">CUENTA:</th>
                                                                <td id="aprobado_por" class="text-dark">${row.CuentaAsociada}</td>
                                                            </tr>`
                            } else {
                                var cuenta = ``
                            }

                            //para traer el score en badge Y SI SON LOS CODIGOS DE ABAJO QUE NO TRAIGA SCORE
                            if (row.CodigoAutorizacion == '11B' || row.CodigoAutorizacion == '11G' || row
                                .CodigoAutorizacion == '19B') {
                                var score = ``
                            } else {
                                if (row.Score == 'S/E') {
                                    //SIN EXPERIENCIA
                                    var score =
                                        `
                                        <tr>
                                            <th scope="row" class="text-start">SCORE:</th>
                                            <td id="observacion"><div class="btn btn-warning" title="sin experiencia" style="padding: 0.3rem 1.3rem; border-radius: 10%;font-weight: 600;font-size: 15px;"><label style="margin-bottom: 0px;">${row.Score}</label></div></td>
                                            </tr>
                                        <tr>
                                       `
                                } else if (row.Score >= 650) {
                                    //NORMAL
                                    var score =
                                        `
                                        <tr>
                                            <th scope="row" class="text-start">SCORE:</th>
                                            <td id="observacion"><div class="btn btn-success" style="padding: 0.3rem 1.3rem; border-radius: 10%;font-weight: 600;font-size: 17px;"><label style="margin-bottom: 0px;">${row.Score}</label></div></td>
                                            </tr>
                                        <tr>
                                        `

                                } else {
                                    //BAJO
                                    var score =
                                        `
                                        <tr>
                                            <th scope="row" class="text-start">SCORE:</th>
                                            <td id="observacion"><div class="btn btn-danger" style="padding: 0.3rem 1.3rem; border-radius: 10%;font-weight: 600;font-size: 15px;"><label style="margin-bottom: 0px;">${row.Score}</label></div></td>
                                            </tr>
                                        <tr>

                                        `
                                }
                            }

                            //AQUI TRAE EL ESTADO EN EL MODAL
                            if (row.Estado == 0) {
                                var Estado =
                                    '<div class="btn btn-danger" style="padding: 0.4rem 1.7rem; border-radius: 10%;font-weight: 600;font-size: 14px;">ANULADO</div>';
                            } else if (row.Estado == 1 || row.Estado == 2) {
                                var Estado =
                                    `<div class="btn btn-warning shadow" style="padding: 0.4rem 1.4rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">EN TRAMITE</div>`
                            } else if (row.Estado == 3) {
                                var Estado =
                                    '<div class="btn btn-primary" style="padding: 0.4rem 1.5rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">CORREGIR</div>'
                            } else if (row.Estado == 4) {
                                var Estado =
                                    '<div class="btn btn-success" style="padding: 0.4rem 1.7rem; border-radius: 10%;font-weight: 600;font-size: 14px;">APROBADO POR GERENCIA</div>';

                            } else if (row.Estado == 5) {
                                var Estado =
                                    '<div class="btn btn-danger" style="padding: 0.4rem 1.7rem; border-radius: 10%;font-weight: 600;font-size: 14px;">ANULADO POR GERENCIA</div>';

                            }



                            var id = row.IDAutorizacion; // Obtener el ID de la fila
                            var url = "{{ route('update.autorizacion', ':id') }}";
                            url = url.replace(':id', id);

                            //PARA EDITAR
                            if (row.Estado == 3) {
                                var detalleEditar = `
                                            <form enctype="multipart/form-data" id="formEditarAutorizacion${row.IDAutorizacion}">
                                            @csrf
                                            <input class="input text-center" name="Detalle" id="Detalle" type="text" value="${row.Detalle}" onkeydown="disableEnterKey(event)"></input>


                                            <tr>
                                                <th scope="row"  class="text-start">SOPORTE:</th>
                                                <td id="tipo">
                                                    <input type="file" class="form-control" name="Soporte" id="SoporteScore" accept=".pdf">
                                                </td>
                                            </tr>
                                            </form>

                                            `
                                var boton = `
                                <div class="text-center">

                                    <button id="boton${row.IDAutorizacion}" type="button" class="btn btn-primary fs-5 fw-bold" name="btnregistrar" onclick="formEditarAutorizacion(${row.IDAutorizacion}, event)"
                                                style="background-color: #646464;">GUARDAR</button>
                                    </div>
                                    `
                            } else {
                                var detalleEditar = `${row.Detalle}
                                <tr>
                                            <th scope="row"  class="text-start">SOPORTE:</th>
                                            <td id="tipo"><a href="Storage/files/soporteautorizaciones/${row.DocumentoSoporte}" download><img src="img/pdf.png" style="height: 2.5rem"></a></td>
                                        </tr>`
                                var boton = ``
                            }

                            //nombre DE LA PERSONA O SI NO EXISTE EN DATACRÉDTIO ES VACIO
                            if (row.CodigoAutorizacion == '11B' || row.CodigoAutorizacion == '11G' || row
                                .CodigoAutorizacion == '19B') {
                                var nombre = ``
                            } else {
                                var nombre =
                                    `<tr>
                                            <th scope="row" class="text-start">NOMBRE:</th>
                                            <td id="fe_ho_so">${row.Nombre} ${row.Apellidos}</td>
                                        </tr>`

                            }


                            //ESTADO DE CUENTA EN COOPSERP
                            if (row.CodigoAutorizacion == '11B' || row.CodigoAutorizacion == '11G') {
                                var estadocuenta = `
                                <tr>
                                    <th scope="row" class="text-start">ESTADO CUENTA:</th>
                                    <td>${row.Cedula}</td>
                                </tr>
                                `
                            } else {
                                var estadocuenta = ``
                            }



                            var modalEditar = `
                                        <a type="button" type="submit" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                                            <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                                        </svg>
                                        </a>

                                        <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" id="modalLink_${id}" data-bs-toggle="modal" data-bs-target="#exampleModal_${id}" data-id="${id}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" color="black" class="bi bi-eye" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                            </svg> Ver detallado
                                            </a>
                                        </li></ul>


                                        {{-- MODAL --}}
                                        <div class="modal fade bd-example-modal-lg" id="exampleModal_${id}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-width: 700px;">
                                                <div class="modal-content" style="padding: 2% 5% 5% 5%">
                                                    <div class="modal-header text-center">
                                                        <h6 class="modal-title" id="exampleModalLongTitle" style="color: #005E56;font-weight: 700;font-size: 22px">DETALLES AUTORIZACIÓN</h6>
                                                        <button type="button" class="btn-close fs-5" data-bs-dismiss="modal" aria-label="Close" style="outline: none; border: none; font-size:18px">
                                                        </button>
                                                    </div>

                                                    <div class="d-flex position-relative" style="max-height: 1000px; overflow-y: auto;">
                                                        <div style="margin-top: 4%;width: 100%;">
                                                            <table class="table table-bordered" style="color: #111 !important; font-size: 17px;">

                                                            <tbody>
                                                            <tr>
                                                            <th scope="row" style="width: 50%;"  class="text-start">CONSECUTIVO:</th>
                                                            <td id="" style="font-size: 30px" class="text-danger">${id}</td>
                                                            <input class="input text-center"  name="id" id="id" type="text" value="${row.IDAutorizacion}" style="display: none"></input>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row"  class="text-start">AGENCIA:</th>
                                                                <td id="tipo">${row.NumAgencia} - ${row.NomAgencia}</td>
                                                                <input class="d-none" name="cedulaNone" value="${row.Cedula}">
                                                            </tr>
                                                            <tr>
                                                            <th scope="row" class="text-start">CODIGO:</th>
                                                            <td id="tipo">${row.CodigoAutorizacion}</td>
                                                            </tr>
                                                            <tr>
                                                            <th scope="row" class="text-start">CONCEPTO:</th>
                                                            <td id="motivo">${row.Concepto}</td>
                                                            </tr>
                                                            <tr>
                                                            <th scope="row" class="text-start">FECHA DE LA SOLICITUD:</th>
                                                            <td id="fe_ho_in">${row.Fecha}</td>
                                                            </tr>
                                                            <tr>
                                                            <th scope="row" class="text-start">CÉDULA</th>
                                                            <td id="fe_ho_fi">${row.Cedula}</td>
                                                            </tr>

                                                            ` + estadocuenta + nombre + cuenta + score + `


                                                            <tr>
                                                            ` + validadopor + `
                                                            <th scope="row" class="text-start">ESTADO:</th>
                                                                        <td id="estado" style="text-align: center">` +
                                Estado + `</</td>
                                                            </tr>
                                                            <th scope="row" class="text-start">DETALLE:</th>
                                                            <td id="estado" style="text-align: center">` +
                                detalleEditar + `</td>
                                                            </tr>
                                                            ` + Observaciones + `

                                                        </tbody>
                                                        </table>
                                                        ` + boton + `
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        </div>`;

                            return modalEditar;


                        },
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
                    "zeroRecords": "<span style='font-size: 40px; text-align: left;'>No existen Autorizaciones Disponibles!</span>",
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

                // Crear un objeto FormData para enviar los datos del formulario
                var formData = new FormData(form[0]);

                // Obtener el archivo seleccionado
                var fileInput = $('input[name="Soporte"]')[0].files[0];

                // Agregar el archivo al objeto FormData
                formData.append('Soporte', fileInput);

                // Agregar el Detalle y el token al objeto FormData
                formData.append('Detalle', $('input[name="Detalle"]').val());
                formData.append('_token', $('input[name="_token"]').val());

                // Realizar la solicitud AJAX para actualizar la autorización
                $.ajax({
                    url: "{{ route('updatecoor.autorizacion', ['id' => ':id']) }}".replace(':id', id),
                    type: "POST",
                    data: formData,
                    processData: false, // Evitar que jQuery procese los datos
                    contentType: false, // Evitar que jQuery establezca el tipo de contenido
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

            function disableEnterKey(event) {
                if (event.key === "Enter") {
                    event.preventDefault(); // Prevenir la acción predeterminada de la tecla "Enter"
                }
            }
        </script>
        <script>
            $('#autorizaciones').on('change', function() {
                // Obtener el valor seleccionado
                var valorSeleccionado = $(this).val();
                console.log("Valor seleccionado:", valorSeleccionado);

                if (valorSeleccionado == "11A" || valorSeleccionado == "11D") {
                    $("#cuerpo").html(`
                        <div class="mb-3 w-100" title="Este campo es obligatorio" id="id">
                            <label for="input1" class="form-label col-form-label-lg fw-semibold">CÉDULA <span class="text-danger"
                                    style="font-size:20px;">*</span></label>
                            <input type="number" name="cedula" class="form-control form-control-lg" id="input1" autocomplete="off" autofocus
                                required>

                        </div>


                        <div class="mb-3 w-100" title="Este campo es obligatorio">
                            <label for="input2" class="form-label col-form-label-lg fw-semibold">DETALLES DE LA AUTORIZACIÓN <span
                                    class="text-danger" style="font-size:20px;">*</span></label>
                            <textarea type="number" name="detalle" class="form-control form-control-lg" autocomplete="off" required></textarea>

                        </div>
                        <div class="mb-4 w-100" style="">
                            <label for="exampleInputEmail1" class="form-label col-form-label-lg fw-semibold">ADJUNTAR SOPORTE<span
                                class="text-danger" style="font-size:20px;"> *</span></label>
                            <input type="file" class="form-control" name="SoporteScore" id="SoporteScore" required>
                        </div>

                        <div class="text-center">
                            <button id="agregar" type="submit" class="btn btn-primary fs-4 fw-bold" name="btnregistrar"
                                style="background-color: #646464;">SOLICITAR</button>
                        </div>
                        `);
                } else if (valorSeleccionado == "11G") {
                    $("#cuerpo").html(`
                        <div class="mb-3 w-100" title="Este campo es obligatorio" id="id">
                            <label for="input1" class="form-label col-form-label-lg fw-semibold">CÉDULA <span class="text-danger"
                                    style="font-size:20px;">*</span></label>
                            <input type="number" name="cedula" class="form-control form-control-lg" id="input1" autocomplete="off" autofocus
                                required>

                        </div>


                        <div class="mb-3 w-100" title="Este campo es obligatorio">
                            <label for="input2" class="form-label col-form-label-lg fw-semibold">DETALLES DE LA AUTORIZACIÓN <span
                                    class="text-danger" style="font-size:20px;">*</span></label>
                            <textarea type="number" name="detalle" class="form-control form-control-lg" autocomplete="off" required></textarea>

                        </div>
                        <div class="mb-4 w-100" style="">
                            <label for="exampleInputEmail1" class="form-label col-form-label-lg fw-semibold">ADJUNTAR ANÁLISIS<span
                                class="text-danger" style="font-size:20px;"> *</span></label>
                            <input type="file" class="form-control" name="SoporteScore" id="SoporteScore" required>
                        </div>
                        <div class="text-center">
                            <button id="agregar" type="submit" class="btn btn-primary fs-4 fw-bold" name="btnregistrar"
                                style="background-color: #646464;">SOLICITAR</button>
                        </div>
                        `);
                } else if (valorSeleccionado == "11B") {
                    $("#cuerpo").html(`
                        <div class="mb-3 w-100" title="Este campo es obligatorio" id="id">
                            <label for="input1" class="form-label col-form-label-lg fw-semibold">CÉDULA <span class="text-danger"
                                    style="font-size:20px;">*</span></label>
                            <input type="number" name="cedula" class="form-control form-control-lg" id="input1" autocomplete="off" autofocus
                                required>

                        </div>


                        <div class="mb-3 w-100" title="Este campo es obligatorio">
                            <label for="input2" class="form-label col-form-label-lg fw-semibold">DETALLES DE LA AUTORIZACIÓN <span
                                    class="text-danger" style="font-size:20px;">*</span></label>
                            <textarea type="number" name="detalle" class="form-control form-control-lg" autocomplete="off" required></textarea>

                        </div>
                        <div class="mb-4 w-100" style="">
                            <label for="exampleInputEmail1" class="form-label col-form-label-lg fw-semibold">ADJUNTAR ANÁLISIS<span
                                class="text-danger" style="font-size:20px;"> *</span></label>
                            <input type="file" class="form-control" name="SoporteScore" id="SoporteScore" required>
                        </div>
                        <div class="text-center">
                            <button id="agregar" type="submit" class="btn btn-primary fs-4 fw-bold" name="btnregistrar"
                                style="background-color: #646464;">SOLICITAR</button>
                        </div>
                        `);
                } else if (valorSeleccionado == "19B") {
                    $("#cuerpo").html(`
                        <div class="mb-3 w-100" title="Este campo es obligatorio" id="id">
                            <label for="input1" class="form-label col-form-label-lg fw-semibold">CÉDULA <span class="text-danger"
                                    style="font-size:20px;">*</span></label>
                            <input type="number" name="cedula" class="form-control form-control-lg" id="input1" autocomplete="off" autofocus
                                required>

                        </div>


                        <div class="mb-3 w-100" title="Este campo es obligatorio">
                            <label for="input2" class="form-label col-form-label-lg fw-semibold">DETALLES DE LA AUTORIZACIÓN <span
                                    class="text-danger" style="font-size:20px;">*</span></label>
                            <textarea type="number" name="detalle" class="form-control form-control-lg" autocomplete="off" required></textarea>

                        </div>
                        <div class="mb-4 w-100" style="">
                            <label for="exampleInputEmail1" class="form-label col-form-label-lg fw-semibold">ADJUNTAR CAPTURA DE AS400<span
                                class="text-danger" style="font-size:20px;"> *</span></label>
                            <input type="file" class="form-control" name="SoporteScore" id="SoporteScore" required>
                        </div>
                        <div class="text-center">
                            <button id="agregar" type="submit" class="btn btn-primary fs-4 fw-bold" name="btnregistrar"
                                style="background-color: #646464;">SOLICITAR</button>
                        </div>
                        `);
                }
            });
        </script>

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
