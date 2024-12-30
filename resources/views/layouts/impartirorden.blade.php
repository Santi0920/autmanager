<div id="impartirtarea">
    <label for="" class="fw-bold fs-4 mb-3">Seleccionar grupo o empleado:</label>



    <select class="form-select fs-4 border-dark border-3" aria-label="Default select example" name="nombreempleado" id="nombreempleado2" required>
        <option value="">Seleccionar una opción</option>
        <option class="fw-semibold" value="" disabled>↓-------- Grupos Creados --------↓</option>
        @foreach ($gruposcreados as $grupo)
            <option class="fw-bold" value="{{$grupo->nombregrupo}}">{{$grupo->nombregrupo}}</option>
        @endforeach
        <option class="fw-semibold" value="" disabled>↓-------- Individual --------↓</option>
        @foreach ($cargos as $cargo)
            <option value="{{$cargo->name}}">{{$cargo->name ." - ". $cargo->agenciau}}</option>
        @endforeach
    </select>
    <div id="selectedPeople2" style="display: block;"></div>
    <input type="hidden" id="selectedPeopleInput2" name="selectedPeopleInput2">

    <label for="" class="fw-bold fs-4 mt-3">Seleccionar tipo de orden de trabajo:</label>
    <div class="form-check fs-4">
        <input class="form-check-input border border-dark border-3" type="radio" name="tipoorden" id="politica" value="politica" required>
        <label class="form-check-label" for="politica">
            Política
        </label>
    </div>
    <div class="form-check fs-4">
        <input class="form-check-input border-dark border-3" type="radio" name="tipoorden" id="tarea" value="tarea" required>
        <label class="form-check-label" for="tarea">
            Tarea
        </label>
    </div>



    <div class="mt-3">
        <label for="descripcion" class="fw-bold fs-4">Descripción Orden de Trabajo:</label>
        <textarea class="form-control fs-4 border border-dark border-3" id="descripcion" name="descripcion" rows="5" placeholder="Escribir aquí..." required></textarea>

        <button type="button" id="emoji-button" class="btn btn-secondary mt-2" required>😊 Emojis</button>


        <div id="emoji-panel" style="display: none; border: 1px solid #ccc; padding: 10px; margin-top: 5px; background: #fff; cursor: pointer; font-size: 25px">

            <span class="emoji" data-emoji="0️⃣">0️⃣</span>
            <span class="emoji" data-emoji="1️⃣">1️⃣</span>
            <span class="emoji" data-emoji="2️⃣">2️⃣</span>
            <span class="emoji" data-emoji="3️⃣">3️⃣</span>
            <span class="emoji" data-emoji="4️⃣">4️⃣</span>
            <span class="emoji" data-emoji="5️⃣">5️⃣</span>
            <span class="emoji" data-emoji="6️⃣">6️⃣</span>
            <span class="emoji" data-emoji="7️⃣">7️⃣</span>
            <span class="emoji" data-emoji="8️⃣">8️⃣</span>
            <span class="emoji" data-emoji="9️⃣">9️⃣</span>
            <span class="emoji" data-emoji="👍">👍</span>
            <span class="emoji" data-emoji="👈">👈</span>
            <span class="emoji" data-emoji="👉">👉</span>
            <span class="emoji" data-emoji="🟡">🟡</span>
            <span class="emoji" data-emoji="🟠">🟠</span>
            <span class="emoji" data-emoji="🔴">🔴</span>
            <span class="emoji" data-emoji="🟣">🟣</span>
            <span class="emoji" data-emoji="🟤">🟤</span>
            <span class="emoji" data-emoji="⚫">⚫</span>
            <span class="emoji" data-emoji="⚪">⚪</span>
            <span class="emoji" data-emoji="🟢">🟢</span>
            <span class="emoji" data-emoji="🔵">🔵</span>
        </div>
    </div>

    <div id="">
            <label for="" class="fw-bold fs-4 mt-3">Seleccionar estado a asignar:</label>
            <div id="politica" class="">
                <div class="form-check fs-4">
                    <input class="form-check-input border border-dark border-3" type="radio" name="estadopolitica" id="estado-permanente" value="PERMANENTE" required>
                    <label class="form-check-label" for="estado-permanente">
                        Permanente
                    </label>
                </div>
                <div class="form-check fs-4">
                    <input class="form-check-input border-dark border-3" type="radio" name="estadopolitica" id="estado-temporal" value="TEMPORAL" required>
                    <label class="form-check-label" for="estado-temporal">
                        Temporal
                    </label>
                </div>
                <div class="form-check fs-4">
                    <input class="form-check-input border-dark border-3" type="radio" name="estadopolitica" id="laboracumplir" value="LABOR A CUMPLIR" required>
                    <label class="form-check-label" for="laboracumplir">
                        Labor a Cumplir
                    </label>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('input[name="tipoorden"]').on('change', function() {
            if ($('#impartirorden').is(':checked')) {
                $('#impartirtarea').removeClass('d-none');
                $('#grupos').addClass('d-none');
                $('#buttonmodal').removeClass('d-none');
            } else if ($('#gruposacciones').is(':checked')) {
                $('#grupos').removeClass('d-none');
                $('#impartirtarea').addClass('d-none');
                $('#buttonmodal').addClass('d-none');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
        let selectedPeople2 = [];  // Renombrado a selectedPeople2

        // Seleccionamos el select y el contenedor para las personas seleccionadas
        const nombreEmpleadoSelect = document.getElementById('nombreempleado2');
        const selectedPeopleContainer = document.getElementById('selectedPeople2'); // Renombrado a selectedPeople2
        const selectedPeopleInput = document.getElementById('selectedPeopleInput2'); // Si tienes un input oculto para los IDs

        if (!nombreEmpleadoSelect || !selectedPeopleContainer) return;

        // Event listener para manejar las selecciones en el select
        nombreEmpleadoSelect.addEventListener('change', function() {
            const selectedOption = nombreEmpleadoSelect.options[nombreEmpleadoSelect.selectedIndex];
            const value = selectedOption.value;

            // Depuración: Verificar el valor seleccionado
            console.log('Opción seleccionada:', value);

            if (value && !selectedPeople2.includes(value)) {  // Usando selectedPeople2
                // Si no está ya en selectedPeople2, lo añadimos
                selectedPeople2.push(value);

                // Crear el div con la persona seleccionada
                const personElement = document.createElement('div');
                personElement.className = 'selected-person';
                personElement.innerHTML = `${selectedOption.text} <button class="btn btn-danger btn-sm remove-person mb-2" data-id="${value}">X</button>`;

                // Añadir la persona al contenedor
                selectedPeopleContainer.appendChild(personElement);

                // Deshabilitar la opción seleccionada en el select
                selectedOption.disabled = true;

                // Actualizamos el valor del input oculto
                if (selectedPeopleInput) {
                    selectedPeopleInput.value = JSON.stringify(selectedPeople2);  // Usando selectedPeople2
                }

                // Depuración: Verificar los elementos seleccionados
                console.log('Personas seleccionadas:', selectedPeople2);  // Usando selectedPeople2
            }
        });

        // Event listener para manejar la eliminación de personas seleccionadas
        selectedPeopleContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-person')) {
                const employeeId = e.target.getAttribute('data-id');

                // Filtramos el ID eliminado
                selectedPeople2 = selectedPeople2.filter(id => id !== employeeId);  // Usando selectedPeople2

                // Eliminamos el div de la persona seleccionada
                e.target.parentElement.remove();

                // Habilitamos nuevamente la opción en el select
                const optionToEnable = nombreEmpleadoSelect.querySelector(`option[value="${employeeId}"]`);
                if (optionToEnable) {
                    optionToEnable.disabled = false;
                }

                // Actualizamos el valor del input oculto
                if (selectedPeopleInput) {
                    selectedPeopleInput.value = JSON.stringify(selectedPeople2);  // Usando selectedPeople2
                }

                // Depuración: Verificar los elementos seleccionados después de la eliminación
                console.log('Personas seleccionadas después de eliminar:', selectedPeople2);  // Usando selectedPeople2
            }
        });

        // Función para habilitar otros campos si es necesario
        function habilitarTipoOrden() {
            console.log('Opción seleccionada: ', nombreEmpleadoSelect.value);
        }
    });


    </script>
