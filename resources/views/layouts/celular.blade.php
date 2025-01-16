@if(session('celular') == null)
<div class="modal fade" id="celularModal" tabindex="-1" role="dialog" aria-labelledby="celularModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-3 fw-bold" id="celularModalLabel">Solicitud de Número de Celular</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="celularForm" action="{{route('celular')}}">
                    @csrf
                    <div class="form-group fs-4">
                        <label for="numeroCelular" class="text-start">Para poder enviar notificaciones importantes de <b>DIRECCIÓN GENERAL</b>, por favor ingresa tu número de <b>celular personal</b> o <b>corporativo</b>. ¡Gracias por tu colaboración!</label>
                        <input type="text" class="form-control fs-1" name="numeroCelular" id="numeroCelular" placeholder="Ej: 3001234567" required maxlength="10" pattern="\d{10}" title="Solo se permiten 10 dígitos." oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">Enviar</button>
            </div>
        </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('#celularModal').modal('show');


        $('#submitBtn').click(function () {
            const numeroCelular = $('#numeroCelular').val();
            if (numeroCelular.length === 10) {
                console.log('Número de celular ingresado:', numeroCelular);
            } else {
                alert('Por favor, ingrese un número de celular válido (10 dígitos).');
            }
        });
    });
</script>
@else
@endif
