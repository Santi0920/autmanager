        <!-- SCRIPT DATATABLE COMPLETO -->
        <script>
            $(function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            var table = $('#personas').DataTable({
                "ajax": {
                    "url": "{{ route('data.solicitudes') }}",
                    "dataType": "json", // Indicar que se espera una respuesta JSON
                    "error": function(xhr, error, thrown) {
                        // Verificar si el error es debido a una respuesta JSON inválida
                        if (xhr.status === 200 && xhr.responseJSON && xhr.responseJSON.error) {
                            // Redirigir al usuario a la ruta deseada
                            window.location.href = "{{ route('login.index') }}";
                        }
                    }
                },
                "order": [
                    [0, 'desc']
                ],
                scrollY: 420,
                "processing" : true,
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
                        data: 'Fecha',
                        render: function(data, type, row) {
                            var Contenido = `${row.Concepto}<div class="fw-bold text-primary">${row.NumArea} - ${row.NomArea} - ${row.Usuario}
                                    <div>
                                        <span class="text-dark" title="Fecha Solicitud">
                                        ${row.FechaStringEstado.charAt(0).toUpperCase() + row.FechaStringEstado.slice(1)}
                                        </span>
                                    </div>
                                </div>
                            `

                            return Contenido
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
                        data: 'UltimoEstado',
                        render: function(data, type, row) {
                            ultimoEstado = row.UltimoEstado
                            if (ultimoEstado == "REMITIDO") {
                                var Estado =
                                    '<div class="btn btn-info shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;"><span class="d-none">1</span>REMITIDO A GERENCIA</div>';
                            }
                            else if(row.Bloqueado == 1){
                                var Estado =
                                    '<div class="btn btn-danger shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;"><span class="d-none">1</span>BLOQUEADO</div>';
                            }else if (ultimoEstado == "CORREGIR") {
                                var Estado =
                                    '<div class="btn btn-primary shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;"><span class="d-none">1</span>CORREGIR</div>';
                            } else if (ultimoEstado == "TRÁMITE") {
                                var Estado =
                                    `<div class="btn btn-warning shadow" style="padding: 0.4rem 1.4rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">EN TRAMITE</div>`
                            }else if (ultimoEstado == "VALIDADO") {
                                var Estado =
                                    `<div class="btn btn-warning shadow" style="padding: 0.4rem 1.4rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">VALIDADO</div>`
                            }else if (ultimoEstado == "APROBADO") {
                                var Estado =
                                    '<div class="btn btn-success blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">APROBADO POR GERENCIA</div>'
                            } else if (ultimoEstado == "ANULADO") {
                                var Estado =
                                    '<div class="btn btn-info blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">ANULADO</div>'
                            } else if (ultimoEstado == "STAND BY") {
                                var Estado =
                                    '<div class="btn btn-dark blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">STAND BY</div>'
                            } else {
                                var Estado =
                                    '<div class="btn btn-primary shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">CORREGIR(GERENCIA)</div>'
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

                            var id = row.IDAutorizacion; // Obtener el ID de la fila

                            const cedula = row.Cedula;

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
                                                        ${row.UltimoEstado == "TRÁMITE"?
                                                            `<button class="btn btn-warning shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">T - EN TRAMITE</button>` :
                                                            row.UltimoEstado == "VALIDADO" ?
                                                            `<button class="btn btn-warning shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">V - VALIDADO</button>` :
                                                            row.UltimoEstado == "APROBADO" ?
                                                            `<button class="btn btn-success  shadow blink" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">AP - APROBADO</button>` :
                                                            row.UltimoEstado == "CORREGIR" ?
                                                            `<button class="btn btn-primary shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">C - CORREGIR</button>` :
                                                            row.UltimoEstado == "ANULADO" ?
                                                            '<button class="btn btn-info shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">AN - ANULADO</button>' :
                                                            row.UltimoEstado == "STAND BY" ?
                                                            '<button class="btn btn-dark shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">STAND BY</button>' :
                                                            row.UltimoEstado == "REMITIDO" ?
                                                            '<button class="btn btn-info shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">REMITIDO A GERENCIA</button>' :
                                                            '<h1>nada</h1>'
                                                        }
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="row g-0 text-center">


                                        ${
                                            row.historial
                                                .slice(0) // saltar el primer estado
                                                .map(item => {

                                                    const inputcedula = `
                                                        <div class="input-group mb-0 w-25 border rounded-3 border-dark ms-2 me-2">
                                                                <input class="form-control fs-5 border-end border-dark" style="border-radius: 7px 0 0 7px;" id="Cedulamodal${id}" name="Cedulamodal" value="${item.Cedula}" required onkeydown="disableEnterKey(event)">
                                                                <span class="input-group-text bg-success-subtle border-dark text-primary tooltip1" data-bs-toggle="tooltip" data-bs-placement="right" title="Cédula / NIT">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info">
                                                                        <circle cx="12" cy="12" r="10" />
                                                                        <path d="M12 16v-4" />
                                                                        <path d="M12 8h.01" />
                                                                    </svg>
                                                                </span>
                                                        </div>
                                                        `
                                                    const inputcuenta = `
                                                        <div class="input-group mb-0 w-25 border rounded-3 border-dark me-2">
                                                                <input class="form-control fs-5 border-end border-dark" style="border-radius: 7px 0 0 7px;" id="Cuentamodal${id}" name="Cuentamodal" value="${item.CuentaAsociado}" required onkeydown="disableEnterKey(event)">
                                                                <span class="input-group-text bg-success-subtle border-dark text-primary tooltip2" data-bs-toggle="tooltip" data-bs-placement="right" title="Cuenta">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info">
                                                                        <circle cx="12" cy="12" r="10" />
                                                                        <path d="M12 16v-4" />
                                                                        <path d="M12 8h.01" />
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        `

                                                    const inputnombre = `
                                                            <div class="input-group mb-0 w-25 border rounded-3 border-dark me-2">
                                                                <input class="form-control fs-5 border-end border-dark" style="border-radius: 7px 0 0 7px;" id="Nombremodal${id}" name="Nombremodal" value="${item.NombrePersona}" required onkeydown="disableEnterKey(event)">
                                                                <span class="input-group-text bg-success-subtle border-dark text-primary tooltip3" data-bs-toggle="tooltip" data-bs-placement="right" title="Nombre / Nombre Empresa">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info">
                                                                        <circle cx="12" cy="12" r="10" />
                                                                        <path d="M12 16v-4" />
                                                                        <path d="M12 8h.01" />
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        `


                                                    const inputconvencion = `
                                                        <div class="input-group mb-0 w-25 border rounded-3 border-dark me-2">
                                                                <input class="form-control fs-5 border-end border-dark tooltip4" style="border-radius: 7px 0 0 7px;" id="Convencionmodal${id}" name="Convencionmodal" value="${item.Convencion}" required onkeydown="disableEnterKey(event)">
                                                                <span class="input-group-text bg-success-subtle border-dark text-primary tooltip4" data-bs-toggle="tooltip" data-bs-placement="right" title="Convenciones">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info">
                                                                        <circle cx="12" cy="12" r="10" />
                                                                        <path d="M12 16v-4" />
                                                                        <path d="M12 8h.01" />
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        `






                                                    if (item.ID_Concepto == 41) {
                                                        var inputs = (inputcedula + inputconvencion);
                                                    } else if (item.ID_Concepto == 22) {
                                                        var inputs =(`
                                                            <input class="mb-0 w-25 fs-5 me-3" style="resize: vertical; border-radius: 10px; width:30px" id="Cedulamodal${id}" name="Cedulamodal" value="805.004.034-9" disabled onkeydown="disableEnterKey(event)"></input>
                                                            <input class="mb-0 w-25 fs-5 me-3" style="resize: vertical; border-radius: 10px; width:30px" id="Nombremodal${id}" name="Nombremodal" value="COOPSERP" disabled onkeydown="disableEnterKey(event)"></input>
                                                        `);
                                                    }else {
                                                        var inputs =(inputcedula + inputnombre + inputcuenta);
                                                    }

                                                    const mesesEnEspanol = [
                                                        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                                                        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                                                    ];

                                                    const fechainsercion = item.FechaInsercion;
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


                                                    // Si el estado es "EN TRÁMITE", renderiza el bloque especial
                                                    if (item.Estado === 'TRÁMITE' || item.Estado === 'DONE' || item.Estado === 'REMITIDO' && item.Estado !== 'VALIDADO') {
                                                        return `
                                        <div class="row g-0 text-center">
                                            <div class="col-sm-12 col-md-12 col-lg-2 d-flex align-items-center justify-content-center rounded-0 bg-warning-subtle border p-3 border border-dark">
                                                <span class="h1 fw-bold mb-0">T<br><span class="fs-5 fw-normal">TRÁMITE</span></span>
                                            </div>
                                            <div class="col-sm-12 col-md-12 col-lg-10">
                                                <div class="row g-0 justify-content-center hover-trigger"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#secondaryData_${id}"
                                                style="cursor:pointer;">
                                                    <div class="row g-0 row-cols-2 justify-content-center">
                                                        <div class="col-md-9 d-flex align-items-center justify-content-start border p-2">
                                                            <span class="fs-5">${item.NomArea} - ${item.NumArea} - <b>${item.Nombre}</b><br>👉(Click para mostrar)👈</span>
                                                        </div>
                                                        <div class="col-md-3 d-flex align-items-center justify-content-center border p-2">
                                                            <span class="mb-0 fs-5">${item.FechaString}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <form enctype="multipart/form-data" id="formEditarAutorizacion${row.IDAutorizacion}">
                                                    @csrf
                                                    <div class="collapse" id="secondaryData_${id}">
                                                        <div class="row g-0 row-cols-2 d-flex justify-content-start">
                                                            <div class="col-sm-6 col-md-9 col-lg-9 d-flex align-items-center justify-content-start border p-2">
                                                                ${row.UltimoEstado == 'CORREGIR' && item.Estado !== 'DONE'  && '{{ session('rol') }}' !== 'Coordinacion'  ? `
                                                                    <div class="mb-3 w-100" id="id">
                                                                        <select class="form-select form-select-lg" name="tautorizacionmodal" id="autorizacionesmodal${row.IDAutorizacion}" 
                                                                            onChange="autorizacionesModalChange(${row.IDAutorizacion},'${item.Cedula}','${item.CuentaAsociado}', '${item.NombrePersona}', '${item.Convencion}', event)" required>
                                                                            <option selected class="fw-bold" value="${item.ID_Concepto}">**Concepto Actual** -> ${row.Concepto}</option>
                                                                            @include('layouts.optionmodal')
                                                                        </select>
                                                                    </div>
                                                                ` : `<span class="fs-5">${row.Concepto} - @include('layouts.optionvercodigo')</span>`}
                                                            </div>
                                                            <div class="col-sm-6 col-md-3 col-lg-3 d-flex align-items-center justify-content-center border p-3">
                                                                ${item.ID_Concepto == 41 ? `<span class="fs-5 fw-bold mb-0">@include('layouts.optionverconvenciones') - ${item.Convencion}</span>` : ``}
                                                            </div>
                                                        </div>


                                                    
                                                        ${
                                                            (row.UltimoEstado == 'CORREGIR' && item.Estado !== 'DONE' && '{{ session('rol') }}' !== 'Coordinacion' 
                                                                ? `
                                                                        <div class="row g-0">
                                                                            <div class="d-inline-flex" style="white-space: nowrap; flex-wrap: nowrap;" id="desactivar">
                                                                                ${inputs}
                                                                            </div>
                                                                            <div class="col-md-12 d-flex justify-content-start border p-2" id="inputs${row.IDAutorizacion}">
                                                                                <span class="fs-5">${item.Cedula}
                                                                                    ${item.CuentaAsociado == null ? '- N/A' : `- ${item.CuentaAsociado}`}
                                                                                    - ${item.NombrePersona} -
                                                                                    ${
                                                                                        item.Score >= 650
                                                                                            ? `<span class="badge bg-success text-light fw-bold">${item.Score}</span> - ${estado}`
                                                                                            : (item.Score === 'S/E'
                                                                                                ? `<span class="badge bg-warning text-dark fw-bold">${item.Score}</span> - ${estado}`
                                                                                                : `<span class="badge bg-danger text-light fw-bold">${item.Score}</span> - ${estado}`)
                                                                                    }
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    `
                                                                    
                                                                : (item.Estado == 'DONE' || item.Estado == 'TRÁMITE')
                                                                    ? `
                                                                    <div class="row g-0">
                                                                        <div class="col-md-12 d-flex justify-content-start border p-2">
                                                                            <span class="fs-5">${item.Cedula}
                                                                                ${item.CuentaAsociado == null ? '- N/A' : `- ${item.CuentaAsociado}`}
                                                                                - ${item.NombrePersona} -
                                                                                ${
                                                                                    item.Score >= 650
                                                                                        ? `<span class="badge bg-success text-light fw-bold">${item.Score}</span> - ${estado}`
                                                                                        : (item.Score === 'S/E'
                                                                                            ? `<span class="badge bg-warning text-dark fw-bold">${item.Score}</span> - ${estado}`
                                                                                            : `<span class="badge bg-danger text-light fw-bold">${item.Score}</span> - ${estado}`)
                                                                                }
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                `: ''
                                                                )
                                                        }


                                                        <div class="row g-0">
                                                            ${row.UltimoEstado == 'CORREGIR' && item.Estado != 'DONE' && '{{ session('rol') }}' !== 'Coordinacion'  ? `
                                                                <div class="col-sm-12 col-md-9 text-start border p-2 fs-5">
                                                                    <textarea class="mb-0 w-100" style="resize: vertical; height: 100px; border-radius: 10px" 
                                                                        id="Detalle" name="Detalle_${row.IDAutorizacion}" required>${item.Detalle}</textarea>
                                                                </div>
                                                                <div class="col-sm-12 col-md-3 d-flex align-items-center justify-content-center p-3">
                                                                    <label for="file_${row.IDAutorizacion}" class="labelFile" style="cursor:pointer;">
                                                                        <span>
                                                                            <img src="img/pdf.png" style="height:4.5rem">
                                                                        </span>
                                                                        <p id="uploadMessage_${row.IDAutorizacion}">Adjunta el archivo aquí!</p>
                                                                    </label>
                                                                    
                                                                    <!-- Input oculto -->
                                                                    <input 
                                                                        class="input d-none" 
                                                                        name="Soporte_${row.IDAutorizacion}" 
                                                                        id="file_${row.IDAutorizacion}" 
                                                                        type="file" 
                                                                        accept="application/pdf" 
                                                                        onchange="fileUploaded(${row.IDAutorizacion})" 
                                                                    />

                                                                    <input type="hidden" id="DocumentoSoporte_${row.IDAutorizacion}" value="${item.DocumentoSoporte}" />
                                                                </div>

                                                            ` : `
                                                                <div class="col-sm-12 col-md-9 text-start border p-2 fs-5">
                                                                    <span class="mb-0">${item.Detalle}</span>
                                                                </div>
                                                                <a href="Storage/files/soporteautorizaciones/${item.DocumentoSoporte}" 
                                                                    class="col-sm-12 col-md-3 d-flex align-items-center justify-content-center btn btn-outline-info rounded-0 p-3" 
                                                                    target="__blank">
                                                                    <img src="img/pdf.png" style="height:4.5rem">
                                                                </a>
                                                            `}
                                                        </div>
                                                    </div>    
                                                </form>
                                            </div>

                                    
                                        ${
                                            // Coordinación para VALIDAR o Gerencia para REMITIDO
                                            ((item.Estado === 'TRÁMITE' && '{{ session('rol') }}' === 'Coordinacion' && item.Observaciones !== 'NADA'))
                                                ? `
                                                    <form enctype="multipart/form-data" id="formValidarAutorizacion${row.IDAutorizacion}" data-id="${row.IDAutorizacion}">
                                                        @csrf
                                                        <div class="row g-0">
                                                            <div class="col-sm-12 col-md-12 col-lg-2 d-flex flex-column align-items-center align-items-lg-start justify-content-start border p-3 border-dark ${row.UltimoEstado === 'TRÁMITE' ? 'bg-dark-subtle' : ''}">
                                                                <label class="label">
                                                                    <input value="VALIDADO" type="radio" name="Estado" required>
                                                                    <span>VALIDAR</span>
                                                                </label>
                                                                <label class="label">
                                                                    <input value="CORREGIR" type="radio" name="Estado" required>
                                                                    <span>RECHAZAR</span>
                                                                </label>
                                                            </div>

                                                            <div class="col-sm-12 col-md-12 col-lg-10">
                                                                <div class="row g-0 justify-content-center">
                                                                    <div class="row g-0 row-cols-2 justify-content-center">
                                                                        <div class="col-md-9 d-flex align-items-center justify-content-start border p-2">
                                                                            <span class="fs-5"><b>{{ session('agenciau') }} - {{ session('name') }}</b></span>
                                                                        </div>
                                                                        <div class="col-md-3 d-flex align-items-center justify-content-center border p-2">
                                                                            <span class="mb-0 fs-5">Pendiente...</span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <input 
                                                                        class="fs-5 col-md-12 d-flex text-start border p-3 w-100" 
                                                                        style="resize: horizontal;" 
                                                                        id="Observaciones" 
                                                                        name="Observaciones" 
                                                                        onkeydown="return event.key != 'Enter';" 
                                                                        placeholder="Escribe aquí tu Observación." 
                                                                        ${item.Observaciones == null ? '' : `value="${item.Observaciones}"`} 
                                                                        required
                                                                    >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                `
                                                : (((item.Estado == 'REMITIDO') && '{{ session('rol') }}' == 'Gerencia')
                                                    ? 
                                                    `
                                                        <form enctype="multipart/form-data" id="formValidarGerenciaAutorizacion${row.IDAutorizacion}" data-id="${row.IDAutorizacion}">
                                                            @csrf
                                                            <div class="row g-0">
                                                                <div class="col-sm-12 col-md-12 col-lg-2 d-flex flex-column align-items-center align-items-lg-start justify-content-start border p-3 border-dark bg-dark-subtle">
                                                                    <label class="label">
                                                                        <input value="APROBADO" type="radio" name="Estado" id="estado_aprobar" required>
                                                                        <span>APROBAR</span>
                                                                    </label>
                                                                    <label class="label">
                                                                        <input value="CORREGIR" type="radio" name="Estado" id="estado_rechazar" required>
                                                                        <span>RECHAZAR</span>
                                                                    </label>
                                                                    <label class="label">
                                                                        <input value="1" type="radio" name="Estado" id="estado_bloquear" required>
                                                                        <span>BLOQUEAR</span>
                                                                    </label>
                                                                    <label class="label">
                                                                        <input value="STAND BY" type="radio" name="Estado" id="estado_standby" required>
                                                                        <span>STAND BY</span>
                                                                    </label>
                                                                </div>

                                                                <div class="col-sm-12 col-md-12 col-lg-10">
                                                                    <div class="row g-0 justify-content-center">
                                                                        <div class="row g-0 row-cols-2 justify-content-center">
                                                                            <div class="col-md-9 d-flex align-items-center justify-content-start border p-2">
                                                                                <span class="fs-5"><b>{{ session('agenciau') }} - {{ session('name') }}</b></span>
                                                                            </div>
                                                                            <div class="col-md-3 d-flex align-items-center justify-content-center border p-2">
                                                                                <span class="mb-0 fs-5">Pendiente...</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <input 
                                                                            class="fs-5 col-md-12 d-flex text-start border p-3 w-100" 
                                                                            style="resize: horizontal;" 
                                                                            id="Observaciones" 
                                                                            name="Observaciones" 
                                                                            onkeydown="return event.key != 'Enter';" 
                                                                            placeholder="Escribe aquí tu Observación." 
                                                                            ${item.Observaciones == null ? '' : `value="${item.Observaciones}"`} 
                                                                            required
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    
                                                    
                                                    `
                                                    : ``)
                                        }





                                        </div>`;
                                                    }

                                                    // Si NO es "TRÁMITE", renderiza el bloque normal
                                                    return `
                                        <div class="row g-0 text-center">
                                            <div class="col-sm-12 col-md-12 col-lg-2 d-flex align-items-center justify-content-center ${
                                                item.Estado === 'VALIDADO' ? 'bg-success-subtle' :
                                                item.Estado === 'VALIDADOCONFIRMADO' ? 'bg-success-subtle' :
                                                item.Estado === 'REMITIDOCONFIRMADO' ? 'bg-warning-subtle' :
                                                item.Estado === 'APROBADO' ? 'bg-success-subtle' :
                                                item.Estado === 'CORREGIR' ? 'bg-primary-subtle' :
                                                item.Estado === 'REMITIDO' ? 'bg-info-subtle' :
                                                'bg-secondary-subtle'
                                            } border p-2 border border-dark" title="${item.Estado}">
                                                <span class="h1 fw-bold mb-0">
                                                    ${item.Estado[0]}<br>
                                                    <span class="fs-5 fw-normal">
                                                        ${item.Estado == "VALIDADOCONFIRMADO" 
                                                            ? "VALIDADO" 
                                                            : item.Estado == "REMITIDOCONFIRMADO" 
                                                                ? "REMITIDO" 
                                                                : item.Estado}
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="col-sm-12 col-md-12 col-lg-10">
                                                <div class="row g-0">
                                                    <div class="text-start col-md-9 d-flex align-items-center border p-2">
                                                        <span class="fs-5 fw-bold mb-0">${item.NumArea} - ${item.NomArea} - ${item.Nombre ?? 'N/A'}</span>
                                                    </div>
                                                    <div class="col-md-3 d-flex align-items-center justify-content-center border p-3">
                                                        <span class="mb-0 fs-5">${item.FechaString ?? ''}</span>
                                                    </div>
                                                    <div class="col-md-12 col-lg-10 w-100">
                                                        <span class="row g-0 border text-start p-2 mb-0 fw-semibold fs-5">
                                                            ${item.Observaciones ? item.Observaciones : 'Ninguna.'}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        ${(((item.Estado == 'VALIDADO') && '{{ session('rol') }}' == 'Gerencia')
                                                    ? 
                                                    `
                                                        <form enctype="multipart/form-data" id="formValidarGerenciaAutorizacion${row.IDAutorizacion}" data-id="${row.IDAutorizacion}">
                                                            @csrf
                                                            <div class="row g-0">
                                                                <div class="col-sm-12 col-md-12 col-lg-2 d-flex flex-column align-items-center align-items-lg-start justify-content-start border p-3 border-dark bg-dark-subtle">
                                                                     <label class="label">
                                                                        <input value="APROBADO" type="radio" name="Estado" id="estado_aprobar" required>
                                                                        <span>APROBAR</span>
                                                                    </label>
                                                                    <label class="label">
                                                                        <input value="CORREGIR" type="radio" name="Estado" id="estado_rechazar" required>
                                                                        <span>RECHAZAR</span>
                                                                    </label>
                                                                    <label class="label">
                                                                        <input value="1" type="radio" name="Estado" id="estado_bloquear" required>
                                                                        <span>BLOQUEAR</span>
                                                                    </label>
                                                                    <label class="label">
                                                                        <input value="STAND BY" type="radio" name="Estado" id="estado_standby" required>
                                                                        <span>STAND BY</span>
                                                                    </label>
                                                                </div>

                                                                <div class="col-sm-12 col-md-12 col-lg-10">
                                                                    <div class="row g-0 justify-content-center">
                                                                        <div class="row g-0 row-cols-2 justify-content-center">
                                                                            <div class="col-md-9 d-flex align-items-center justify-content-start border p-2">
                                                                                <span class="fs-5"><b>{{ session('agenciau') }} - {{ session('name') }}</b></span>
                                                                            </div>
                                                                            <div class="col-md-3 d-flex align-items-center justify-content-center border p-2">
                                                                                <span class="mb-0 fs-5">Pendiente...</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <input 
                                                                            class="fs-5 col-md-12 d-flex text-start border p-3 w-100" 
                                                                            style="resize: horizontal;" 
                                                                            id="Observaciones" 
                                                                            name="Observaciones" 
                                                                            onkeydown="return event.key != 'Enter';" 
                                                                            placeholder="Escribe aquí tu Observación." 
                                                                            ${item.Observaciones == null ? '' : `value="${item.Observaciones}"`} 
                                                                            required
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    
                                                    
                                                    `
                                                    : ``)
                                        
                                        
                                        }`;
                                                })
                                                .join('')
                                        }



                                        </div>
                                ${
                                    row.UltimoEstado == 'CORREGIR' && '{{ session('rol') }}' !== 'Coordinacion' 
                                        ? `
                                        <div class="text-center p-3">
                                            <button id="boton${row.IDAutorizacion}" 
                                                type="button" 
                                                class="btn btn-outline-success fs-5 fw-bold w-50" 
                                                name="btnregistrar" 
                                                onclick="formEditarAutorizacion(${row.IDAutorizacion}, event)">
                                                GUARDAR
                                            </button>
                                        </div>
                                        `
                                        : (row.UltimoEstado === 'TRÁMITE' && '{{ session('rol') }}' === 'Coordinacion')
                                            ? `
                                            <div class="text-center p-3">
                                                <button id="boton${row.IDAutorizacion}" 
                                                    type="button" 
                                                    class="btn btn-outline-success fs-5 fw-bold w-50" 
                                                    name="btnregistrar" 
                                                    onclick="formValidarAutorizacion(${row.IDAutorizacion}, event)">
                                                    GUARDAR
                                                </button>
                                            </div>
                                            `
                                        : ((row.UltimoEstado === 'REMITIDO' || row.UltimoEstado === 'VALIDADO') && '{{ session('rol') }}' === 'Gerencia')
                                            ? `

                                            <div class="text-center p-3">
                                                <button id="boton${row.IDAutorizacion}" 
                                                    type="button" 
                                                    class="btn btn-outline-success fs-5 fw-bold w-50" 
                                                    name="btnregistrar" 
                                                    onclick="formValidarGerenciaAutorizacion(${row.IDAutorizacion}, event)">
                                                    GUARDAR
                                                </button>
                                            </div>
                                            
                                            `:''
                                }

                                        </div>
                                    </div>
                                </div>
                            </div> `;

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
                    var buttonsHtml = '<div class="d-flex flex-wrap align-items-center gap-2">' +
                        '<button class="custom-btn2 mt-0 mt-lg-1 mt-md-2  mt-sm-2 me-1" title="ACTUALIZAR INFORMACIÓN"><a href="filtrarconceptoger" id="exportExcel" title="EXPORTAR EXCEL"><i class="fas fa-file-excel text-white"></i></a></button>' +
                        '<button id="btnT" class="custom-btn mt-0 mt-lg-1 mt-md-2  mt-sm-2 me-1" title="ACTUALIZAR INFORMACIÓN"><i class="fa-solid fa-rotate-right"></i></button>' +
                        `
                        <div class="dropdown d-inline" title="Solicitudes de jefaturas">
                            <button class="btn btn-dark fw-bold dropdown-toggle mt-0 mt-lg-1 mt-md-2 mt-sm-2 me-1"
                                    type="button"
                                    id="dropdownMenuButton"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                STAND BY
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item fw-bold" href="#" id="btnStandBy">VER</a></li>
                                <li><a class="dropdown-item fw-bold" href="{{ route('datager.aprobarstandby') }}" id="btnAprobarTodos">APROBAR TODOS</a></li>
                            </ul>
                        </div>
                        ` +
                        '<button id="btnA" class="btn btn-success fw-bold mt-0 mt-lg-1 mt-md-2  mt-sm-2 me-1" title="APROBADOS">APROBADOS</button>' +
                        '<button id="btnR" class="btn btn-danger fw-bold mt-0 mt-lg-1 mt-md-2  mt-sm-2 me-1" title="RECHAZADOS">RECHAZADOS</button>' +
                        '<button id="btnTramite" class="btn btn-warning fw-bold mt-0 mt-lg-1 mt-md-2  mt-sm-2  me-1" title="EN TRÁMITE">EN TRÁMITE</button>' +
                        '<button id="btnBloqueado" class="btn btn-primary fw-bold mt-0 mt-lg-1 mt-md-2  mt-sm-2 me-1" title="BLOQUEADOS">BLOQUEADOS</button>' +
                        '<button id="btnAnulado" class="btn btn-info fw-bold mt-0 mt-lg-1 mt-md-2  mt-sm-2  me-1" title="ANULADOS">ANULADOS</button>' +
                    '</div>';

                    $(buttonsHtml).prependTo('.dataTables_filter');
                        $('#btnT').on('click', function() {
                            var newAjaxSource = '{{ route("datager.solicitudes") }}';

                            $('#personas').DataTable().ajax.url(newAjaxSource).load();
                        });

                        $('#btnA').on('click', function() {
                            var newAjaxSource = '{{ route("datager.aprobados") }}';

                            $('#personas').DataTable().ajax.url(newAjaxSource).load();
                        });

                        $('#btnR').on('click', function() {
                            var newAjaxSource = '{{ route("datager.rechazados") }}';

                            $('#personas').DataTable().ajax.url(newAjaxSource).load();

                        });

                        $('#btnTramite').on('click', function() {
                            var newAjaxSource = '{{ route("datager.tramite") }}';

                            $('#personas').DataTable().ajax.url(newAjaxSource).load();

                        });

                        $('#btnBloqueado').on('click', function() {
                            var newAjaxSource = '{{ route("datager.bloqueados") }}';

                            $('#personas').DataTable().ajax.url(newAjaxSource).load();

                        });

                        $('#btnAnulado').on('click', function() {
                            var newAjaxSource = '{{ route("datager.anulados") }}';

                            $('#personas').DataTable().ajax.url(newAjaxSource).load();

                        });

                        $('#btnStandBy').on('click', function() {
                            var newAjaxSource = '{{ route("datager.standby") }}';

                            $('#personas').DataTable().ajax.url(newAjaxSource).load();

                        });



                        // Evitar que aprueba directamente
                        document.getElementById('btnAprobarTodos').addEventListener('click', function(e) {
                            e.preventDefault(); // Evita que se vaya directo al enlace

                            let url = this.getAttribute('href');

                            Swal.fire({
                                title: '¿Está seguro?',
                                html: '<span style="font-size:21px;">¿Desea aprobar todas las solicitudes con estado <b>STAND BY</b>?</span>',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#198754',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Sí, aprobar',
                                cancelButtonText: 'Cancelar',
                                customClass: {
                                    confirmButton: 'swal2-confirm btn-lg custom-btn',
                                    cancelButton: 'swal2-cancel btn-lg custom-btn'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = url; // Redirige a la ruta
                                }
                            });
                        });



                        },
                        // responsive: "true",
                        //     dom: 'Bfrtilp',
                        //     buttons:[
                        //         {
                        //             extend:    'excelHtml5',
                        //             text:      '<i class="fas fa-file-excel"></i> ',
                        //             titleAttr: 'Exportar a Excel',
                        //             className: 'btn btn-success btn-md'
                        //         }
                        // ],

                    });

            function csesion() {
                var respuesta = confirm("¿Estas seguro que deseas cerrar sesión?")
                return respuesta
            }

            //ajax
            function formEditarAutorizacion(id, event) {
                var _token = $('input[name="_token"]').val();
                var CodigoAutorizacion = $(`#autorizacionesmodal${id}`).val();
                var Cedulamodal = $(`#Cedulamodal${id}`).val();
                var Cuentamodal = $(`#Cuentamodal${id}`).val();
                var Nombremodal = $(`#Nombremodal${id}`).val();
                var Convencionmodal = $(`#Convencionmodal${id}`).val();
                var Detalle = $(`textarea[name="Detalle_${id}"]`).val();
                var Soporte = document.querySelector(`input[name="Soporte_${id}"]`).files[0];
                var DocumentoSoporte = $(`#DocumentoSoporte_${id}`).val();


                var formData = new FormData();
                formData.append('_token', _token);
                formData.append('Detalle', Detalle);
                formData.append('CodigoAutorizacion', CodigoAutorizacion);
                formData.append('Cedulamodal', Cedulamodal);
                formData.append('Cuentamodal', Cuentamodal);
                formData.append('Nombremodal', Nombremodal);
                formData.append('Convencionmodal', Convencionmodal);

                // Verificar si hay un archivo adjunto
                if (Soporte) {
                    formData.append('Soporte_' + id, Soporte);
                } else {
                    formData.append('DocumentoSoporte', DocumentoSoporte);
                }

                Swal.fire({
                    title: 'Cargando...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                        // Realizar la solicitud AJAX mientras se muestra el mensaje de carga
                        $.ajax({
                            url: "{{ route('update.autorizacion', ['id' => ':id']) }}".replace(':id', id),
                            type: "POST",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {

                                if (response.message === "Datos recibidos correctamente") {
                                    $(`#exampleModal_${id}`).modal('hide');
                                    console.log('¡Éxito!');
                                    event.preventDefault();
                                    $('#personas').DataTable().ajax.reload();
                                    Swal.fire({
                                        icon: 'success',
                                        title: "¡ACTUALIZADO!",
                                        html: "<span class='fw-semibold'>Se actualizó correctamente la autorización No. <span class='badge bg-primary fw-bold'>" +
                                            id + "</span></span>",
                                        confirmButtonColor: '#646464'
                                    });
                                } else if (response.message === "¡PERSONA NO EXISTE EN AS400!") {
                                    Swal.fire({
                                        icon: 'error',
                                        title: "¡PERSONA NO EXISTE EN AS400!",
                                        text: '',
                                        confirmButtonColor: '#646464',
                                        timer: 10000
                                    });
                                } else if (response.message === "¡PERSONA NO EXISTE EN DATACRÉDITO!") {
                                    Swal.fire({
                                        icon: 'error',
                                        title: "¡PERSONA NO EXISTE EN DATACRÉDITO!",
                                        text: '',
                                        confirmButtonColor: '#646464',
                                        timer: 10000
                                    });
                                } else if (response.message ===
                                    "No aplica porque aun está vinculado a COOPSERP.") {
                                    Swal.fire({
                                        icon: 'error',
                                        title: "No aplica porque aun está vinculado a COOPSERP.",
                                        text: '',
                                        confirmButtonColor: '#646464',
                                        timer: 10000
                                    });
                                } else if (response.message === "No necesita autorización") {
                                    Swal.fire({
                                        icon: 'info',
                                        title: "No necesita autorización",
                                        html: "No necesita autorización, tiene " + response
                                            .dias_restantes + " días asociados a COOPSERP.",
                                        confirmButtonColor: '#646464',
                                        timer: 10000
                                    });
                                }else{
                                    console.log(response.message);
                                }
                            }
                        });
                    }
                });
            }

            //AJAX PARA COORDINADOR

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
                    url: "{{ route('update.autorizacion', ['id' => ':id']) }}".replace(':id', id),
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


            //AJAX PARA GERENCIA
            function formValidarGerenciaAutorizacion(id, event) {

                var form = $("#formValidarGerenciaAutorizacion" + id);

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
                    url: "{{ route('update.autorizacion', ['id' => ':id']) }}".replace(':id', id),
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






            function disableEnterKey(event) {
                if (event.key === "Enter") {
                    event.preventDefault(); // Prevenir la acción predeterminada de la tecla "Enter"
                }
            }


            function autorizacionesModalChange(id, cedula, cuenta, nombrepersona, convencion, event) {
                // Obtener el valor seleccionado del elemento select
                const valorSeleccionado = $(`#autorizacionesmodal${id}`).val();
                $('#desactivar').addClass('d-none');

                const inputcedula = `
                    <div class="input-group mb-0 w-25 border rounded-3 border-dark me-2">
                            <input class="form-control fs-5 border-end border-dark" style="border-radius: 7px 0 0 7px;" id="Cedulamodal${id}" name="Cedulamodal" value="${cedula}" required onkeydown="disableEnterKey(event)">
                            <span class="input-group-text bg-success-subtle border-dark text-primary tooltip1" data-bs-toggle="tooltip" data-bs-placement="right" title="Cédula / NIT">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 16v-4" />
                                    <path d="M12 8h.01" />
                                </svg>
                            </span>
                    </div>
                    `

                const inputcuenta = `
                    <div class="input-group mb-0 w-25 border rounded-3 border-dark me-2">
                            <input class="form-control fs-5 border-end border-dark" style="border-radius: 7px 0 0 7px;" id="Cuentamodal${id}" name="Cuentamodal" value="${cuenta}" required onkeydown="disableEnterKey(event)">
                            <span class="input-group-text bg-success-subtle border-dark text-primary tooltip2" data-bs-toggle="tooltip" data-bs-placement="right" title="Cuenta">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 16v-4" />
                                    <path d="M12 8h.01" />
                                </svg>
                            </span>
                        </div>
                    `

                const inputnombre = `
                    <div class="input-group mb-0 w-25 border rounded-3 border-dark me-2">
                            <input class="form-control fs-5 border-end border-dark" style="border-radius: 7px 0 0 7px;" id="Nombremodal${id}" name="Nombremodal" value="${nombrepersona}" required onkeydown="disableEnterKey(event)">
                            <span class="input-group-text bg-success-subtle border-dark text-primary tooltip3" data-bs-toggle="tooltip" data-bs-placement="right" title="Nombre / Nombre Empresa">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 16v-4" />
                                    <path d="M12 8h.01" />
                                </svg>
                            </span>
                        </div>
                    `


                const inputconvencion = `
                    <div class="input-group mb-0 w-25 border rounded-3 border-dark me-2">
                            <input class="form-control fs-5 border-end border-dark tooltip4" style="border-radius: 7px 0 0 7px;" id="Convencionmodal${id}" name="Convencionmodal" value="${convencion}" required onkeydown="disableEnterKey(event)">
                            <span class="input-group-text bg-success-subtle border-dark text-primary tooltip4" data-bs-toggle="tooltip" data-bs-placement="right" title="Convenciones">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 16v-4" />
                                    <path d="M12 8h.01" />
                                </svg>
                            </span>
                        </div>
                    `

                if (valorSeleccionado == 41) {
                    $("#inputs" + id).html(inputcedula + inputconvencion);
                } else if (valorSeleccionado == 22) {
                    $("#inputs" + id).html(`
                        <input class="mb-0 w-25 fs-5 me-3" style="resize: vertical; border-radius: 10px; width:30px" id="Cedulamodal${id}" name="Cedulamodal" value="805.004.034-9" disabled onkeydown="disableEnterKey(event)"></input>
                        <input class="mb-0 w-25 fs-5 me-3" style="resize: vertical; border-radius: 10px; width:30px" id="Nombremodal${id}" name="Nombremodal" value="COOPSERP" disabled onkeydown="disableEnterKey(event)"></input>
                    `);
                }else {
                    $("#inputs"+id).html(inputcedula + inputnombre + inputcuenta);
                }

                $('[data-bs-toggle="tooltip"]').tooltip();
            }


            $('#autorizaciones').on('change', function() {

                // Obtener el valor seleccionado
                var valorSeleccionado = $(this).val();
                console.log("Valor seleccionado:", valorSeleccionado);


                if (valorSeleccionado == "41") {
                    $("#cuerpo").html(`
                        <div class="mb-3 w-100" title="Este campo es obligatorio" id="id">
                            <label for="input1" class="form-label col-form-label-lg fw-semibold">CÉDULA <span class="text-danger"
                                    style="font-size:20px;">*</span></label>
                            <input type="number" name="cedula" class="form-control form-control-lg" id="input1" autocomplete="off" autofocus
                                required>

                        </div>

                        <div class="mb-3 w-100" title="Este campo es obligatorio" id="id">
                            <label for="input1" class="form-label col-form-label-lg fw-semibold">NOMBRE PERSONA/EMPRESA <span class="text-danger"
                                    style="font-size:20px;">*</span></label>
                            <input type="text" name="nombre" class="form-control form-control-lg" id="input1" autocomplete="off" autofocus
                                required>

                        </div>

                        <div class="mb-3 w-100" title="Este campo es obligatorio" id="id">
                            <label for="input1" class="form-label col-form-label-lg fw-semibold">CUENTA <span class="text-danger"
                                    style="font-size:20px;">*</span></label>
                            <input type="text" name="cuenta" class="form-control form-control-lg" id="input1" placeholder="Si no tiene cuenta escribir N/A" autocomplete="off" autofocus
                                required>

                        </div>

                        <div class="mb-3 w-100" title="Este campo es obligatorio">
                            <label for="input2" class="form-label col-form-label-lg fw-semibold">DETALLES DE LA AUTORIZACIÓN <span
                                    class="text-danger" style="font-size:20px;">*</span></label>
                            <textarea type="number" name="detalle" class="form-control form-control-lg" autocomplete="off" required></textarea>

                        </div>

                        <div class="mb-3 w-100" title="Este campo es obligatorio">
                            <label for="input2" class="form-label col-form-label-lg fw-semibold">CONVENCIONES <span
                                    class="text-danger" style="font-size:20px;">*</span></label>
                            <input type="text" name="convencion" class="form-control form-control-lg" autocomplete="off" required></input>

                        </div>


                        <div class="mb-4 w-100" style="">
                            <label for="exampleInputEmail1" class="form-label col-form-label-lg fw-semibold">ADJUNTAR CAPTURA DE AS400<span
                                class="text-danger" style="font-size:20px;"> *</span></label>
                            <input type="file" class="form-control" name="SoporteScore" id="SoporteScore" accept="application/pdf" required>
                        </div>
                        <div class="text-center">
                            <button id="agregar" type="submit" class="btn btn-primary fs-4 fw-bold" name="btnregistrar"
                                style="background-color: #646464;" >SOLICITAR</button>
                        </div>
                        `);
                }else if (valorSeleccionado == "22") {
                    $("#cuerpo").html(`
                        <div class="mb-3 w-100" title="Este campo es obligatorio">
                            <label for="input2" class="form-label col-form-label-lg fw-semibold">DETALLES DE LA AUTORIZACIÓN <span
                                    class="text-danger" style="font-size:20px;">*</span></label>
                            <textarea type="number" name="detalle" class="form-control form-control-lg" autocomplete="off" required></textarea>

                        </div>



                        <div class="mb-4 w-100" style="">
                            <label for="exampleInputEmail1" class="form-label col-form-label-lg fw-semibold">ADJUNTAR SOPORTE<span
                                class="text-danger" style="font-size:20px;"> *</span></label>
                            <input type="file" class="form-control" name="SoporteScore" id="SoporteScore" accept="application/pdf" required>
                        </div>
                        <div class="text-center">
                            <button id="agregar" type="submit" class="btn btn-primary fs-4 fw-bold" name="btnregistrar"
                                style="background-color: #646464;" >SOLICITAR</button>
                        </div>
                        `);
                }else{
                    $("#cuerpo").html(`
                        <div class="mb-3 w-100" title="Este campo es obligatorio" id="id">
                            <label for="input1" class="form-label col-form-label-lg fw-semibold">CÉDULA/NIT <span class="text-danger"
                                    style="font-size:20px;">*</span></label>
                            <p class="fw-bold fs-5">En caso tal de que sea un NIT escribirlo: 805.004.034 sin -9 (código de verificación).<span class="text-danger"> NOTA</span></p>
                            <input type="text" name="cedula" class="form-control form-control-lg" id="input1" autocomplete="off" autofocus
                                required>

                        </div>

                        <div class="mb-3 w-100" title="Este campo es obligatorio" id="id">
                            <label for="input1" class="form-label col-form-label-lg fw-semibold">NOMBRE PERSONA/EMPRESA <span class="text-danger"
                                    style="font-size:20px;">*</span></label>
                            <input type="text" name="nombre" class="form-control form-control-lg" id="input1" autocomplete="off" autofocus
                                required>

                        </div>

                        <div class="mb-3 w-100" title="Este campo es obligatorio" id="id">
                            <label for="input1" class="form-label col-form-label-lg fw-semibold">CUENTA <span class="text-danger"
                                    style="font-size:20px;">*</span></label>
                            <input type="text" name="cuenta" class="form-control form-control-lg" id="input1" placeholder="Si no tiene cuenta escribir N/A" autocomplete="off" autofocus
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
                            <input type="file" class="form-control" name="SoporteScore" id="SoporteScore" accept="application/pdf" required>
                        </div>
                        <div class="text-center">
                            <button id="agregar" type="submit" class="btn btn-primary fs-4 fw-bold" name="btnregistrar"
                                style="background-color: #646464;" >SOLICITAR</button>
                        </div>
                        `);
                }
                });

                        function enviarFormulario() {
                                const boton = document.getElementById("agregar");
                                boton.disabled = true;
                                return true;
                            }

                        function fileUploaded(id) {
                            // Obtiene el elemento input de tipo file dinámicamente
                            var fileInput = document.getElementById(`file_${id}`);

                            // Obtiene el nombre del archivo
                            var fileName = "";
                            if (fileInput.files.length > 0) {
                                fileName = fileInput.files[0].name;
                            }

                            // Muestra el mensaje de confirmación con el nombre del archivo
                            var uploadMessage = document.getElementById(`uploadMessage_${id}`);
                            uploadMessage.innerHTML = fileName + " subido.";
                            uploadMessage.style.display = "block";
                        }
        </script>