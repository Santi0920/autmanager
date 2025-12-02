

                                                            
                                                            
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
                                                                    : item.Estado == "REMITIDOCONFIRMADO" 
                                                                    ? "REMITIDO" 
                                                                    : item.Estado == "ENVIADO" 
                                                                    ? "ENVIADO(DR)" 
                                                                    : item.Estado == "RECIBIDOCONFIRMADO" 
                                                                    ? "RECIBIDO" 
                                                                    : item.Estado == "RESOLVER" ||  item.Estado == "ACLARAR" ||  item.Estado == "ENCARGARSE" ||  item.Estado == "PROCEDER" ||  item.Estado == "SOLUCIONAR" ||  item.Estado == "QUE PASO"
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
                                                                    && (row.UltimoEstado === 'ENVIADO' || row.UltimoEstado === 'RESOLVER' || row.UltimoEstado === 'ACLARAR' || row.UltimoEstado === 'ENCARGARSE' || row.UltimoEstado === 'PROCEDER' || row.UltimoEstado === 'SOLUCIONAR' || row.UltimoEstado === 'QUE PASO')
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
                                                                    && row.historial && row.historial.length
                                                                    && item.ID === row.historial[row.historial.length - 1].ID
                                                                    && (row.UltimoEstado === 'RECIBIDO')
                                                                )
                                                            ?
                                                            
                                                            `
                                                                <form enctype="multipart/form-data" id="formValidarAutorizacion${row.IDAutorizacion}" data-id="${row.IDAutorizacion}">
                                                                    @csrf
                                                                    <div class="row g-0">
                                                                        <div class="col-sm-12 col-md-12 col-lg-2 d-flex flex-column align-items-center align-items-lg-start justify-content-start border p-3 border-dark bg-dark-subtle">
                                                                            <div class="estado-container">
                                                                                <label class="label">
                                                                                    <label style="cursor: pointer;">
                                                                                        <input value="RESOLVER" type="radio" name="Estado" id="estado_resolver" required>
                                                                                        <span>RESOLVER</span>
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