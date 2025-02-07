
<div id="grupos" class="d-none">
    <label for="groupName" class="fw-bold fs-4 mt-3">Crear Grupo o Agregar Personas a un Grupo Existente:</label><br>
    <input type="text" class="form-control fs-4 border border-dark border-3 w-75" id="groupName" list="groupList" placeholder="Nombre del Grupo">

    <select class="form-select fs-4 border-dark border-3 mt-3 w-75" aria-label="Default select example" name="nombreempleado" id="nombreempleado">
        <option value="">Añadir personas al grupo:</option>
        @include('layouts/selectpersonas')


    </select>

    <div class="mt-3" id="selectedPeople">

    </div>

    <button class="btn btn-warning mt-1 fs-4 fw-bold" id="guardarGrupo">Guardar Grupo</button><br>

    <label for="" class="fw-bold fs-4 mt-3">Grupos:</label>
    <table class="table table-striped fs-5 table-hover table-bordered" id="groups">
        <thead style="background-color: #646464;">
            <tr class="text-white">
                <th style="width: 30%">Nombre del Grupo</th>
                <th class="" style="width: 60%">Integrantes</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="createdGroups">

        </tbody>
    </table>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
    const nombreEmpleadoSelect = document.getElementById('nombreempleado');
    const selectedPeopleContainer = document.getElementById('selectedPeople');
    let selectedPeople = [];

    nombreEmpleadoSelect.addEventListener('change', function () {
        const selectedOption = nombreEmpleadoSelect.options[nombreEmpleadoSelect.selectedIndex];
        const employeeId = selectedOption.value;
        const employeeName = selectedOption.text;

        if (employeeId && !selectedPeople.includes(employeeId)) {
            selectedPeople.push(employeeId);

            const personElement = document.createElement('div');
            personElement.className = 'selected-person';
            personElement.innerHTML = `${employeeName} <button class="btn btn-danger btn-sm remove-person mb-2" data-id="${employeeId}">X</button>`;
            selectedPeopleContainer.appendChild(personElement);

            selectedOption.disabled = true;
        }

        // Reabrir el select después de seleccionar
        setTimeout(() => {
            nombreEmpleadoSelect.focus();
            const maxVisibleOptions = 10; 
            nombreEmpleadoSelect.size = Math.min(nombreEmpleadoSelect.options.length, maxVisibleOptions); // Limita el tamaño visible
        }, 100);
    });




    selectedPeopleContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-person')) {
            const employeeId = e.target.getAttribute('data-id');

            selectedPeople = selectedPeople.filter(id => id !== employeeId);


            e.target.parentElement.remove();


            const optionToEnable = nombreEmpleadoSelect.querySelector(`option[value="${employeeId}"]`);
            optionToEnable.disabled = false;
        }
    });


    // Guardar el grupo
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    document.getElementById('guardarGrupo').addEventListener('click', function () {
        const groupName = document.getElementById('groupName').value;

        if (groupName && selectedPeople.length > 0) {
            fetch('otrabajo/guardar-grupo', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    name: groupName,
                    members: selectedPeople
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success2 || data.success) {
                    alert(`Grupo ${groupName} guardado con éxito!`);

                    loadGroups();
                    selectLoad();


                    document.getElementById('nombreempleado2').selectedIndex = 0;
                    selectedPeopleContainer.innerHTML = '';
                    document.getElementById('groupName').value = '';
                    selectedPeople = [];
                    nombreEmpleadoSelect.querySelectorAll('option').forEach(option => option.disabled = false);
                }
            });
        } else {
            alert('Debe asignar un nombre al grupo y seleccionar al menos una persona.');
        }
    });


});

        function loadGroups() {
            fetch('otrabajo/ruta-para-cargar-grupos')
            .then(response => response.json())
            .then(data => {
                const createdGroups = document.getElementById('createdGroups');
                createdGroups.innerHTML = '';

                data.forEach(group => {
                    let integrantesArray = [...group.integrantes];

                    const row = document.createElement('tr');
                    const integrantesList = document.createElement('ul');
                    const displayedCount = 2;


                    function updateIntegrantesList(showAll = false) {
                        integrantesList.innerHTML = '';


                        const integrantesToShow = showAll ? integrantesArray : integrantesArray.slice(0, displayedCount);

                        integrantesToShow.forEach((integrante, index) => {
                            const listItem = document.createElement('li');


                            const deleteButton = document.createElement('span');
                            deleteButton.textContent = 'X ';
                            deleteButton.style.cursor = 'pointer';
                            deleteButton.style.color = 'red';
                            deleteButton.style.marginRight = '10px';
                            deleteButton.title = 'Eliminar'


                            deleteButton.addEventListener('click', () => {
                                const integranteId = integrantesArray[index];



                                const confirmation = window.confirm(`¿Está seguro de eliminar a ${integrante} del grupo ${group.nombregrupo}?`);

                                if (confirmation) {

                                    fetch(`otrabajo/eliminar-integrante/${group.id}/${integranteId}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                            'Content-Type': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(result => {
                                        if (result.success) {
                                            integrantesArray.splice(index, 1);
                                            updateIntegrantesList();
                                            alert('Eliminado Correctamente!')
                                        } else {
                                            alert('Error al eliminar el integrante');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Error al eliminar el integrante');
                                    });
                                }
                            });


                            listItem.appendChild(deleteButton);
                            listItem.appendChild(document.createTextNode(integrante));
                            integrantesList.appendChild(listItem);
                        });

                        if (!showAll && integrantesArray.length > displayedCount) {
                            const leerMas = document.createElement('button');
                            leerMas.textContent = 'Leer más';
                            leerMas.className = 'btn btn-link';
                            leerMas.style.cursor = 'pointer';

                            leerMas.addEventListener('click', () => {
                                updateIntegrantesList(true);
                                leerMas.style.display = 'none';

                                const leerMenos = document.createElement('button');
                                leerMenos.textContent = 'Leer menos';
                                leerMenos.className = 'btn btn-link';
                                leerMenos.style.cursor = 'pointer';

                                leerMenos.addEventListener('click', () => {
                                    updateIntegrantesList(false);
                                    leerMas.style.display = 'inline';
                                    leerMenos.style.display = 'none';
                                });

                                integrantesList.appendChild(leerMenos);
                            });

                            integrantesList.appendChild(leerMas);
                        }
                    }

                    updateIntegrantesList();

                    row.innerHTML = `
                        <td>
                            <input type='text' class='w-75' value='${group.nombregrupo}' id='groupNameInput_${group.id}'>
                            <button class='btn btn-success' onclick='updateGroup(${group.id})'><i class="fa-solid fa-check"></i></button>
                        </td>
                        <td></td>
                        <td>
                            <button class='btn btn-danger' onclick='deleteGroup(${group.id})'><i class="fa-solid fa-trash"></i></button>
                        </td>
                    `;

                    const integrantesCell = row.cells[1];
                    integrantesCell.appendChild(integrantesList);
                    createdGroups.appendChild(row);
                });
            });
        }



        function deleteGroup(groupId) {
            if (confirm("¿Estás seguro de que quieres eliminar este grupo?")) {
                fetch(`otrabajo/eliminar-grupo/${groupId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Grupo eliminado con éxito');
                        loadGroups();
                        selectLoad();

                    } else {
                        alert('Error al eliminar el grupo');
                    }
                });
            }
        }

        function selectLoad() {
            fetch('otrabajo/recargar', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const nombreEmpleadoSelect = document.getElementById('nombreempleado2');

                nombreEmpleadoSelect.innerHTML = `
                    <option value="">Seleccionar una opción</option>
                    <option class="fw-semibold" value="" disabled>↓-------- Grupos Creados --------↓</option>
                `;

                data.grupos.forEach(grupo => {
                    const option = document.createElement('option');
                    option.classList.add('fw-bold');
                    option.value = grupo.nombregrupo;
                    option.textContent = grupo.nombregrupo;
                    nombreEmpleadoSelect.appendChild(option);
                });

                nombreEmpleadoSelect.innerHTML += `
                    <option class="fw-semibold" value="" disabled>↓-------- Individual --------↓</option>

                `;
            });
        }

        document.addEventListener('DOMContentLoaded', loadGroups);





        function updateGroup(groupId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const newName = document.getElementById(`groupNameInput_${groupId}`).value;

            fetch(`otrabajo/actualizar-grupo/${groupId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    name: newName
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Grupo actualizado a: ${newName}`);
                    loadGroups();
                    selectLoad();
                } else {
                    alert('Error al actualizar el grupo');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar el grupo');
            });
        }


</script>
