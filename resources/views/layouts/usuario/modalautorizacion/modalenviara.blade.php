<div class="modal fade" id="modalEnviarA" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content shadow-lg rounded-4">

            <div class="modal-header bg-dark text-white">
                <h5 class="fw-bold" style="font-size: 28px;">
                    📤 ENVIAR AUTORIZACIÓN O REPORTE
                    (<span class="text-info" id="numeroAutorizacion"></span>)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- OBSERVACION --}}
                <div class="mb-3 d-flex align-items-start gap-2">
                    <label class="form-label fw-bold fs-5 mb-0" style="min-width: 230px;">
                        📝 Observación (opcional):
                    </label>
                    <textarea
                        class="form-control fs-5"
                        rows="2"
                        placeholder="Escriba una observación para los funcionarios..."
                        id="observacion_enviar"></textarea>
                </div>

                <!-- 🔍 BUSCADOR -->
                <div class="mb-3 d-flex align-items-center gap-3">
                    <label class="form-label fw-bold fs-5 mb-0">FILTRO:</label>
                    <input type="text"
                        class="form-control form-control-lg"
                        placeholder="🔎 Buscar por nombre, código o agencia..."
                        onkeyup="filtrarUsuarios(this.value, 12860)">
                </div>

                <!-- LISTA -->
                <div class="border rounded-3 p-2"
                     style="max-height: 420px; overflow-y: auto;">

                    <div class="row g-2" id="contenedorUsuarios">
                        @foreach ($usuariosEnviara as $usuario)
                            <div class="col-md-6 usuario-item"
                                 data-text="{{ strtolower($usuario->codigo.' '.$usuario->name.' '.$usuario->agenciau) }}">

                                <label class="w-100 border rounded-3 p-3 d-flex gap-3 shadow-sm cursor-pointer">
                                    <input class="form-check-input destinatarios"
                                        type="checkbox"
                                        value="{{ $usuario->id }}">

                                    <div>
                                        <div class="fw-bold">
                                            {{ $usuario->codigo }} - {{ $usuario->name }}
                                        </div>
                                        <small class="text-muted">{{ $usuario->agenciau }}</small>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-center text-muted py-5 d-none" id="sinResultados">
                        <i class="bi bi-search fs-1 mb-2 d-block"></i>
                        <div class="fw-bold fs-5">No se encontraron resultados</div>
                        <small>Intenta con otro nombre, código o agencia</small>
                    </div>
                </div>

            </div>

            <div class="modal-footer justify-content-center gap-3">
                <button
                    type="button"
                    class="btn btn-dark fw-bold btn-premium-action2 btn-sm
                        d-inline-flex align-items-center btn-scroll" data-bs-dismiss="modal">
                    <span class="d-none d-md-inline ms-2">Cancelar</span>
                    <span class="d-inline d-md-none ms-1">Cancelar</span>
                </button>

                <button class="btn btn-premium-action fw-bold"
                        onclick="enviarAjax()">
                    <i class="fa-solid fa-paper-plane"></i>
                    Enviar seleccionados
                </button>
            </div>

        </div>
    </div>
</div>


<script>
let autorizacionActual = null;

    function abrirModalEnviar(id) {

        let autorizacionActual = null;
        let formActivo = null;

        const modalEl = document.getElementById('modalEnviarA');

        // 🔥 CLAVE ABSOLUTA: mover el modal al BODY
        if (!modalEl.dataset.moved) {
            document.body.appendChild(modalEl);
            modalEl.dataset.moved = "true";
        }

        // Actualizar número
        document.getElementById('numeroAutorizacion').innerText = `No. ${id}`;

        // Reset estado
        document.getElementById('observacion_enviar').value = '';
        document.querySelectorAll('.destinatarios').forEach(cb => cb.checked = false);

        const modal = new bootstrap.Modal(modalEl, {
            keyboard: false,
            focus: true
        });

        modal.show();
    }


    function abrirModalEnviarDesdeRadio(event, id) {

        event.preventDefault();
        event.stopPropagation();

        autorizacionActual = id;
        formActivo = document.getElementById(`formValidarGerenciaAutorizacion${id}`);

        // ❌ desmarcar todos los radios
        if (formActivo) {
            formActivo.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
        }

        // 🔒 deshabilitar observaciones
        const obs = document.getElementById(`Observaciones_${id}`);
        if (obs) {
            obs.value = '';
            obs.disabled = true;
            obs.removeAttribute('required');
        }

        abrirModalEnviar(id);
    }

    document.addEventListener('DOMContentLoaded', () => {

        const modalEl = document.getElementById('modalEnviarA');

        modalEl.addEventListener('hidden.bs.modal', () => {

            if (!formActivo) return;

            // ❌ solo limpiamos radios (seguridad visual)
            formActivo.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);

            // ❌ NO tocar Observaciones aquí

            autorizacionActual = null;
            formActivo = null;
        });
        

    });

    function manejarEstadoSeleccionado(id, tipo) {

        const inputObs = document.getElementById(`Observaciones_${id}`);
        if (!inputObs) return;

        if (tipo === 'ENVIAR_A') {
            inputObs.value = '';
            inputObs.disabled = true;
            inputObs.removeAttribute('required');
        } else {
            inputObs.disabled = false;
            inputObs.setAttribute('required', 'required');
            inputObs.focus();
        }
    }
    function filtrarUsuarios(texto) {
        texto = texto.toLowerCase();
        let visibles = 0;

        document.querySelectorAll('.usuario-item').forEach(item => {
            const mostrar = item.dataset.text.includes(texto);
            item.style.display = mostrar ? '' : 'none';
            if (mostrar) visibles++;
        });

        document.getElementById('sinResultados')
            .classList.toggle('d-none', visibles > 0);
    }
    //SE RESETEEA EL FILTRO AL SELECCIONAR UN CHECKBOX
    document.addEventListener('DOMContentLoaded', () => {

        const modalEl = document.getElementById('modalEnviarA');

        // Cuando se selecciona un checkbox
        modalEl.addEventListener('change', (e) => {

            if (!e.target.classList.contains('destinatarios')) return;

            const filtro = modalEl.querySelector('input[onkeyup^="filtrarUsuarios"]');
            if (!filtro) return;

            filtro.value = '';
            filtrarUsuarios('');
        });

    });
    //SE RESETEEA EL MODAL AL CERRARLO
    document.addEventListener('DOMContentLoaded', () => {

        const modalEl = document.getElementById('modalEnviarA');

        modalEl.addEventListener('hidden.bs.modal', () => {

            // 🔄 limpiar filtro
            const filtro = modalEl.querySelector('input[onkeyup^="filtrarUsuarios"]');
            if (filtro) filtro.value = '';
            filtrarUsuarios('');

            // ❌ desmarcar checks
            modalEl.querySelectorAll('.destinatarios')
                .forEach(cb => cb.checked = false);

            // ❌ ocultar sin resultados
            const sinResultados = document.getElementById('sinResultados');
            if (sinResultados) sinResultados.classList.add('d-none');
        });

    });


    function enviarAjax() {

        const seleccionados = Array.from(
            document.querySelectorAll('.destinatarios:checked')
        ).map(cb => cb.value);

        const observacion = document.getElementById('observacion_enviar').value;

        if (seleccionados.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: '¡ATENCIÓN!',
                html: "<span class='fs-4'>Debe seleccionar al menos un funcionario</span>",
                confirmButtonText: 'OK',
                customClass: { confirmButton: 'swal-confirm-dark' }
            });
            return;
        }

        Swal.fire({
            title: 'ENVIANDO...',
            text: 'Procesando la autorización',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch("{{ route('autorizaciones.enviar') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                autorizacion_id: autorizacionActual,
                destinatarios: seleccionados,
                Observaciones: observacion
            })
        })
        .then(res => res.json())
        .then(response => {

            if (response.success) {

                const modalEl = document.getElementById('modalEnviarA');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);

                if (modalInstance) {
                    modalInstance.hide();
                }

                Swal.fire({
                    icon: 'success',
                    title: 'ENVIADO CORRECTAMENTE',
                    html: response.message,
                    confirmButtonText: 'ACEPTAR',
                    customClass: { confirmButton: 'swal-confirm-dark' },
                    didClose: () => {
                        // 🔥 RESTAURACIÓN COMPLETA
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';

                        document.querySelectorAll('.modal-backdrop')
                            .forEach(el => el.remove());
                    }
                });

                $('#personas').DataTable().ajax.reload(null, false);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'ERROR',
                    text: response.message || 'Ocurrió un error'
                });
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'ERROR CRÍTICO',
                text: 'No se pudo completar la solicitud'
            });
        });
    }

    

</script>


<style>
    /* para sobreponer */
    .swal2-container {
        z-index: 20000 !important;
    }
    .swal-confirm-dark {
        background-color: #646464 !important;
        color: #fff !important;
        border: none !important;
    }
</style>
