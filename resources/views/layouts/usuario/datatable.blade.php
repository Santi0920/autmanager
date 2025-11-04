        <!-- SCRIPT DATATABLE COMPLETO -->
        <script>
            $(function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            var table = $('#personas').DataTable({
                "ajax": {
                    "url": "{{ route('data.solicitudes') }}",
                    "data": function(d) {
                        d.search_term = $('#personas_filter input').val();
                    },
                },
                processing: true,




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
                        data: 'Fecha',
                        render: function(data, type, row) {

                        if('{{ session('rol') }}' == 'Gerencia'){

                            function parseFecha(fechaStr) {
                                if (!fechaStr) return null;

                                const months = {
                                    "enero": "01", "febrero": "02", "marzo": "03", "abril": "04", "mayo": "05",
                                    "junio": "06", "julio": "07", "agosto": "08", "septiembre": "09",
                                    "octubre": "10", "noviembre": "11", "diciembre": "12"
                                };

                                const parts = fechaStr.split(' ');
                                if (!parts || parts.length < 3) return null;

                                const month = months[parts[0]?.toLowerCase()] ?? "01";
                                const day = parts[1];
                                const [year, time] = parts[2]?.split('-') ?? [];

                                return new Date(`${year}-${month}-${day} ${time}`);
                            }

                            function calcularDiferencia(fechaSolicitud, fechaValidacion) {
                                const diff = fechaValidacion - fechaSolicitud;
                                const totalSeg = Math.floor(diff / 1000);
                                return {
                                    horas: String(Math.floor(totalSeg / 3600)).padStart(2, '0'),
                                    minutos: String(Math.floor((totalSeg / 60) % 60)).padStart(2, '0'),
                                    segundos: String(totalSeg % 60).padStart(2, '0')
                                };
                            }


                            // ✅ Selección correcta de fechas según el estado:
                            let fechaSolicitud = null;
                            let fechaValidacion = null;

                            if (row.ultimaRemitidoCorregir) {
                                // Caso de Corrección → Solo se muestra tiempo contra ahora
                                fechaSolicitud = parseFecha(row.ultimaRemitidoCorregir);
                            } else if (row.UltimaFechaDoneTramite) {
                                fechaSolicitud = parseFecha(row.UltimaFechaDoneTramite);
                            }

                            if (row.UltimaFechaCoordinacion) {
                                fechaValidacion = parseFecha(row.UltimaFechaCoordinacion);
                            }


                            // ✅ Construcción visual
                            let demoracoord = "";
                            let demoradireccion = "";

                            if (fechaSolicitud && !fechaValidacion) {
                                const dif = calcularDiferencia(fechaSolicitud, new Date());
                                demoradireccion = `<span class="">C#: <span class="text-dark fw-semibold">${dif.horas};${dif.minutos};${dif.segundos}.</span></span>`;
                            }

                            if (fechaSolicitud && fechaValidacion) {
                                const dif1 = calcularDiferencia(fechaSolicitud, fechaValidacion);
                                const dif2 = calcularDiferencia(fechaValidacion, new Date());

                                demoracoord = `<span title="Fecha Solicitud: ${row.UltimaFechaDoneTramite ?? row.ultimaRemitidoCorregir} . Fecha Validacion: ${row.UltimaFechaCoordinacion}" class="">
                                                    ${row.UltimaAreaCoordinacion}: 
                                                    <span class="text-dark fw-semibold">${dif1.horas};${dif1.minutos};${dif1.segundos}.</span>
                                                </span>`;

                                demoradireccion = `<span title="Fecha Validacion: ${row.UltimaFechaCoordinacion}" class="">
                                                    D. General:
                                                    <span class="text-dark fw-semibold">${dif2.horas};${dif2.minutos};${dif2.segundos}.</span>
                                                </span>`;
                            }


                            var Contenido = `
                                ${row.Concepto}
                                <div class="fw-bold text-primary">
                                    ${row.NumArea} - ${row.NomArea} - ${row.Usuario}
                                </div>
                                ${demoracoord}
                                ${demoradireccion}
                            `;

                        }else if('{{ session('rol') }}' == 'Coordinacion'){

                            function parseFecha(fechaStr) {
                                if (!fechaStr) return null;

                                const months = {
                                    "enero": "01", "febrero": "02", "marzo": "03", "abril": "04", "mayo": "05",
                                    "junio": "06", "julio": "07", "agosto": "08", "septiembre": "09",
                                    "octubre": "10", "noviembre": "11", "diciembre": "12"
                                };

                                const parts = fechaStr.split(' ');
                                if (!parts || parts.length < 3) return null;

                                const month = months[parts[0]?.toLowerCase()] ?? "01";
                                const day = parts[1];
                                const [year, time] = parts[2]?.split('-') ?? [];

                                if (!year || !time) return null;

                                return new Date(`${year}-${month}-${day} ${time}`);
                            }

                            function calcularDiferencia(fechaSolicitud, fechaValidacion) {
                                const diff = fechaValidacion - fechaSolicitud;
                                const totalSeg = Math.floor(diff / 1000);
                                return {
                                    horas: String(Math.floor(totalSeg / 3600)).padStart(2, '0'),
                                    minutos: String(Math.floor(totalSeg / 60) % 60).padStart(2, '0'),
                                    segundos: String(totalSeg % 60).padStart(2, '0')
                                };
                            }

                            let fechaSolicitud = null;
                            let fechaValidacion = null;

                            // ✅ Selección de fechas según situación actual
                            if (row.ultimaRemitidoCorregir) {
                                fechaSolicitud = parseFecha(row.ultimaRemitidoCorregir);
                            } else if (row.UltimaFechaDoneTramite) {
                                fechaSolicitud = parseFecha(row.UltimaFechaDoneTramite);
                            }

                            if (row.UltimaFechaCoordinacion) {
                                fechaValidacion = parseFecha(row.UltimaFechaCoordinacion);
                            }

                            let textoEstado = '';
                            if (fechaSolicitud && fechaValidacion) {
                                const dif = calcularDiferencia(fechaSolicitud, fechaValidacion);
                                textoEstado = `<span class="text-dark fw-semibold">
                                                    Coordinación:
                                                    <span class="fw-normal">${dif.horas};${dif.minutos};${dif.segundos}</span>
                                                </span>`;
                            } else {
                                textoEstado = `<span class="text-danger">${row.UltimoConceptoID == '17' ? `` : `Falta por validar ó N/A` }</span>`;
                            }

                            var Contenido = `
                                ${row.Concepto}
                                <div class="fw-bold text-primary">
                                    ${row.NumArea} - ${row.NomArea} - ${row.Usuario}
                                    <div>${textoEstado}</div>
                                </div>
                            `;



                        }else{
                            var Contenido = `${row.UltimoConcepto}<div class="fw-bold text-primary">${row.NumArea} - ${row.NomArea} - ${row.Usuario}
                                    <div>
                                        <span class="text-dark" title="Fecha Solicitud">
                                        ${row.FechaStringEstado.charAt(0).toUpperCase() + row.FechaStringEstado.slice(1)}
                                        </span>
                                    </div>
                                </div>
                            `
                        }
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
                            if (ultimoEstado == "REMITIDO" || ultimoEstado == "VALIDADO") {
                                var Estado =
                                    '<div class="btn btn-info shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;"><span class="d-none">1</span>REMITIDO A GERENCIA</div>';
                            }else if (ultimoEstado == "CORREGIR") {
                                var Estado =
                                    '<div class="btn btn-primary shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;"><span class="d-none">1</span>CORREGIR</div>';
                            } else if (ultimoEstado == "TRÁMITE") {
                                var Estado =
                                    `<div class="btn btn-warning shadow" style="padding: 0.4rem 1.4rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">EN TRAMITE</div>`
                            }else if (ultimoEstado == "APROBADO") {
                                var Estado =
                                    '<div class="btn btn-success blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">APROBADO POR GERENCIA</div>'
                            }else if (ultimoEstado == "RECIBIDO") {
                                var Estado =
                                    '<div class="btn btn-success blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">RECIBIDO</div>'
                            }else if (ultimoEstado == "ENTERADO") {
                                var Estado =
                                    '<div class="btn btn-success blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">ENTERADO</div>'
                            } else if (ultimoEstado == "ANULADO") {
                                var Estado =
                                    '<div class="btn btn-info blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">ANULADO</div>'
                            } else if (ultimoEstado == "STAND BY") {
                                var Estado =
                                    '<div class="btn btn-dark blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">STAND BY</div>'
                            }else if (ultimoEstado == "DESBLOQUEADO") {
                                var Estado =
                                    '<div class="btn btn-secondary blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">DESBLOQUEADO</div>'
                            }else if (ultimoEstado == "ENVIADO") {
                                var Estado =
                                    '<div class="btn btn-secondary blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">ENVIADO</div>'
                            }   else {
                                var Estado =
                                    '<div class="btn btn-danger shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;"><span class="d-none">1</span>BLOQUEADO</div>'
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
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {

                            var id = row.IDAutorizacion; // Obtener el ID de la fila

                            const cedula = row.Cedula;

                            document.querySelectorAll('.modal').forEach(modal => {
                                const radios = modal.querySelectorAll('input[name^="Estado"]'); // solo radios dentro del modal
                                const enviarDiv = modal.querySelector('.enviarselect'); // div relativo al modal

                                radios.forEach(radio => {
                                    radio.addEventListener('change', () => {
                                        if (radio.value === 'ENVIAR A' && radio.checked) {
                                            enviarDiv.classList.remove('d-none'); // Mostrar
                                        } else if (radio.checked) {
                                            enviarDiv.classList.add('d-none'); // Ocultar
                                        }
                                    });
                                });
                            });
                            document.addEventListener('click', function(e) {
                                if (e.target && e.target.id === 'btnScroll') {
                                    const anchor = document.getElementById('anchor-scroll');
                                    if (anchor) {
                                        anchor.scrollIntoView({
                                            behavior: 'smooth',
                                            block: 'center'
                                        });
                                    }
                                }
                            });



                            var modalEditar = `
                            <a type="button" class="btn btn-outline-secondary" id="modalLink_${id}" data-bs-toggle="modal" data-bs-target="#exampleModal_${id}"
                                        data-id="${id}">
                                        <i class="fa-solid fa-eye fs-5"></i>
                            </a>


                            {{-- MODAL --}}
                            <div class="modal fade bd-example-modal-lg" id="exampleModal_${id}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header text-center">
                                        <h6 class="modal-title text-light" id="exampleModalLongTitle"
                                            style="font-weight: 700;font-size: 22px">DETALLE DE LA AUTORIZACIÓN 
                                            </h6>
                                                ${
                                                    row.historial
                                                        .map((item, index) => {
                                                            let contenido = `

                                                            `;

                                                            // Insertar boton cuando llegue al 5to elemento (índice 4)
                                                            if (index === 3) {
                                                                contenido += `
                                                                <button type="button" id="btnScroll" class="btn btn-dark fw-bold fs-5 ms-3">
                                                                    Desplazar al Final
                                                                </button>
                                                                `;
                                                            }

                                                            return contenido;
                                                        })
                                                        .join('')
                                                }

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
                                                    <div class="col-md-7 col-lg-9 bg-primary-subtle d-flex align-items-center justify-content-center p-2">
                                                        <span class="h2 fw-bold">${row.UltimoConceptoID == '17' ? `REPORTE` : `SOLICITUD`}</span>
                                                    </div>
                                                    <div class="col-md-5 col-lg-3">
                                                    <div class="row g-0 justify-content-center border p-2">
                                                        <span class="h3 fw-bold mb-0 text-danger">No.${row.IDAutorizacion}</span>
                                                    </div>

                                                        <div class="row g-0 align-items-center justify-content-center border p-2">
                                                            ${row.UltimoEstado == "TRÁMITE"?
                                                                `<button class="btn btn-warning shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">T - EN TRAMITE</button>` :
                                                                row.UltimoEstado == "APROBADO" ?
                                                                `<button class="btn btn-success  shadow blink" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">AP - APROBADO</button>` :
                                                                row.UltimoEstado == "ENTERADO" ?
                                                                `<button class="btn btn-success  shadow blink" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">E - ENTERADO</button>` :
                                                                row.UltimoEstado == "CORREGIR" ?
                                                                `<button class="btn btn-primary shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">C - CORREGIR</button>` :
                                                                row.UltimoEstado == "ANULADO" ?
                                                                '<button class="btn btn-info shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">AN - ANULADO</button>' :
                                                                row.UltimoEstado == "STAND BY" ?
                                                                '<button class="btn btn-dark shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">STAND BY</button>' :
                                                                row.UltimoEstado == "REMITIDO" || row.UltimoEstado == "VALIDADO" ?
                                                                '<button class="btn btn-info shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">REMITIDO A GERENCIA</button>' :
                                                                row.UltimoEstado == "DESBLOQUEADO" ?
                                                                '<button class="btn btn-secondary shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">DESBLOQUEADO</button>' :
                                                                row.UltimoEstado == "ENVIADO" ?
                                                                '<button class="btn btn-secondary shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">ENVIADO</button>' :
                                                                row.UltimoEstado == "RECIBIDO" ?
                                                                `<button class="btn btn-success  shadow blink" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">RECIBIDO</button>` :
                                                                '<button class="btn btn-danger shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">BLOQUEADO</button>'
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
                                                    if (item.Estado === 'TRÁMITE' || item.Estado === 'DONE' || item.Estado === 'REMITIDO'  || item.Estado === 'REMITIDOCONFIRMADO' || item.Estado == 'REMITIDOCORREGIR' && item.Estado !== 'VALIDADO') {
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
                                                            <span class="fs-5">${item.NumArea} - ${item.NomArea}(<b>${item.CodigoUsuario}</b>) - <b>${item.Nombre}</b><br>👉(Click para mostrar)👈</span>
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
                                                                ${row.UltimoEstado == 'CORREGIR' && (item.Estado != 'REMITIDOCONFIRMADO') && item.Estado !== 'DONE'  ? `
                                                                    <div class="mb-3 w-100" id="id">
                                                                        <select class="form-select form-select-lg" name="tautorizacionmodal" id="autorizacionesmodal${row.IDAutorizacion}" 
                                                                            onChange="autorizacionesModalChange(${row.IDAutorizacion},'${item.Cedula}','${item.CuentaAsociado}', '${item.NombrePersona}', '${item.Convencion}', event)" required>
                                                                            <option selected class="fw-bold" value="${item.ID_Concepto}">**Concepto Actual** -> ${item.Concepto}</option>
                                                                            @include('layouts.optionmodal')
                                                                        </select>
                                                                    </div>
                                                                ` : `<span class="fs-5">${item.Concepto} - @include('layouts.optionvercodigo')</span>`}
                                                            </div>
                                                            <div class="col-sm-6 col-md-3 col-lg-3 d-flex align-items-center justify-content-center border p-3">
                                                                ${item.ID_Concepto == 41 ? `<span class="fs-5 fw-bold mb-0">@include('layouts.optionverconvenciones') - ${item.Convencion}</span>` : ``}
                                                            </div>
                                                        </div>


                                                    
                                                        ${
                                                            (row.UltimoEstado == 'CORREGIR' && (item.Estado != 'REMITIDOCONFIRMADO') && item.Estado !== 'DONE'
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
                                                                    
                                                                : (item.Estado == 'DONE' || item.Estado == 'TRÁMITE'|| item.Estado == 'REMITIDOCONFIRMADO' || item.Estado == 'REMITIDO')
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
                                                            ${row.UltimoEstado == 'CORREGIR' && (item.Estado != 'REMITIDOCONFIRMADO') && item.Estado !== 'DONE'  ? `
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
                                            // Coordinación para VALIDAR o Gerencia para VALIDAR JEFATURA
                                            ((item.Estado === 'TRÁMITE' && '{{ session('rol') }}' === 'Coordinacion' && item.Observaciones !== 'NADA') || (item.NumArea == 'Jefatura' && '{{ session('rol') }}' == 'Gerencia') && (row.UltimoEstado != "CORREGIR" && row.UltimoEstado != "APROBADO" && row.UltimoEstado != "VALIDADO" && item.Estado != "DONE"))
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
                                                                    <input 
                                                                        type="radio" 
                                                                        name="Estado" 
                                                                        required
                                                                        value="{{ session('rol') == 'Gerencia' ? 'CORREGIRJEFATURA' : 'CORREGIR' }}"
                                                                    >
                                                                    <span>RECHAZAR</span>
                                                                </label>
                                                            </div>

                                                            <div class="col-sm-12 col-md-12 col-lg-10">
                                                                <div class="row g-0 justify-content-center">
                                                                    <div class="row g-0 row-cols-2 justify-content-center">
                                                                        <div class="col-md-9 d-flex align-items-center justify-content-start border p-2">
                                                                            <span class="fs-5"><b>{{ session('agenciau') === 'Gerencia General' ? 'Dirección General' : session('agenciau') }} - {{ session('name') }}</b></span>
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

                                                                    <div class="estado-container">

                                                                        ${row.UltimoConceptoID == '17' ?
                                                                        `<label class="label">
                                                                            <input value="ENTERADO" type="radio" name="Estado" required>
                                                                            <span>ENTERADO</span>
                                                                        </label>` :
                                                                        `<label class="label">
                                                                            <input value="APROBADO" type="radio" name="Estado" required>
                                                                            <span>APROBAR</span>
                                                                        </label>`
                                                                        }

                                                                        <label class="label">
                                                                            <input value="CORREGIR" type="radio" name="Estado" required>
                                                                            <span>RECHAZAR</span>
                                                                        </label>
                                                                        <label class="label">
                                                                            <input value="BLOQUEADO" type="radio" name="Estado" required>
                                                                            <span>BLOQUEAR</span>
                                                                        </label>
                                                                        <label class="label">
                                                                            <input value="STAND BY" type="radio" name="Estado" required>
                                                                            <span>STAND BY</span>
                                                                        </label>
                                                                        <label class="label">
                                                                            <input value="ENVIAR A" type="radio" name="Estado" required>
                                                                            <span>ENVIAR A</span>
                                                                        </label>

                                                                    </div>

                                                                </div>


                                                                <div class="col-sm-12 col-md-12 col-lg-10">
                                                                    <div class="row g-0 justify-content-center">
                                                                        <div class="row g-0 row-cols-2 justify-content-center">
                                                                            <div class="col-md-9 d-flex align-items-center justify-content-start border p-2">
                                                                                <span class="fs-5"><b>{{ session('agenciau') === 'Gerencia General' ? 'Dirección General' : session('agenciau') }} - {{ session('name') }}</b></span>
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
                                                                            required
                                                                        >
                                                                    </div>
                                                                    <div class="col-md-12 enviarselect d-none">
                                                                        <select class="form-select form-select-lg border border-danger-subtle bg-secondary-subtle fw-bold text-dark w-100 w-sm-100 p-3" name="Destinatario">
                                                                            <option value="" selected disabled>→ Seleccionar funcionario a enviar... ←</option>
                                                                            @foreach ($usuariosEnviara as $usuario)
                                                                                <option value="{{$usuario->id}}">{{$usuario->name}} - {{$usuario->agenciau}} - {{$usuario->codigo}}</option>
                                                                            @endforeach
                                                                        </select>
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
                                                item.Estado === 'APROBADO' || item.Estado === 'ENTERADO' || item.Estado === 'RECIBIDO' ? 'bg-success-subtle' :
                                                item.Estado === 'CORREGIR' ? 'bg-primary-subtle' :
                                                item.Estado === 'ANULADO' ? 'bg-info-subtle' :
                                                item.Estado === 'REMITIDO' ? 'bg-info-subtle' :
                                                item.Estado == 'BLOQUEADO' ? 'bg-danger-subtle' :
                                                item.Estado == 'STAND BY' ? 'bg-dark-subtle' :
                                                'bg-secondary-subtle'
                                            } border p-2 border border-dark" title="${item.Estado}">
                                                <span class="h1 fw-bold mb-0">
                                                    ${item.Estado[0]}<br>
                                                    <span class="fs-5 fw-normal">
                                                        ${item.Estado == "VALIDADOCONFIRMADO" 
                                                            ? "VALIDADO" 
                                                            : item.Estado == "REMITIDOCONFIRMADO" 
                                                            ? "REMITIDO" 
                                                            : item.Estado == "ENVIADO" 
                                                            ? "ENVIADO(DR)" 
                                                            : item.Estado}
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="col-sm-12 col-md-12 col-lg-10">
                                                <div class="row g-0">
                                                    <div class="text-start col-md-9 d-flex align-items-center border p-2">
                                                        <span class="fs-5 mb-0">${item.NomArea}(<b>${item.CodigoUsuario == null ? '':item.CodigoUsuario}</b>) - <b>${item.Nombre ?? 'N/A'}</b></span>
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
                                        
                                        ${((('{{ session('rol') }}' === 'Gerencia') && item.ID === row.historial[row.historial.length - 1].ID && (row.UltimoEstado === 'VALIDADO' || row.UltimoEstado === 'DESBLOQUEADO'))
                                                    ? 
                                                    `
                                                        <form enctype="multipart/form-data" id="formValidarGerenciaAutorizacion${row.IDAutorizacion}" data-id="${row.IDAutorizacion}">
                                                            @csrf
                                                            <div class="row g-0">
                                                                <div class="col-sm-12 col-md-12 col-lg-2 d-flex flex-column align-items-center align-items-lg-start justify-content-start border p-3 border-dark bg-dark-subtle">
                                                                    <div class="estado-container">
                                                                        ${row.UltimoConceptoID == '17' ?
                                                                            `<label class="label">
                                                                                <input value="ENTERADO" type="radio" name="Estado" required>
                                                                                <span>ENTERADO</span>
                                                                            </label>` :
                                                                            `<label class="label">
                                                                                <input value="APROBADO" type="radio" name="Estado" required>
                                                                                <span>APROBAR</span>
                                                                            </label>`
                                                                        }
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
                                                                        <label class="label">
                                                                            <input value="ENVIAR A" type="radio" name="Estado" id="estado_enviara" required>
                                                                            <span>ENVIAR A</span>
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-12 col-md-12 col-lg-10">
                                                                    <div class="row g-0 justify-content-center">
                                                                        <div class="row g-0 row-cols-2 justify-content-center">
                                                                            <div class="col-md-9 d-flex align-items-center justify-content-start border p-2">
                                                                                <span class="fs-5"><b>{{ session('agenciau') === 'Gerencia General' ? 'Dirección General' : session('agenciau') }} - {{ session('name') }}</b></span>
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
                                                                            required
                                                                        >
                                                                    </div>
                                                                    
                                                                    <div class="col-md-12 enviarselect d-none">
                                                                        <select class="form-select form-select-lg border border-danger-subtle bg-secondary-subtle fw-bold text-dark w-100 w-sm-100 p-3" name="Destinatario">
                                                                            <option value="" selected disabled>→ Seleccionar funcionario a enviar... ←</option>
                                                                            @foreach ($usuariosEnviara as $usuario)
                                                                                <option value="{{$usuario->id}}">{{$usuario->name}} - {{$usuario->agenciau}} - {{$usuario->codigo}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                        </form>
                                                    
                                                    
                                                    `
                                        :  (
                                                '{{ session("rol") }}' === 'Gerencia'
                                                && row.historial && row.historial.length
                                                && item.ID === row.historial[row.historial.length - 1].ID
                                                && (row.UltimoEstado === 'BLOQUEADO' || row.UltimoEstado === 'STAND BY' || row.UltimoEstado === 'CORREGIR')
                                            )
                                        ?
                                                    `
                                                        <form enctype="multipart/form-data" id="formValidarGerenciaAutorizacion${row.IDAutorizacion}" data-id="${row.IDAutorizacion}">
                                                            @csrf
                                                            <div class="row g-0">
                                                                <div class="col-sm-12 col-md-12 col-lg-2 d-flex flex-column align-items-center align-items-lg-start justify-content-start border p-3 border-dark bg-dark-subtle">
                                                                    ${row.UltimoEstado == 'BLOQUEADO' ?
                                                                    `<label class="label">
                                                                        <input value="DESBLOQUEADO" type="radio" name="Estado" id="estado_desbloquear" required>
                                                                        <span>DESBLOQUEAR</span>
                                                                    </label>`:``}
                                                                     <label class="label">
                                                                        <input value="ANULADO" type="radio" name="Estado" id="estado_anular" required>
                                                                        <span>ANULAR</span>
                                                                    </label>
                                                                </div>

                                                                <div class="col-sm-12 col-md-12 col-lg-10">
                                                                    <div class="row g-0 justify-content-center">
                                                                        <div class="row g-0 row-cols-2 justify-content-center">
                                                                            <div class="col-md-9 d-flex align-items-center justify-content-start border p-2">
                                                                                <span class="fs-5"><b>{{ session('agenciau') === 'Gerencia General' ? 'Dirección General' : session('agenciau') }} - {{ session('name') }}</b></span>
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
                                                    
                                                    : (
                                                            '{{ session("rol") }}' !== 'Gerencia' && row.UltimoEstado !== 'RECIBIDO'
                                                            && row.historial && row.historial.length
                                                            && row.ultimoEnviadoa === '{{ session("name") }}'
                                                            && item.ID === row.historial[row.historial.length - 1].ID
                                                            && (row.UltimoEstado === 'ENVIADO')
                                                        )
                                                    ? 
                                                    `
                                                    
                                                        <form enctype="multipart/form-data" id="formValidarAutorizacion${row.IDAutorizacion}" data-id="${row.IDAutorizacion}">
                                                            @csrf
                                                            <div class="row g-0">
                                                                <div class="col-sm-12 col-md-12 col-lg-2 d-flex flex-column align-items-center align-items-lg-start justify-content-start border p-3 border-dark bg-dark-subtle">
                                                                     <label class="label">
                                                                        <input value="RECIBIDO" type="radio" name="Estado" id="estado_recibido" required>
                                                                        <span>RECIBIDO</span>
                                                                    </label>
                                                                </div>

                                                                <div class="col-sm-12 col-md-12 col-lg-10">
                                                                    <div class="row g-0 justify-content-center">
                                                                        <div class="row g-0 row-cols-2 justify-content-center">
                                                                            <div class="col-md-9 d-flex align-items-center justify-content-start border p-2">
                                                                                <span class="fs-5"><b>{{ session('agenciau') === 'Gerencia General' ? 'Dirección General' : session('agenciau') }} - {{ session('name') }}</b></span>
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
                                                                            required
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    
                                                    
                                                    `
                                                    :
                                                    
                                                    ``)
                                        
                                        
                                        }`;
                                                })
                                                .join('')
                                        }



                                        </div>
                                ${//BOTONES
                                    row.UltimoEstado == 'CORREGIR'  && '{{ session('rol') }}' !== 'Coordinacion' && '{{ session('rol') }}' !== 'Gerencia'
                                        ? `
                                        <div class="text-center p-3">
                                            <button id="boton${row.IDAutorizacion}" 
                                                name="btnregistrar" 
                                                type="button"
                                                class="btn btn-premium-action"
                                                onclick="formEditarAutorizacion(${row.IDAutorizacion}, event)">
                                                <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Cambios
                                            </button>
                                        </div>
                                        `
                                        :   ((row.UltimoEstado === 'REMITIDO' || row.UltimoEstado === 'VALIDADO' || row.UltimoEstado == 'CORREGIR' || row.UltimoEstado == 'STAND BY' || row.UltimoEstado == 'BLOQUEADO' || row.UltimoEstado == 'DESBLOQUEADO') && '{{ session('rol') }}' === 'Gerencia')

                                            ? `
                                                <div class="text-center p-3">
                                                    <button id="boton${row.IDAutorizacion}" 
                                                        name="btnregistrar" 
                                                        type="button"
                                                        class="btn btn-premium-action"
                                                        onclick="formValidarGerenciaAutorizacion(${row.IDAutorizacion}, event)">
                                                        <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Cambios
                                                    </button>
                                                </div>
                                            `
                                        : 
                                        (
                                            row.UltimoEstado === 'TRÁMITE' &&
                                            '{{ session('rol') }}' === 'Coordinacion'
                                        )
                                        ||
                                        (
                                            row.NumArea === 'Jefatura' &&
                                            '{{ session('rol') }}' === 'Gerencia' &&
                                            row.UltimoEstado !== "CORREGIR" &&
                                            row.UltimoEstado !== "APROBADO" &&
                                            row.UltimoEstado !== "VALIDADO" &&
                                            row.UltimoEstado !== "DONE" &&
                                            row.UltimoEstado !== "ANULADO" &&
                                            row.UltimoEstado !== "ENVIADO"
                                        )
                                        ||
                                        (
                                            row.ultimoEnviadoa === '{{ session("name") }}' &&
                                            '{{ session("rol") }}' !== 'Gerencia' &&
                                            row.UltimoEstado !== 'RECIBIDO'
                                        )
                                            ? `

                                            <div class="text-center p-3">
                                                <button id="boton${row.IDAutorizacion}" 
                                                    name="btnregistrar" 
                                                    type="button"
                                                    class="btn btn-premium-action"
                                                    onclick="formValidarAutorizacion(${row.IDAutorizacion}, event)">
                                                    <i class="fa-solid fa-floppy-disk me-2"></i>Validar
                                                </button>
                                            </div>
    `
                                        : (row.EstadoRemitidoBoton === 'REMITIDOCORREGIR' && '{{ session('rol') }}' === 'Coordinacion')
                                            ? `
                                            <div class="text-center p-3">
                                                <button id="boton${row.IDAutorizacion}" 
                                                    name="btnregistrar" 
                                                    type="button"
                                                    class="btn btn-premium-action"
                                                    onclick="formEditarAutorizacion(${row.IDAutorizacion}, event)">
                                                    <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Cambios
                                                </button>
                                            </div>
                                            `:''
                                }
                                            <span id="anchor-scroll"></span>
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
                    [5,10],
                    [5,10]
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
                            <i class='fa-solid fa-table'></i> Mostrar:
                        </span> _MENU_
                    `,
                    "zeroRecords": `
                        <div class='py-4 text-center'>
                            <i class='fa-solid fa-circle-exclamation' style='font-size: 55px; color: #dc3545;'></i>
                            <div style='font-size: 24px; font-weight: 600; margin-top: 10px; color: #343a40;'>
                                No se encontraron autorizaciones
                            </div>
                            <p style='font-size: 15px; color: #6c757d; margin-top: 6px;'>
                                Verifica los filtros o realiza una nueva búsqueda
                            </p>
                        </div>
                    `,
                    "info": `
                        <span style="
                            font-weight: 700;
                            color: #005e56;
                            font-size: 14.5px;
                            letter-spacing: .3px;
                        ">
                            Página <b>_PAGE_</b> de <b>_PAGES_</b>
                        </span>
                    `,
                    "infoEmpty": `
                        <span style="
                            font-weight: 700;
                            color: #a30000;
                            font-size: 14.5px;
                        ">
                            No hay registros disponibles
                        </span>
                    `,
                    "infoFiltered": `
                        <span style="
                            font-size: 13px;
                            font-weight: 500;
                            color: #6c757d;
                        ">
                            (Filtrado de <b>_MAX_</b> registros totales)
                        </span>
                    `,
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
                            🔍 Buscar Autorización:
                        </span>
                    `,
                    "paginate": {
                        "next": `
                            <span style="
                                font-weight: 600;
                                color: #005e56;
                                font-size: 14px;
                                letter-spacing: .3px;
                            ">
                                Siguiente →
                            </span>
                        `,
                        "previous": `
                            <span style="
                                font-weight: 600;
                                color: #005e56;
                                font-size: 14px;
                                letter-spacing: .3px;
                            ">
                                ← Anterior
                            </span>
                        `
                    }

                },
                "initComplete": function(settings, json) {
                var buttonsHtml = `
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">

                    <!-- BOTÓN ACTUALIZAR -->
                    <button id="btnT" class="btn btn-primary shadow-sm fw-bold d-flex align-items-center justify-content-center gap-1 btn-filter"
                            title="ACTUALIZAR INFORMACIÓN" style="transition: transform 0.2s;">
                        <i class="fa-solid fa-rotate-right"></i>
                        <span class="d-none d-md-inline">ACTUALIZAR</span>
                    </button>

                    ${
                        ('{{ session('rol') }}' === 'Gerencia') ? `
                        <!-- BOTÓN COORDINACIÓN 9 -->
                        <button id="btnC9" class="btn btn-secondary shadow-sm fw-bold btn-filter" title="COORDINACIÓN 9"
                                style="transition: transform 0.2s;">COORDINACIÓN 9</button>

                        <!-- DROPDOWN STAND BY -->
                        <div class="dropdown d-inline">
                            <button class="btn btn-dark shadow-sm fw-bold dropdown-toggle btn-filter" type="button" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-expanded="false" title="Solicitudes de jefaturas">
                                STAND BY
                            </button>
                            <ul class="dropdown-menu shadow">
                                <li><a class="dropdown-item fw-bold" href="#" id="btnStandBy">VER</a></li>
                                <li><a class="dropdown-item fw-bold" href="{{ route('datager.aprobarstandby') }}" id="btnAprobarTodos">APROBAR TODOS</a></li>
                            </ul>
                        </div>

                        <!-- BOTONES DE ESTADO -->
                        <button id="btnA" class="btn btn-success shadow-sm fw-bold btn-filter" title="APROBADOS" style="transition: transform 0.2s;">APROBADOS</button>
                        <button id="btnR" class="btn btn-danger shadow-sm fw-bold btn-filter" title="RECHAZADOS" style="transition: transform 0.2s;">RECHAZADOS</button>
                        <button id="btnTramite" class="btn btn-warning shadow-sm fw-bold btn-filter" title="EN TRÁMITE" style="transition: transform 0.2s;">EN TRÁMITE</button>
                        <button id="btnBloqueado" class="btn btn-danger shadow-sm fw-bold btn-filter" title="BLOQUEADOS" style="transition: transform 0.2s;">BLOQUEADOS</button>
                        <button id="btnAnulado" class="btn btn-info shadow-sm fw-bold btn-filter" title="ANULADOS" style="transition: transform 0.2s;">ANULADOS</button>
                        <button id="btnEnviados" class="btn btn-secondary shadow-sm fw-bold btn-filter" title="ENVIADOS" style="transition: transform 0.2s;">ENVIADOS</button>
                        ` :
                        `
                        <!-- BOTONES USUARIOS -->
                        <button id="btnA" class="btn btn-success shadow-sm fw-bold btn-filter" title="APROBADOS" style="transition: transform 0.2s;">APROBADOS</button>
                        <button id="btnAnulado" class="btn btn-info shadow-sm fw-bold btn-filter" title="ANULADOS" style="transition: transform 0.2s;">ANULADOS</button>
                        <button id="btnStandBy" class="btn btn-dark shadow-sm fw-bold btn-filter" title="STAND BY" style="transition: transform 0.2s;">STAND BY</button>
                        `
                    }
                </div>`


            var lastAjaxUrl = '{{ route("data.solicitudes") }}';

            function setActiveButton(btnId) {
                $(".btn-filter").removeClass("active"); // Quita la clase a todos
                $(btnId).addClass("active"); // Activa solo el seleccionado
            }
            $('#personas_filter input').on('keyup').on('keyup', function() {
                var searchValue = $(this).val().trim();

                if(searchValue === '') {
                    table.ajax.url(lastAjaxUrl).load();
                } else {
                    table.ajax.url('{{ route("data.solicitudes") }}?search=' + encodeURIComponent(searchValue)).load();
                }

                console.log('Valor enviado al servidor:', searchValue);
            });

            
            $(buttonsHtml).prependTo('.dataTables_filter');
                $('#btnT').on('click', function() {
                    lastAjaxUrl = '{{ route("data.solicitudes") }}';
                    table.ajax.url(lastAjaxUrl).load();
                    setActiveButton('#btnT');
                });

                $('#btnC9').on('click', function() {
                    lastAjaxUrl = '{{ route("data.c9") }}';
                    table.ajax.url(lastAjaxUrl).load();
                    setActiveButton('#btnC9');
                });

                $('#btnA').on('click', function() {
                    lastAjaxUrl = '{{ route("data.aprobados") }}';
                    table.ajax.url(lastAjaxUrl).load();
                    setActiveButton('#btnA');
                });

                $('#btnR').on('click', function() {
                    lastAjaxUrl = '{{ route("data.rechazados") }}';
                    table.ajax.url(lastAjaxUrl).load();
                    setActiveButton('#btnR');
                });

                $('#btnTramite').on('click', function() {
                    lastAjaxUrl = '{{ route("data.tramite") }}';
                    table.ajax.url(lastAjaxUrl).load();
                    setActiveButton('#btnTramite');
                });

                $('#btnBloqueado').on('click', function() {
                    lastAjaxUrl = '{{ route("data.bloqueados") }}';
                    table.ajax.url(lastAjaxUrl).load();
                    setActiveButton('#btnBloqueado');
                });

                $('#btnAnulado').on('click', function() {
                    lastAjaxUrl = '{{ route("data.anulados") }}';
                    table.ajax.url(lastAjaxUrl).load();
                    setActiveButton('#btnAnulado');
                });

                $('#btnStandBy').on('click', function() {
                    lastAjaxUrl = '{{ route("data.standby") }}';
                    table.ajax.url(lastAjaxUrl).load();
                    setActiveButton('#btnStandBy');
                });

                $('#btnEnviados').on('click', function() {
                    lastAjaxUrl = '{{ route("data.enviado") }}';
                    table.ajax.url(lastAjaxUrl).load();
                    setActiveButton('#btnEnviados');
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
                            url: "/autmanager/public/solicitudes/actualizar-" + id,
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
                    url: "/autmanager/public/solicitudes/actualizar-" + id,
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

                if (form.data('submitted')) return;

                form.data('submitted', true);

                var formDataArray = form.serializeArray();

                var estado, observaciones, destinatario;

                formDataArray.forEach(function(input) {

                    if (input.name === "Estado") {
                        estado = input.value;
                    } 
                    else if (input.name === "Observaciones") {
                        observaciones = input.value;
                    }
                    else if (input.name === "Destinatario") {
                        destinatario = input.value;
                    }
                });

                console.log("Estado:", estado, "Observaciones:", observaciones, "Destinatario:", destinatario);

                if (typeof estado === 'undefined') {
                    alert('Por favor, seleccione un estado.');
                    form.data('submitted', false);
                    return;
                }

                $.ajax({
                    url: "{{ route('update.autorizacion', ['id' => ':id']) }}".replace(':id', id),
                    type: "POST",
                    data: {
                        Observaciones: observaciones,
                        Estado: estado,
                        Destinatario: destinatario,
                        _token: $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        if (response) {
                            $(`#exampleModal_${id}`).modal('hide');
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
                        console.log('Error', error);
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

                const valorSeleccionado = $(this).val();
                const iconRequired = '<span class="text-danger" style="font-size:20px;">*</span>';

                let contenido = '';

                if (valorSeleccionado == "41") {
                    contenido = `
                    <div class="mb-4">
                        <label class="form-label fw-semibold">CÉDULA ${iconRequired}</label>
                        <input type="number" name="cedula" class="form-control form-control-lg shadow-sm" placeholder="Ingrese su cédula" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">NOMBRE PERSONA/EMPRESA ${iconRequired}</label>
                        <input type="text" name="nombre" class="form-control form-control-lg shadow-sm" placeholder="Nombre completo o empresa" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">CUENTA ${iconRequired}</label>
                        <input type="text" name="cuenta" class="form-control form-control-lg shadow-sm" placeholder="Si no tiene cuenta escriba N/A" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">DETALLES DE LA AUTORIZACIÓN ${iconRequired}</label>
                        <textarea name="detalle" class="form-control form-control-lg shadow-sm" rows="4" placeholder="Describa los detalles de la autorización" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">CONVENCIONES ${iconRequired}</label>
                        <input type="text" name="convencion" class="form-control form-control-lg shadow-sm" placeholder="Ingrese las convenciones" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">ADJUNTAR CAPTURA DE AS400 ${iconRequired}</label>
                        <input type="file" class="form-control shadow-sm" name="SoporteScore" accept="application/pdf" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-gradient-primary btn-lg fw-bold w-50">SOLICITAR</button>
                    </div>
                    `;
                } else if (valorSeleccionado == "22") {
                    contenido = `
                    <div class="mb-4">
                        <label class="form-label fw-semibold">DETALLES DE LA AUTORIZACIÓN ${iconRequired}</label>
                        <textarea name="detalle" class="form-control form-control-lg shadow-sm" rows="4" placeholder="Describa los detalles de la autorización" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">ADJUNTAR SOPORTE ${iconRequired}</label>
                        <input type="file" class="form-control shadow-sm" name="SoporteScore" accept="application/pdf" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-gradient-primary btn-lg fw-bold w-50">SOLICITAR</button>
                    </div>
                    `;
                } else {
                    contenido = `
                    <div class="mb-4">
                        <label class="form-label fw-semibold">CÉDULA/NIT ${iconRequired}</label>
                        <p class="text-muted fs-6">Si es NIT, escribir sin el código verificación. Ej: 805.004.034</p>
                        <input type="text" name="cedula" class="form-control form-control-lg shadow-sm" placeholder="Cédula o NIT" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">NOMBRE PERSONA/EMPRESA ${iconRequired}</label>
                        <input type="text" name="nombre" class="form-control form-control-lg shadow-sm" placeholder="Nombre completo o empresa" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">CUENTA ${iconRequired}</label>
                        <input type="text" name="cuenta" class="form-control form-control-lg shadow-sm" placeholder="Si no tiene cuenta escriba N/A" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">DETALLES DE LA AUTORIZACIÓN ${iconRequired}</label>
                        <textarea name="detalle" class="form-control form-control-lg shadow-sm" rows="4" placeholder="Describa los detalles de la autorización" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">ADJUNTAR SOPORTE ${iconRequired}</label>
                        <input type="file" class="form-control shadow-sm" name="SoporteScore" accept="application/pdf" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-gradient-primary btn-lg fw-bold w-50">SOLICITAR</button>
                    </div>
                    `;
                }

                $("#cuerpo").html(contenido);
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