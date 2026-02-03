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
                scrollY: 440,

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
                            let fechaCoordinacion = null;

                            if(row.UltimaFechaDoneTramite) {
                                fechaSolicitud = parseFecha(row.UltimaFechaDoneTramite);
                            }

                            if (row.UltimaFechaCoordinacion) {
                                fechaValidacion = parseFecha(row.UltimaFechaCoordinacion);
         
                            }
                            if (row.UltimaFechaCoordinacion && row.UltimoEstado == "REMITIDO") {
                                fechaCoordinacion = parseFecha(row.UltimaFechaCoordinacion);
                            }


                            // ✅ Construcción visual
                            let demoracoord = "";
                            let demoradireccion = "";

                            if (fechaSolicitud && !fechaValidacion || row.UltimaAreaCoordinacion == 'C9' && row.UltimoEstado != 'VALIDADO') {
                                const dif = calcularDiferencia(fechaSolicitud, new Date());
                                demoradireccion = `<span class="">C#: <span class="text-dark fw-semibold">${dif.horas};${dif.minutos};${dif.segundos}.</span></span>`;
                            }

                            if ((fechaSolicitud && fechaValidacion && row.UltimaAreaCoordinacion != 'C9') || row.UltimoEstado == 'VALIDADO') {
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

                            if (fechaCoordinacion) {

                                const dif2 = calcularDiferencia(fechaCoordinacion, new Date());

                                demoradireccion = `<span title="Fecha Validacion: ${row.UltimaFechaCoordinacion}" class="">
                                                    D. General:
                                                    <span class="text-dark fw-semibold">${dif2.horas};${dif2.minutos};${dif2.segundos}.</span>
                                                </span>`;
                            }




                            var Contenido = `
                                ${row.UltimoConcepto}
                                <div class="fw-bold text-primary">
                                    ${row.NumArea} - ${row.NomArea}${row.CodigoUsuario ? `(${row.CodigoUsuario})` : ''} - ${row.Usuario}
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
                                ${row.UltimoConcepto}
                                <div class="fw-bold text-primary">
                                    ${row.NumArea} - ${row.NomArea}${row.CodigoUsuario ? `(${row.CodigoUsuario})` : ''} - ${row.Usuario}
                                    <div>${textoEstado}</div>
                                </div>
                            `;



                        }else{
                            var Contenido = `${row.UltimoConcepto}<div class="fw-bold text-primary">${row.NumArea} - ${row.NomArea}${row.CodigoUsuario ? `(${row.CodigoUsuario})` : ''} - ${row.Usuario}
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
                            if (ultimoEstado == "REMITIDO" || ultimoEstado == "VALIDADO" || ultimoEstado == "INFORMADO") {
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
                            }else if (ultimoEstado == "TERMINADO") {
                                var Estado =
                                    '<div class="btn btn-success blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">TERMINADO</div>'
                            }else if (ultimoEstado == "RECIBIDO") {
                                var Estado =
                                    '<div class="btn btn-success blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">RECIBIDO POR FUNCIONARIO</div>'
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
                            }else if (ultimoEstado === "ENVIADO" || ultimoEstado === "ACLARAR" || ultimoEstado === "ENCARGARSE" || ultimoEstado === "PROCEDER" || ultimoEstado === "SOLUCIONAR" || ultimoEstado === "QUE PASO") {
                                var Estado =
                                    '<div class="btn btn-secondary blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">'+ ultimoEstado +'</div>'
                            } else if(ultimoEstado == "VENCIDO") {
                                var Estado =
                                    '<div class="btn btn-danger shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;"><span class="d-none">1</span>VENCIDO</div>'
                            } else {
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
                            
                            row.historialActual = row.historialEstadosUnicos;

                            document.addEventListener("click", function(e) {

                                // Verificar si se hizo click en un botón de historial o historial original
                                const btnHistorial = e.target.closest(`[id^="btnToggleHistorial-"]`);
                                const btnOriginal = e.target.closest(`[id^="btnToggleOriginal-"]`);

                                if (btnHistorial || btnOriginal) {

                                    const btn = btnHistorial || btnOriginal;
                                    const id = btn.dataset.id;
                                    const row = table.row(btn.closest("tr")).data();
                                    const contenedor = document.querySelector(`#historial-dinamico-${id}`);

                                    // Elegir el array según el botón
                                    const historialArray = btnHistorial ? row.historial : row.historialActual;

                                    // Renderizar
                                    contenedor.innerHTML = historialArray
                                        .slice(0)
                                        .map(item => renderHistorial(item, id))
                                        .join("");

                                    // Cambiar visibilidad de botones
                                    if (btnHistorial) {
                                        document.querySelector(`#btnToggleHistorial-${id}`).classList.add("d-none");
                                        document.querySelector(`#btnToggleOriginal-${id}`).classList.remove("d-none");
                                    } else {
                                        document.querySelector(`#btnToggleOriginal-${id}`).classList.add("d-none");
                                        document.querySelector(`#btnToggleHistorial-${id}`).classList.remove("d-none");
                                    }
                                }

                            });
                            
                            function renderHistorial(item, id) {


                                        document.querySelectorAll('.modal').forEach(modal => {

                                            const id = modal.getAttribute('data-id');
                                            if (!id) return;

                                            // Obtener el select dentro del modal
                                            const enviarSelect = modal.querySelector(`#enviarselect_${id} select`);
                                            if (!enviarSelect) return;

                                            // Buscar el contenedor de radios por ID dinámico
                                            const estadoContainer = modal.querySelector(`#radios_${id}`);
                                            if (!estadoContainer) return;

                                            // Buscar solo los radios dentro de ese contenedor
                                            const radios = estadoContainer.querySelectorAll('input[type="radio"]');

                                            radios.forEach(radio => {
                                                radio.addEventListener('change', () => {
                                                    if (radio.checked && radio.value === 'ENVIAR A') {
                                                        enviarSelect.disabled = false; // habilitar select
                                                    } else if (radio.checked) {
                                                        enviarSelect.disabled = true;  // deshabilitar select
                                                    }
                                                });
                                            });

                                        });








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
                            
                                    function escapeHTML(text) {
                                        if (!text) return '';
                                        return text
                                            .replace(/</g, " < ")
                                            .replace(/>/g, " > ")
                                    }
                                   
                                        
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
                                                            <span class="fs-5">${item.NumArea} - ${item.NomArea}<b>${item.CodigoUsuario ? `(${item.CodigoUsuario})` : ''}</b> - <b>${item.Nombre}</b><br>👉(Click para mostrar)👈</span>
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
                                                                        <select class="form-select form-select-lg" name="tautorizacionmodal" id="autorizacionesmodal${row.IDAutorizacion}" required>
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
                                                                                <div class="input-group mb-0 rounded-3 me-2">
                                                                                        <input class="form-control fs-5 border border-dark ms-1" style="border-radius: 7px 0 0 7px;" id="Cedulamodal${id}" name="Cedulamodal" value="${item.Cedula}" required onkeydown="disableEnterKey(event)">
                                                                                        <span class="input-group-text bg-success-subtle border-dark text-primary tooltip1" data-bs-toggle="tooltip" data-bs-placement="right" title="Cédula / NIT">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info">
                                                                                                <circle cx="12" cy="12" r="10" />
                                                                                                <path d="M12 16v-4" />
                                                                                                <path d="M12 8h.01" />
                                                                                            </svg>
                                                                                        </span>

                                                                                        <input class="form-control fs-5 border-end border-dark ms-1" style="border-radius: 7px 0 0 7px;" id="Cuentamodal${id}" name="Cuentamodal" value="${item.CuentaAsociado}" required onkeydown="disableEnterKey(event)">
                                                                                        <span class="input-group-text bg-success-subtle border-dark text-primary tooltip2" data-bs-toggle="tooltip" data-bs-placement="right" title="Cuenta">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info">
                                                                                                <circle cx="12" cy="12" r="10" />
                                                                                                <path d="M12 16v-4" />
                                                                                                <path d="M12 8h.01" />
                                                                                            </svg>
                                                                                        </span>

                                                                                        <input class="form-control fs-5 border-end border-dark ms-1" style="border-radius: 7px 0 0 7px;" id="Nombremodal${id}" name="Nombremodal" value="${item.NombrePersona}" required onkeydown="disableEnterKey(event)">
                                                                                        <span class="input-group-text bg-success-subtle border-dark text-primary tooltip3" data-bs-toggle="tooltip" data-bs-placement="right" title="Nombre / Nombre Empresa">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info">
                                                                                                <circle cx="12" cy="12" r="10" />
                                                                                                <path d="M12 16v-4" />
                                                                                                <path d="M12 8h.01" />
                                                                                            </svg>
                                                                                        </span>

                                                                                        <input class="form-control fs-5 border-end border-dark tooltip4 ms-1" style="border-radius: 7px 0 0 7px;" id="Convencionmodal${id}" name="Convencionmodal" value="${item.Convencion ?? ''}" placeholder="${item.Convencion ?? 'Disposición-N/A'}" required onkeydown="disableEnterKey(event)">
                                                                                        <span class="input-group-text bg-success-subtle border-dark text-primary tooltip4" data-bs-toggle="tooltip" data-bs-placement="right" title="Convenciones">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info">
                                                                                                <circle cx="12" cy="12" r="10" />
                                                                                                <path d="M12 16v-4" />
                                                                                                <path d="M12 8h.01" />
                                                                                            </svg>
                                                                                        </span>

                                                                                </div>
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
                                                                        id="Detalle" name="Detalle_${row.IDAutorizacion}" required>${escapeHTML(item.Detalle)}</textarea>
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
                                                                    <span class="mb-0">${escapeHTML(item.Detalle)}</span>
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
                                                                ${row.UltimoConceptoID == '17' ?
                                                                `<label class="label">
                                                                    <input value="INFORMADO" type="radio" name="Estado" required>
                                                                    <span>INFORMADO</span>
                                                                </label>`
                                                                :
                                                                `
                                                                <label class="label">
                                                                    <input value="VALIDADO" type="radio" name="Estado" required>
                                                                    <span>VALIDAR</span>
                                                                </label>
                                                                `
                                                                }
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
                                                                            <span class="fs-5"><b>{{ session('agenciau') === 'Gerencia General' ? 'Coordinación 9' : session('agenciau') }} - {{ session('name') }}</b></span>
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

                                                                    <div class="estado-container" id="radios_${id}">

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
                                                                            <input value="ENVIAR A" type="radio" name="Estado" id="estado_enviara_${id}" onclick="abrirModalEnviar(${id})" required>
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
                                                                </div>
                                                            </div>
                                                        </form>
                                                    
                                                    
                                                    `
                                                    : ((item.Estado == 'TRÁMITE' && item.Observaciones != 'NADA') && '{{ session('rol') }}' == 'Gerencia')? `
                                                    
                                                        <form enctype="multipart/form-data" id="formValidarGerenciaAutorizacion${row.IDAutorizacion}" data-id="${row.IDAutorizacion}">
                                                            @csrf
                                                            <div class="row g-0">
                                                                <div class="col-sm-12 col-md-12 col-lg-2 d-flex flex-column align-items-center align-items-lg-start justify-content-start border p-3 border-dark bg-dark-subtle">
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
                                                    
                                                    ` : ``)
                                        }





                                        </div>`;
                                                    }

                                                    // Si NO es "TRÁMITE", renderiza el bloque normal
                                                    return `
                                        <div class="row g-0 text-center">
                                            <div class="col-sm-12 col-md-12 col-lg-2 d-flex align-items-center justify-content-center ${
                                                item.Estado === 'VALIDADO' || item.Estado === 'INFORMADO' || item.Estado === 'INFORMADOCONFIRMADO' ? 'bg-success-subtle' :
                                                item.Estado === 'RECIBIDOCONFIRMADO' ? 'bg-success-subtle' :
                                                item.Estado === 'VALIDADOCONFIRMADO' ? 'bg-success-subtle' :
                                                item.Estado === 'REMITIDOCONFIRMADO' ? 'bg-warning-subtle' :
                                                item.Estado === 'ENTERADO' || item.Estado === 'RECIBIDO' ? 'bg-success-subtle' :
                                                item.Estado === 'APROBADO' ? 'bg-info-subtle' :
                                                item.Estado === 'CORREGIR' ? 'bg-primary-subtle' :
                                                item.Estado === 'ANULADO' ? 'bg-info-subtle' :
                                                item.Estado === 'REMITIDO' ? 'bg-info-subtle' :
                                                item.Estado == 'BLOQUEADO' || item.Estado == 'VENCIDO' ? 'bg-danger-subtle' :
                                                item.Estado == 'STAND BY' ? 'bg-dark-subtle' :
                                                'bg-secondary-subtle'
                                            } border p-2 border border-dark" title="${item.Estado}">
                                                <span class="h1 fw-bold mb-0">
                                                    ${item.Estado[0]}<br>
                                                    <span class="fs-5 fw-normal">
                                                        ${item.Estado == "VALIDADOCONFIRMADO" 
                                                            ? "VALIDADO" 
                                                            : item.Estado == "INFORMADOCONFIRMADO" 
                                                            ? "INFORMADO" 
                                                            : item.Estado == "REMITIDOCONFIRMADO" 
                                                            ? "REMITIDO" 
                                                            : item.Estado == "ENVIADO" 
                                                            ? "ENVIADO(DR)" 
                                                            : item.Estado == "RECIBIDOCONFIRMADO" 
                                                            ? "RECIBIDO" 
                                                            : item.Estado == "TERMINADO" ||  item.Estado == "ACLARAR" ||  item.Estado == "ENCARGARSE" ||  item.Estado == "PROCEDER" ||  item.Estado == "SOLUCIONAR" ||  item.Estado == "QUE PASO"
                                                            ? item.Estado + "(DR)" :
                                                            item.Estado}
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
                                        
                                        ${((
                                                '{{ session('rol') }}' === 'Gerencia' &&
                                                row.UltimoEstado !== 'APROBADO' &&
                                                row.UltimoEstado !== 'ANULADO' &&
                                                row.UltimoEstado !== 'TERMINADO' &&
                                                row.UltimoEstado !== 'ENTERADO' &&
                                                row.UltimoEstado !== 'VENCIDO' &&
                                                row.UltimoEstado !== 'ENVIADO' &&
                                                row.UltimoEstado !== 'RECIBIDO' &&
                                                row.UltimoEstado !== 'ACLARAR' &&
                                                row.UltimoEstado !== 'ENCARGARSE' &&
                                                row.UltimoEstado !== 'PROCEDER' &&
                                                row.UltimoEstado !== 'SOLUCIONAR' &&
                                                row.UltimoEstado !== 'QUE PASO' &&
                                                row.UltimoEstado !== 'CORREGIR' &&
                                                row.UltimoEstado !== 'BLOQUEADO' &&
                                                (
                                                    item.ID === row.historialEstadosUnicos.at(-1)?.ID ||
                                                    item.Estado === 'VALIDADO' ||
                                                    item.Estado === 'DESBLOQUEADO'
                                                )
                                            )

                                                    ? 
                                                    `
                                                        <form enctype="multipart/form-data" id="formValidarGerenciaAutorizacion${row.IDAutorizacion}" data-id="${row.IDAutorizacion}">
                                                            @csrf
                                                            <div class="row g-0">
                                                                <div class="col-sm-12 col-md-12 col-lg-2 d-flex flex-column align-items-center align-items-lg-start justify-content-start border p-3 border-dark bg-dark-subtle">
                                                                    <div class="estado-container" id="radios_${id}">
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
                                                                            <input value="ENVIAR A" type="radio" name="Estado" id="estado_enviara_${id}" onclick="abrirModalEnviar(${id})" required>
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
                                                                </div>
                                                            </div>
                                                        </form>
                                                    
                                                    
                                                    `
                                        :  (
                                            '{{ session("rol") }}' === 'Gerencia'
                                            && row.historialEstadosUnicos?.length

                                            && (
                                                row.UltimoEstado === 'BLOQUEADO'
                                                || row.UltimoEstado === 'STAND BY'
                                                || row.UltimoEstado === 'CORREGIR'
                                                || row.UltimoEstado === 'APROBADO'
                                            )
                                            && item.Estado !== 'CORREGIR'
                                            && item.Estado !== 'VALIDADOCONFIRMADO'
                                 
                                            && item.Estado !== 'DESBLOQUEADO'
                                            && item.Estado !== 'REMITIDOCONFIRMADO'
                                            && item.Estado !== 'STAND BY'
                                            && item.Estado !== 'VALIDADO'
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
                                                                            ${item.Observaciones == null ? '' : ``} 
                                                                            required
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    `
                                                    
                                                    : (
                                                            '{{ session("rol") }}' !== 'Gerencia' && row.UltimoEstado !== 'RECIBIDO'
                                                            && row.historialEstadosUnicos && row.historialEstadosUnicos.length
                                                            // && row.ultimoEnviadoa === '{{ session("name") }}'
                                                            && item.ID === row.historialEstadosUnicos[row.historialEstadosUnicos.length - 1].ID
                                                            && (row.UltimoEstado === 'ENVIADO' || row.UltimoEstado === 'ACLARAR' || row.UltimoEstado === 'ENCARGARSE' || row.UltimoEstado === 'PROCEDER' || row.UltimoEstado === 'SOLUCIONAR' || row.UltimoEstado === 'QUE PASO')
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
                                                    :  (
                                                            '{{ session("rol") }}' === 'Gerencia'
                                                            && row.historialEstadosUnicos && row.historialEstadosUnicos.length
                                                            && item.ID === row.historialEstadosUnicos[row.historialEstadosUnicos.length - 1].ID
                                                            && (row.UltimoEstado === 'RECIBIDO')
                                                        )
                                                    ?
                                                    
                                                    `
                                                        <form enctype="multipart/form-data" id="formValidarAutorizacion${row.IDAutorizacion}" data-id="${row.IDAutorizacion}">
                                                            @csrf
                                                            <div class="row g-0">
                                                                <div class="col-sm-12 col-md-12 col-lg-2 d-flex flex-column align-items-center align-items-lg-start justify-content-start border p-3 border-dark bg-dark-subtle">
                                                                    <div class="estado-container" id="radios_${id}">
                                                                        <label class="label">
                                                                            <label style="cursor: pointer;">
                                                                                <input value="TERMINADO" type="radio" name="Estado" id="estado_terminado" required>
                                                                                <span>TERMINADO</span>
                                                                            </label>
                                                                            
                                                                            <label style="cursor: pointer;">
                                                                                <input value="ACLARAR" type="radio" name="Estado" id="estado_aclarar" required>
                                                                                <span>ACLARAR</span>
                                                                            </label>

                                                                            <label style="cursor: pointer;">
                                                                                <input value="ENCARGARSE" type="radio" name="Estado" id="estado_encargarse" required>
                                                                                <span>ENCARGARSE</span>
                                                                            </label>

                                                                            <label style="cursor: pointer;">
                                                                                <input value="PROCEDER" type="radio" name="Estado" id="estado_proceder" required>
                                                                                <span>PROCEDER</span>
                                                                            </label>

                                                                            <label style="cursor: pointer;">
                                                                                <input value="SOLUCIONAR" type="radio" name="Estado" id="estado_solucionar" required>
                                                                                <span>SOLUCIONAR</span>
                                                                            </label>

                                                                            <label style="cursor: pointer;">
                                                                                <input value="QUE PASO" type="radio" name="Estado" id="estado_quepaso" required>
                                                                                <span>QUE PASÓ⁉️</span>
                                                                            </label>

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
                                                                </div>
                                                            </div>
                                                        </form>
                                                    
                                                    
                                                    `
                                                    
                                                    
                                                    : ``)
                                        
                                        
                                        }`;
                            
                            }
                            



                            

                            var modalEditar = `
                            <a type="button" class="btn btn-outline-secondary" id="modalLink_${id}" data-bs-toggle="modal" data-bs-target="#exampleModal_${id}"
                                        data-id="${id}">
                                        <i class="fa-solid fa-eye fs-5"></i>
                            </a>


                            {{-- MODAL --}}
                            <div class="modal fade bd-example-modal-lg" id="exampleModal_${id}" tabindex="-1" role="dialog" aria-hidden="true" data-id="${id}">
                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header row w-100 m-0 align-items-center p-3" style="border-bottom: 2px solid #00bfff; border-radius: 8px 8px 0 0;">

                                            <!-- Detalle de Autorización -->
                                            <div class="col-12 col-md-6 mb-2 mb-md-0">
                                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                                    <!-- Número de Autorización destacado -->
                                                    <div class="d-none d-md-block"
                                                        style="font-size: ${row.UltimoConceptoID == '17' ? '26px' : '31px'};
                                                                font-weight: 800;
                                                                color: #D5DBDB;
                                                                text-shadow: 2px 2px 6px rgba(0,0,0,0.4);">
                                                        
                                                        ${row.UltimoConceptoID == '17'
                                                            ? 'REPORTE DE INFORMACIÓN ADMITIVA'
                                                            : 'SOLICITUD DE AUTORIZACIONES'}
                                                        <br>
                                                        <span style="font-size: 40px; color: #00bfff;">
                                                            No. ${row.IDAutorizacion} ${row.UltimaSECautorizacion ? ' | SEC: ' + row.UltimaSECautorizacion : ''}
                                                        </span>
                                                    </div>

                                                    <!-- MÓVIL -->
                                                    <div class="d-block d-md-none"
                                                        style="font-size: 18px;
                                                                font-weight: 800;
                                                                color: #D5DBDB;
                                                                text-shadow: 1px 1px 4px rgba(0,0,0,0.4);">
                                                        
                                                        ${row.UltimoConceptoID == '17' ? 'REPORTE' : 'AUTORIZACIONES'}
                                                        <br>
                                                        <span style="font-size: 22px; color: #00bfff;">
                                                            No. ${row.IDAutorizacion}
                                                        </span>
                                                    </div>

                                                    <!-- Información secundaria -->
                                                    <!-- Desktop / Tablet -->
                                                    <div class="d-none d-md-block" style="font-size: 20px; font-weight: 600; color: #ffd700;">
                                                        Fecha: ${row.FechaStringEstado} | Área: ${row.NomArea}${row.CodigoUsuario ? `(${row.CodigoUsuario})` : ''} | Solicitado Por: ${row.Usuario}
                                                    </div>

                                                    <!-- Móvil -->
                                                    <div class="d-block d-md-none" style="font-size: 14px; font-weight: 600; color: #ffd700;">
                                                        Solicitado por: ${row.Usuario}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Botones de acción -->
                                            <div class="col-12 col-md-5 d-flex justify-content-md-end justify-content-center align-items-center gap-2 flex-wrap">


                                                <!-- HISTORIAL -->
                                                <button 
                                                    type="button"
                                                    id="btnToggleHistorial-${id}"
                                                    class="btn btn-warning fw-bold shadow-sm
                                                        btn-sm d-inline-flex align-items-center"
                                                    data-id="${id}">
                                                    <i class="fa-solid fa-clock-rotate-left me-1"></i>
                                                    <span class="d-none d-md-inline">Historial</span>
                                                </button>

                                                <!-- ORIGINAL -->
                                                <button 
                                                    type="button"
                                                    id="btnToggleOriginal-${id}"
                                                    class="btn btn-warning fw-bold d-none shadow-sm
                                                        btn-sm d-inline-flex align-items-center"
                                                    data-id="${id}">
                                                    <i class="fa-solid fa-rotate-left me-1"></i>
                                                    <span class="d-none d-md-inline">Original</span>
                                                </button>

                                                <!-- DESPLAZAR -->
                                                <button
                                                    type="button"
                                                    class="btn btn-dark fw-bold shadow-sm
                                                        btn-sm d-inline-flex align-items-center btn-scroll btn-premium-action2"
                                                    data-scroll-to="anchor-scroll-${id}">
                                                    <i class="fa-solid fa-arrow-down"></i>
                                                    <span class="d-none d-md-inline ms-1">Desplazar al Final</span>
                                                </button>


                                                <button 
                                                    type="button" 
                                                    class="btn-close fs-5" 
                                                    data-bs-dismiss="modal" 
                                                    aria-label="Close"
                                                    style="outline: none; border: none; font-size: 20px;">
                                                </button>
                                 

                                            </div>


                                        </div>



                                        <div class="modal-body p-1">

                                                <div class="row g-0 text-center">
                                                    <div class="col-sm-none col-md-none col-lg-2 bg-primary-subtle">

                                                    </div>
                                                    <div class="col-md-12 col-lg-10">
                                                              <div class="row g-0 text-center">
                                                                    <span id="anchor-scrollup-${id}"></span>
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
                                                                                row.UltimoEstado == "TERMINADO" ?
                                                                                `<button class="btn btn-success  shadow blink" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">TERMINADO</button>` :
                                                                                row.UltimoEstado == "ENTERADO" ?
                                                                                `<button class="btn btn-success  shadow blink" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">E - ENTERADO</button>` :
                                                                                row.UltimoEstado == "CORREGIR" ?
                                                                                `<button class="btn btn-primary shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">C - CORREGIR</button>` :
                                                                                row.UltimoEstado == "ANULADO" ?
                                                                                '<button class="btn btn-info shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">AN - ANULADO</button>' :
                                                                                row.UltimoEstado == "STAND BY" ?
                                                                                '<button class="btn btn-dark shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">STAND BY</button>' :
                                                                                row.UltimoEstado == "REMITIDO" || row.UltimoEstado == "VALIDADO" || row.UltimoEstado == "INFORMADO" ?
                                                                                '<button class="btn btn-info shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">REMITIDO A GERENCIA</button>' :
                                                                                row.UltimoEstado == "DESBLOQUEADO" ?
                                                                                '<button class="btn btn-secondary shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">DESBLOQUEADO</button>' :
                                                                                row.UltimoEstado == "ENVIADO" || row.UltimoEstado == "ACLARAR" || row.UltimoEstado == "ENCARGARSE" || row.UltimoEstado == "PROCEDER" || row.UltimoEstado == "SOLUCIONAR" || row.UltimoEstado == "QUE PASO" ?
                                                                                '<button class="btn btn-secondary shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">'+ row.UltimoEstado +'</button>' :
                                                                                row.UltimoEstado == "RECIBIDO" ?
                                                                                `<button class="btn btn-success  shadow blink" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">RECIBIDO POR FUNCIONARIO</button>` :
                                                                                row.UltimoEstado == "VENCIDO" ?
                                                                                `<button class="btn btn-danger shadow blink" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">VENCIDO</button>` :
                                                                                '<button class="btn btn-danger shadow" style="padding: 0.4rem 1.7rem; border-radius: 10%; font-weight: 600; font-size: 14px;">BLOQUEADO</button>'
                                                                            }
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                <div class="row g-0 text-center" id="historial-dinamico-${id}">

                                                ${
                                                    row.historialActual
                                                        .slice(0) // saltar el primer estado
                                                        .map(item => {
                                                            return renderHistorial(item, id);
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
                                                                                    id="Detalle" name="Detalle_${row.IDAutorizacion}" required>${escapeHTML(item.Detalle)}</textarea>
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
                                                                                <span class="mb-0">${escapeHTML(item.Detalle)}</span>
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

                                                                            <div class="estado-container" id="radios_${id}">

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
                                                                                    <input value="ENVIAR A" type="radio" name="Estado" id="estado_enviara_${id}"  onclick="abrirModalEnviar(${id})" required>
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
                                                        item.Estado === 'VALIDADO' || item.Estado === 'INFORMADO' || item.Estado === 'INFORMADOCONFIRMADO' ? 'bg-success-subtle' :
                                                        item.Estado === 'RECIBIDOCONFIRMADO' ? 'bg-success-subtle' :
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
                                                                    : item.Estado == "INFORMADOCONFIRMADO"
                                                                    ? "INFORMADO"
                                                                    : item.Estado == "REMITIDOCONFIRMADO" 
                                                                    ? "REMITIDO" 
                                                                    : item.Estado == "ENVIADO" 
                                                                    ? "ENVIADO(DR)" 
                                                                    : item.Estado == "RECIBIDOCONFIRMADO" 
                                                                    ? "RECIBIDO" 
                                                                    : item.Estado == "TERMINADO" ||  item.Estado == "ACLARAR" ||  item.Estado == "ENCARGARSE" ||  item.Estado == "PROCEDER" ||  item.Estado == "SOLUCIONAR" ||  item.Estado == "QUE PASO"
                                                                    ? item.Estado + "(DR)" :
                                                                    item.Estado}
                                                            </span>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-12 col-md-12 col-lg-10">
                                                        <div class="row g-0">
                                                            <div class="text-start col-md-9 d-flex align-items-center border p-2">
                                                                <span class="fs-5 mb-0">${item.NomArea}(<b>${item.CodigoUsuario == null ? 'N/A':item.CodigoUsuario}</b>) - <b>${item.Nombre ?? 'N/A'}</b></span>
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
                                                
                                                ${((('{{ session('rol') }}' === 'Gerencia') && item.ID === row.historialEstadosUnicos[row.historialEstadosUnicos.length - 1].ID && (row.UltimoEstado === 'VALIDADO' || row.UltimoEstado === 'DESBLOQUEADO'))
                                                            ? 
                                                            `
                                                                <form enctype="multipart/form-data" id="formValidarGerenciaAutorizacion${row.IDAutorizacion}" data-id="${row.IDAutorizacion}">
                                                                    @csrf
                                                                    <div class="row g-0">
                                                                        <div class="col-sm-12 col-md-12 col-lg-2 d-flex flex-column align-items-center align-items-lg-start justify-content-start border p-3 border-dark bg-dark-subtle">
                                                                            <div class="estado-container" id="radios_${id}">
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
                                                                                    <input value="ENVIAR A" type="radio" name="Estado" id="estado_enviara_${id}" onclick="abrirModalEnviar(${id})" required>
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
                                                                            

                                                                            
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            
                                                            
                                                            `
                                                :  (
                                                        '{{ session("rol") }}' === 'Gerencia'
                                                        && row.historialEstadosUnicos && row.historialEstadosUnicos.length
                                                        && item.ID === row.historialEstadosUnicos[row.historialEstadosUnicos.length - 1].ID
                                                        && (row.UltimoEstado === 'BLOQUEADO' || row.UltimoEstado === 'STAND BY' || row.UltimoEstado === 'CORREGIR'  || row.UltimoEstado === 'APROBADO')
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
                                                                    && row.historialEstadosUnicos && row.historialEstadosUnicos.length
                                                                    && row.ultimoEnviadoa === '{{ session("name") }}'
                                                                    && item.ID === row.historialEstadosUnicos[row.historialEstadosUnicos.length - 1].ID
                                                                    && (row.UltimoEstado === 'ENVIADO' || row.UltimoEstado === 'TERMINADO' || row.UltimoEstado === 'ACLARAR' || row.UltimoEstado === 'ENCARGARSE' || row.UltimoEstado === 'PROCEDER' || row.UltimoEstado === 'SOLUCIONAR' || row.UltimoEstado === 'QUE PASO')
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
                                                            :  (
                                                                    '{{ session("rol") }}' === 'Gerencia'
                                                                    && row.historialEstadosUnicos && row.historialEstadosUnicos.length
                                                                    && item.ID === row.historialEstadosUnicos[row.historialEstadosUnicos.length - 1].ID
                                                                    && (row.UltimoEstado === 'RECIBIDO')
                                                                )
                                                            ?
                                                            
                                                            `
                                                                <form enctype="multipart/form-data" id="formValidarAutorizacion${row.IDAutorizacion}" data-id="${row.IDAutorizacion}">
                                                                    @csrf
                                                                    <div class="row g-0">
                                                                        <div class="col-sm-12 col-md-12 col-lg-2 d-flex flex-column align-items-center align-items-lg-start justify-content-start border p-3 border-dark bg-dark-subtle">
                                                                            <div class="estado-container" id="radios_${id}">
                                                                                <label class="label">
                                                                                    <label style="cursor: pointer;">
                                                                                        <input value="TERMINADO" type="radio" name="Estado" id="estado_terminado" required>
                                                                                        <span>TERMINADO</span>
                                                                                    </label>
                                                                                    
                                                                                    <label style="cursor: pointer;">
                                                                                        <input value="ACLARAR" type="radio" name="Estado" id="estado_aclarar" required>
                                                                                        <span>ACLARAR</span>
                                                                                    </label>

                                                                                    <label style="cursor: pointer;">
                                                                                        <input value="ENCARGARSE" type="radio" name="Estado" id="estado_encargarse" required>
                                                                                        <span>ENCARGARSE</span>
                                                                                    </label>

                                                                                    <label style="cursor: pointer;">
                                                                                        <input value="PROCEDER" type="radio" name="Estado" id="estado_proceder" required>
                                                                                        <span>PROCEDER</span>
                                                                                    </label>

                                                                                    <label style="cursor: pointer;">
                                                                                        <input value="SOLUCIONAR" type="radio" name="Estado" id="estado_solucionar" required>
                                                                                        <span>SOLUCIONAR</span>
                                                                                    </label>

                                                                                    <label style="cursor: pointer;">
                                                                                        <input value="QUE PASO" type="radio" name="Estado" id="estado_quepaso" required>
                                                                                        <span>QUE PASÓ⁉️</span>
                                                                                    </label>

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
                                            ${//BOTONES
                                                row.UltimoEstado == 'CORREGIR'  && '{{ session('rol') }}' !== 'Coordinacion' && '{{ session('rol') }}' !== 'Gerencia'
                                                    ? `
                                                    <div class="text-center p-3">

                                                        <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">

                                                            <!-- GUARDAR -->
                                                            <button id="boton${row.IDAutorizacion}" 
                                                                name="btnregistrar" 
                                                                type="button"
                                                                class="btn btn-premium-action fw-bold btn-sm
                                                                    d-inline-flex align-items-center"
                                                                onclick="formEditarAutorizacion(${row.IDAutorizacion}, event)">
                                                                
                                                                <i class="fa-solid fa-floppy-disk"></i>
                                                                <span class="d-none d-md-inline ms-2">Guardar Cambios</span>
                                                                <span class="d-inline d-md-none ms-1">Guardar</span>
                                                            </button>

                                                            <!-- DESPLAZAR ARRIBA -->
                                                            <button
                                                                type="button"
                                                                class="btn btn-dark fw-bold btn-premium-action2 btn-sm
                                                                    d-inline-flex align-items-center btn-scroll"
                                                                data-scroll-to="anchor-scrollup-${id}">
                                                                
                                                                <i class="fa-solid fa-arrow-up"></i>
                                                                <span class="d-none d-md-inline ms-2">Desplazar al Inicio</span>
                                                            </button>

                                                        </div>

                                                        <span id="anchor-scroll-${id}"></span>
                                                    </div>

                                                    `
                                                    :   (((row.UltimoEstado === 'TRÁMITE' && row.NumArea != 'Jefatura') || row.UltimoEstado === 'APROBADO' || row.UltimoEstado === 'REMITIDO' || row.UltimoEstado === 'VALIDADO' || row.UltimoEstado == 'CORREGIR' || row.UltimoEstado == 'STAND BY' || row.UltimoEstado == 'BLOQUEADO' || row.UltimoEstado == 'DESBLOQUEADO' || row.UltimoEstado == 'INFORMADO') && '{{ session('rol') }}' === 'Gerencia')

                                                        ? `
                                                        <div class="text-center p-3">

                                                            <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">

                                                                <!-- GUARDAR -->
                                                                <button id="boton${row.IDAutorizacion}" 
                                                                    name="btnregistrar" 
                                                                    type="button"
                                                                    class="btn btn-premium-action fw-bold
                                                                        btn-sm d-inline-flex align-items-center"
                                                                    onclick="formValidarGerenciaAutorizacion(${row.IDAutorizacion}, event)">
                                                                    
                                                                    <i class="fa-solid fa-floppy-disk"></i>
                                                                    <span class="d-none d-md-inline ms-2">Guardar Cambios</span>
                                                                    <span class="d-inline d-md-none ms-1">Guardar</span>
                                                                </button>

                                                                <!-- DESPLAZAR ARRIBA -->
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-dark fw-bold btn-premium-action2 btn-sm
                                                                        d-inline-flex align-items-center btn-scroll"
                                                                    data-scroll-to="anchor-scrollup-${id}">
                                                                    
                                                                    <i class="fa-solid fa-arrow-up"></i>
                                                                    <span class="d-none d-md-inline ms-2">Desplazar al Inicio</span>
                                                                </button>

                                                            </div>

                                                            <span id="anchor-scroll-${id}"></span>
                                                        </div>

                                                        `
                                                    : 
                                                    (
                                                        row.UltimoEstado === 'TRÁMITE' &&
                                                        '{{ session('rol') }}' === 'Coordinacion'
                                                    )
                                                    ||
                                                    (
                                                        '{{ session('rol') }}' === 'Gerencia' && row.UltimoEstado == "RECIBIDO" ||
                                                        row.UltimoEstado !== "CORREGIR" &&
                                                        row.UltimoEstado !== "APROBADO" &&
                                                        row.UltimoEstado !== "VALIDADO" &&
                                                        row.UltimoEstado !== "DONE" &&
                                                        row.UltimoEstado !== "ANULADO" &&
                                                        row.UltimoEstado !== "ENVIADO" &&
                                                        row.UltimoEstado !== "TERMINADO" && row.NumArea === 'Jefatura' && '{{ session('rol') }}' !== 'Jefatura' && '{{ session('rol') }}' !== 'Coordinacion'
                                                        
                                                    )
                                                    ||
                                                    (
                                                        // row.ultimoEnviadoa === '{{ session("name") }}' &&
                                                        ('{{ session("rol") }}' !== 'Gerencia' &&
                                                        row.UltimoEstado !== 'RECIBIDO' && row.UltimoEstado !== 'TERMINADO' && row.UltimoEstado !== 'INFORMADO') &&
                                                        (('{{ session("rol") }}' !== 'Gerencia' && '{{ session("rol") }}' !== 'Consultante') && row.UltimoEstado !== 'CORREGIR' && row.UltimoEstado !== 'REMITIDO' && row.UltimoEstado !== 'VALIDADO')
                                                    )
                                                        ? `

                                                        <div class="text-center p-3">

                                                            <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">

                                                                <!-- VALIDAR -->
                                                                <button id="boton${row.IDAutorizacion}" 
                                                                    name="btnregistrar" 
                                                                    type="button"
                                                                    class="btn btn-premium-action fw-bold btn-sm
                                                                        d-inline-flex align-items-center"
                                                                    onclick="formValidarAutorizacion(${row.IDAutorizacion}, event)">
                                                                    
                                                                    <i class="fa-solid fa-floppy-disk"></i>
                                                                    <span class="d-none d-md-inline ms-2">Validar</span>
                                                                    <span class="d-inline d-md-none ms-1">Validar</span>
                                                                </button>

                                                                <!-- DESPLAZAR ARRIBA -->
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-dark fw-bold btn-premium-action2 btn-sm
                                                                        d-inline-flex align-items-center btn-scroll"
                                                                    data-scroll-to="anchor-scrollup-${id}">
                                                                    
                                                                    <i class="fa-solid fa-arrow-up"></i>
                                                                    <span class="d-none d-md-inline ms-2">Desplazar al Inicio</span>
                                                                </button>

                                                            </div>

                                                            <span id="anchor-scroll-${id}"></span>
                                                        </div>

                                                                 `
                                                    : ((row.EstadoRemitidoBoton === 'REMITIDOCORREGIR' || row.UltimoEstado == 'CORREGIR') && row.NomArea.includes('Coordinacion') && '{{ session('rol') }}' === 'Coordinacion')
                                                        ? `
                                                            <div class="text-center p-3">

                                                                <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">

                                                                    <!-- GUARDAR -->
                                                                    <button id="boton${row.IDAutorizacion}" 
                                                                        name="btnregistrar" 
                                                                        type="button"
                                                                        class="btn btn-premium-action fw-bold btn-sm
                                                                            d-inline-flex align-items-center"
                                                                        onclick="formEditarAutorizacion(${row.IDAutorizacion}, event)">
                                                                        
                                                                        <i class="fa-solid fa-floppy-disk"></i>
                                                                        <span class="d-none d-md-inline ms-2">Guardar Cambios</span>
                                                                        <span class="d-inline d-md-none ms-1">Guardar</span>
                                                                    </button>

                                                                    <!-- DESPLAZAR ARRIBA -->
                                                                    <button
                                                                        type="button"
                                                                        class="btn btn-dark fw-bold btn-premium-action2 btn-sm
                                                                            d-inline-flex align-items-center btn-scroll"
                                                                        data-scroll-to="anchor-scrollup-${id}">
                                                                        
                                                                        <i class="fa-solid fa-arrow-up"></i>
                                                                        <span class="d-none d-md-inline ms-2">Desplazar al Inicio</span>
                                                                    </button>

                                                                </div>

                                                                <span id="anchor-scroll-${id}"></span>
                                                            </div>

                                                        `:` 
                                                            <div class="text-center p-3">

                                                                <button
                                                                    type="button"
                                                                    class="btn btn-dark fw-bold btn-premium-action2 btn-sm
                                                                        d-inline-flex align-items-center btn-scroll"
                                                                    data-scroll-to="anchor-scrollup-${id}">
                                                                    
                                                                    <i class="fa-solid fa-arrow-up"></i>
                                                                    <span class="d-none d-md-inline ms-2">Desplazar al Inicio</span>
                                                                </button>

                                                                <span id="anchor-scroll-${id}"></span>
                                                            </div>
                     
                                                        `
                                                        
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
                    [4, 10, 25, 50, 100, -1],
                    [4, 10, 25, 50, 100, "Todos"]
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
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3 ms-auto">

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
                        <button id="btnTerminados" class="btn btn-success shadow-sm fw-bold btn-filter" title="TERMINADOS" style="transition: transform 0.2s;">TERMINADOS</button>
                        <button id="btnReportes" class="btn btn-primary shadow-sm fw-bold btn-filter" title="REPORTES" style="transition: transform 0.2s;">REPORTES</button>
                        <button id="btnVencidos" class="btn btn-danger shadow-sm fw-bold btn-filter" title="VENCIDOS" style="transition: transform 0.2s;">VENCIDOS</button>
                        ` :
                        `
                        <!-- BOTONES USUARIOS -->
                        <button id="btnA" class="btn btn-success shadow-sm fw-bold btn-filter" title="APROBADOS" style="transition: transform 0.2s;">APROBADOS</button>
                        <button id="btnAnulado" class="btn btn-info shadow-sm fw-bold btn-filter" title="ANULADOS" style="transition: transform 0.2s;">ANULADOS</button>
                        <button id="btnStandBy" class="btn btn-dark shadow-sm fw-bold btn-filter" title="STAND BY" style="transition: transform 0.2s;">STAND BY</button>
                        <button id="btnTerminados" class="btn btn-success shadow-sm fw-bold btn-filter" title="TERMINADOS" style="transition: transform 0.2s;">TERMINADOS</button>
                        <button id="btnReportes" class="btn btn-primary shadow-sm fw-bold btn-filter" title="REPORTES" style="transition: transform 0.2s;">REPORTES</button>
                        <button id="btnVencidos" class="btn btn-danger shadow-sm fw-bold btn-filter" title="VENCIDOS" style="transition: transform 0.2s;">VENCIDOS</button>
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

                $('#btnTerminados').on('click', function() {
                    lastAjaxUrl = '{{ route("data.terminado") }}';
                    table.ajax.url(lastAjaxUrl).load();
                    setActiveButton('#btnTerminados');
                });

                $('#btnReportes').on('click', function() {
                    lastAjaxUrl = '{{ route("data.reporte") }}';
                    table.ajax.url(lastAjaxUrl).load();
                    setActiveButton('#btnReportes');
                });

                $('#btnVencidos').on('click', function() {
                    lastAjaxUrl = '{{ route("data.vencido") }}';
                    table.ajax.url(lastAjaxUrl).load();
                    setActiveButton('#btnVencidos');
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

            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.btn-scroll');
                if (!btn) return;

                const targetId = btn.dataset.scrollTo;
                const target = document.getElementById(targetId);
                if (!target) {
                    console.warn('No existe el anchor:', targetId);
                    return;
                }

                // detectar contenedor scrollable más cercano
                let scroller = target;
                while (scroller && scroller !== document.body) {
                    const style = window.getComputedStyle(scroller);
                    const overflowY = style.overflowY;
                    if (overflowY === 'auto' || overflowY === 'scroll') break;
                    scroller = scroller.parentElement;
                }

                if (!scroller || scroller === document.body) {
                    // desplaza la ventana
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    // desplaza el contenedor que hace scroll
                    const top = target.getBoundingClientRect().top - scroller.getBoundingClientRect().top + scroller.scrollTop;
                    scroller.scrollTo({ top, behavior: 'smooth' });
                }
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
                                    const currentPage = table.page();

                                    table.ajax.reload(function () {
                                        const pageInfo = table.page.info();

                                        if (currentPage >= pageInfo.pages && pageInfo.pages > 0) {
                                            table.page(pageInfo.pages - 1).draw(false);
                                        }
                                    }, false);


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
                            const currentPage = table.page();

                            table.ajax.reload(function () {
                                const pageInfo = table.page.info();

                                if (currentPage >= pageInfo.pages && pageInfo.pages > 0) {
                                    table.page(pageInfo.pages - 1).draw(false);
                                }
                            }, false);

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

                event.preventDefault();

                var form = $("#formValidarGerenciaAutorizacion" + id);

                if (form.data('submitted')) return;
                form.data('submitted', true);

                // ✅ DETECTAR EL ÚLTIMO RADIO SELECCIONADO SOLO EN ESTE FORM
                var estado = form.find('input[name="Estado"]:checked').val();
                var observaciones = form.find('input[name="Observaciones"]').val();
                var destinatario = form.find('input[name="Destinatario"]').val();

                console.log("Estado:", estado, "Observaciones:", observaciones, "Destinatario:", destinatario);

                if (!estado) {
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

                            const currentPage = table.page();
                            table.ajax.reload(function () {
                                const pageInfo = table.page.info();

                                if (currentPage >= pageInfo.pages && pageInfo.pages > 0) {
                                    table.page(pageInfo.pages - 1).draw(false);
                                }
                            }, false);

                            // 🔒 SWAL.FIRE INTACTO (IGUAL AL TUYO)
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
                        form.data('submitted', false);
                    }
                });
            }








            function disableEnterKey(event) {
                if (event.key === "Enter") {
                    event.preventDefault(); // Prevenir la acción predeterminada de la tecla "Enter"
                }
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
                        <button type="submit" class="btn btn-gradient-primary btn-lg fw-bold w-50" id="solicitar">SOLICITAR</button>
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
                        <button type="submit" class="btn btn-gradient-primary btn-lg fw-bold w-50" id="solicitar">SOLICITAR</button>
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
                        <button type="submit" class="btn btn-gradient-primary btn-lg fw-bold w-50" id="solicitar">SOLICITAR</button>
                    </div>
                    `;
                }

                $("#cuerpo").html(contenido);
            });
            


            document.getElementById('pagare').addEventListener('submit', function (e) {
                e.preventDefault();

                const form = this;
                const btn = document.querySelector("#solicitar");
                const formData = new FormData(form);

                btn.disabled = true;
                btn.innerText = "Enviando...";

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    btn.disabled = false;
                    btn.innerText = "Solicitar";

                    if (data.dd) {
                        console.log('DD AJAX:', data.data);

                        Swal.fire({
                            icon: 'info',
                            title: 'DEBUG',
                            html: `<pre style="text-align:left">${JSON.stringify(data.data, null, 2)}</pre>`
                        });

                        return; // corta ejecución
                    } else if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            html: data.message
                        });

                        form.reset();
                        document.getElementById('cuerpo').innerHTML = '';
                        $('#personas').DataTable().ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: data.message
                        });
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    btn.innerText = "Solicitar";

                    Swal.fire({
                        icon: 'error',
                        title: 'Error inesperado',
                        text: data.message
                    });

                    console.error(error);
                });
            });

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