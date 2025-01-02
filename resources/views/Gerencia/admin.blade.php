@include('layouts/head')

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
                    title: "¡Error!",
                    html: "{!! session('incorrecto') !!}",
                    confirmButtonColor: '#646464'
                });
            </script>
        </div>
    @endif

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-3 fw-bold" id="exampleModalLabel">Crear usuario o agencia</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('crearusuario')}}" method="POST" id="dynamicForm">
                    @csrf
                    <label for="" class="fw-bold fs-4">Seleccionar acción a realizar:</label>
                    <div class="form-check fs-4">
                        <input class="form-check-input border border-dark border-3" type="radio" name="crear" id="crearDAgencia" value="crearDAgencia" checked>
                        <label class="form-check-label" for="crearDAgencia">
                            Crear Director de Agencia o Auxiliar
                        </label>
                    </div>
                    <div class="form-check fs-4">
                        <input class="form-check-input border-dark border-3" type="radio" name="crear" id="crearCoord" value="crearCoord">
                        <label class="form-check-label" for="crearCoord">
                            Crear Coordinación
                        </label>
                    </div>
                    <div class="form-check fs-4">
                        <input class="form-check-input border-dark border-3" type="radio" name="crear" id="crearJefatura" value="crearJefatura">
                        <label class="form-check-label" for="crearJefatura">
                            Crear Jefatura
                        </label>
                    </div>

                    <div class="form-check fs-4">
                        <input class="form-check-input border-dark border-3" type="radio" name="crear" id="crearAgencia" value="crearAgencia">
                        <label class="form-check-label" for="crearAgencia">
                            Crear Agencia
                        </label>
                    </div>




                    <div id="dynamicFields" class="mt-3 fs-4">
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const dynamicFields = document.getElementById('dynamicFields');

                                // Campos para Director de Agencia
                                const directorFields = `
                                    <label for="nombre" class="form-label fw-bold">Nombre del funcionario:</label>
                                    <input type="text" id="nombre" class="form-control mb-3 border-dark border-3 fs-4" placeholder="Ingrese el nombre del funcionario" name="nombre">
                                    <div class="text-danger" id="error-nombre"></div>

                                    <label for="agencia" class="form-label fw-bold">Seleccionar la agencia:</label>
                                    <select id="agencia" class="form-select mb-3 fs-4 border-dark border-3" name="agenciaDAgencia">
                                        <option value="" disabled selected>Seleccione una agencia</option>
                                        @foreach ($agencias as $agencia)
                                            <option value="{{$agencia->NameAgencia}}"><b>{{$agencia->NumAgencia}}</b> - {{$agencia->NameAgencia}}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger" id="error-agencia"></div>

                                    <label for="correo" class="form-label fw-bold" >Correo de ingreso:</label>
                                    <input type="email" id="correo" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese el correo" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" name="correo">
                                    <div class="text-danger" id="error-correo"></div>

                                    <label for="contrasena" class="form-label fw-bold">Contraseña:</label>
                                    <input type="password" id="contrasena" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese la contraseña" autocomplete="off" name="password">
                                    <div class="text-danger" id="error-contrasena"></div>
                                `;

                                // Campos para Jefatura
                                const jefaturaFields = `
                                    <label for="nombre" class="form-label fw-bold">Nombre del funcionario:</label>
                                    <input type="text" id="nombre" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese el nombre del funcionario" name="nombre">
                                    <div class="text-danger" id="error-nombre"></div>

                                    <label for="departamento" class="form-label fw-bold">Departamento o área:</label>
                                    <input type="text" id="departamento" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese el departamento" list="dptoList" autocomplete="off" name="jefatura">
                                    <datalist id="dptoList">
                                        @foreach ($jefaturas as $jefatura)
                                            <option value="{{$jefatura->agenciau}}">
                                        @endforeach
                                    </datalist>
                                    <div class="text-danger" id="error-departamento"></div>

                                    <label for="correo" class="form-label fw-bold">Correo de ingreso:</label>
                                    <input type="email" id="correo" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese el correo" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" name="correo">
                                    <div class="text-danger" id="error-correo"></div>

                                    <label for="contrasena" class="form-label fw-bold">Contraseña:</label>
                                    <input type="password" id="contrasena" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese la contraseña" autocomplete="off" name="password">
                                    <div class="text-danger" id="error-contrasena"></div>
                                `;

                                const coordinacionFields = `
                                    <label for="nombre" class="form-label fw-bold">Nombre del funcionario:</label>
                                    <input type="text" id="nombre" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese el nombre del funcionario" name="nombre">
                                    <div class="text-danger" id="error-nombre"></div>

                                    <label for="coordinacion" class="form-label fw-bold">Seleccionar la coordinación:</label>
                                    <select id="coordinacion" class="form-select mb-3 fs-4 border-dark border-3" name="selectcoordinacion">
                                        <option value="" disabled selected>Seleccione una coordinación</option>

                                    </select>

                                    <div class="text-danger" id="error-coordinacion"></div>

                                    <label for="agencias" class="form-label fw-bold">Seleccionar agencia(s) a vincular a la coordinación:</label>
                                    <select id="agencias" class="form-select mb-3 fs-4 border-dark border-3">
                                        <option value="" disabled selected>Seleccione agencia(s)</option>
                                        @foreach ($agencias as $agencia)
                                            <option value="{{$agencia->NumAgencia}}">{{$agencia->NumAgencia}} - @if($agencia->NameAgencia == "La Unión")La Unión Valle @else {{$agencia->NameAgencia}} @endif
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="selectedPeopleInput" name="selectedPeople" value="">
                                    <div class="mt-3" id="selectedPeople">

                                        </div>
                                    <div class="text-danger" id="error-agencias"></div>

                                    <label for="correo" class="form-label fw-bold">Correo de ingreso:</label>
                                    <input type="email" id="correo" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese el correo" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" name="correo">
                                    <div class="text-danger" id="error-correo"></div>

                                    <label for="contrasena" class="form-label fw-bold">Contraseña:</label>
                                    <input type="password" id="contrasena" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese la contraseña" autocomplete="off" name="password">
                                    <div class="text-danger" id="error-contrasena"></div>
                                `;

                                agenciaFields = `
                                <label for="nombre" class="form-label fw-bold">Nombre de la agencia:</label>
                                <input type="text" list="agenciaList" id="nombre" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese el nombre de la agencia" name="agencianombre">
                                <div class="text-danger" id="error-nombre"></div>
                                <datalist id="agenciaList">
                                    @foreach ($agencias as $agencia)
                                        <option value="{{$agencia->NameAgencia}}">
                                    @endforeach
                                </datalist>


                                <label for="centrocosto" class="form-label fw-bold">Centro de costo de la agencia:</label>
                                <input type="number" id="centrocosto" list="cuList" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese la ubicación de la agencia" name="centrocosto">
                                <div class="text-danger" id="error-centrocosto"></div>

                                <datalist id="cuList">
                                        @foreach ($agencias as $agencia)
                                            <option value="{{$agencia->NumAgencia}}">
                                        @endforeach
                                </datalist>
                                `;



                                // Actualizar campos dinámicos
                                function updateFields(selectedId) {
                                    if (selectedId === 'crearDAgencia') {
                                        dynamicFields.innerHTML = directorFields;
                                    } else if (selectedId === 'crearCoord') {
                                        dynamicFields.innerHTML = coordinacionFields;
                                        initializeCoordinationLogic();





                                    } else if (selectedId === 'crearAgencia') {
                                        dynamicFields.innerHTML = agenciaFields;
                                    } else if (selectedId === 'crearJefatura') {
                                        dynamicFields.innerHTML = jefaturaFields;
                                    }
                                }

                                document.querySelectorAll('input[name="crear"]').forEach(radio => {
                                    radio.addEventListener('change', function () {
                                        updateFields(this.id);
                                    });
                                });

                                // Mostrar los campos iniciales
                                updateFields('crearDAgencia');

                                // Validar campos
                                const validateFields = () => {
                                    let isValid = true;
                                    document.querySelectorAll('#dynamicFields input, #dynamicFields select').forEach(input => {
                                        const errorDiv = document.getElementById(`error-${input.id}`);
                                        if (!input.value.trim()) {
                                            errorDiv.textContent = 'Este campo es obligatorio';
                                            input.classList.add('is-invalid');
                                            isValid = false;
                                        } else {
                                            errorDiv.textContent = '';
                                            input.classList.remove('is-invalid');
                                        }
                                    });
                                    return isValid;
                                };

                                // Listener para validar al enviar
                                document.getElementById('dynamicForm').addEventListener('submit', function (event) {
                                    if (!validateFields()) {
                                        event.preventDefault();
                                    }
                                });
                            });

                            function initializeCoordinationLogic() {
                                const nombreEmpleadoSelect = document.getElementById('agencias');
                                const selectedPeopleContainer = document.getElementById('selectedPeople');
                                let selectedPeople = [];

                                if (!nombreEmpleadoSelect || !selectedPeopleContainer) return;

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
                                        console.log(selectedPeople);
                                        const selectedPeopleInput = document.getElementById('selectedPeopleInput');

                                        selectedPeopleInput.value = JSON.stringify(selectedPeople);

                                        }
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
                                //cantidad de coordinaciones
                                const selectCoordinacion = document.getElementById('coordinacion');

                                for (let i = 1; i <= 15; i++) {
                                    const option = document.createElement('option');
                                    option.value = i;
                                    option.textContent = `Coordinación ${i}`;
                                    selectCoordinacion.appendChild(option);
                                }



                            }



                        </script>
                    </div>


                </div>
                <div class="modal-footer text-center">
                    <button id="createButton" type="submit" class="fs-4 btn btn-warning fw-bold w-50">Crear</button>
                </div>
            </form>
            </div>
        </div>
    </div>


    {{-- FECHA --}}
    <div class="col-11" style="margin-left:3.5%">
        <div class="">
            <form action="" method="post">
                <div class="d-flex justify-content-between align-items-center" style="margin-top: 8px; margin-right: -14px;">
                    <span class="d-inline mb-0 text-dark text-end" style="font-size: 35px"><b>⭐- PANEL ADMINISTRATIVO -⭐</b></span>
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
                        let amPm = horas >= 12 ? 'PM' : 'AM'; // Se establece 'AM' si horas es menor a 12, de lo contrario, se establece 'PM'

                        // Convertir 0 a 12 AM
                        horas = horas % 12 || 12;

                        const minutos = fecha.getMinutes();
                        const segundos = fecha.getSeconds();

                        return `${mes} ${dia}, ${anio} - ${horas}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')} ${amPm}`;
                    }

                    function actualizarFechaActual() {
                        const elementoFecha = document.getElementById('fechaActual');
                        elementoFecha.textContent = obtenerFechaActual();
                    }

                    setInterval(actualizarFechaActual, 1000);
            </script>


            </form>
            <div class="text-start mb-3">
                <button data-bs-toggle="modal" data-bs-target="#exampleModal" class="shadow-lg mt-3 buttonpro btn btn-warning ">
                    <span>
                        <svg
                        height="24"
                        width="24"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                        >
                        <path d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z" fill="currentColor"></path>
                        </svg>
                        Crear Usuario o Agencia
                    </span>
                </button>

            </div>
        </div>
        <div class="table-responsive mb-5">
            <table id="personas" class="hover table table-striped shadow-lg mt-4 table-hover table-bordered ">
                <thead style="background-color: #646464;">
                    <tr class="text-white">
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">NOMBRE</th>
                        <th scope="col" class="text-center">ROL / AGENCIA / CC</th>
                        <th scope="col" class="text-center">DETALLES</th>
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
            "ajax": "{{ route('datager.dagencia') }}",
            "order": [
                [0, 'asc']
            ],
            scrollY: 420,
            "processing" : true,
            "columns": [{
                data: null,
                render: function(data, type, row, meta) {
                    var numeroRegistro = `<span class='text-danger fw-bold'>${meta.row + 1}</span>`;
                    return numeroRegistro;
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
                data:null,
                        render: function(data, type, row) {
                            var ID = `<span class='fw-bold'>${row.name}</span>`

                            if(row.NameAgencia != null){
                                var ID = `<span class='fw-bold'>${row.NameAgencia}</span>`
                            }

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
                data:null,
                        render: function(data, type, row) {
                            var agenciau = `<span class='text-danger fw-bold'>${row.agenciau}</span>`

                            if(row.NumAgencia != null){
                                var agenciau = `<span class='text-danger fw-bold'>${row.NumAgencia}</span>`
                            }

                            return agenciau
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
                data:null,
                        render: function(data, type, row) {


                            $(document).on('click', '.delete-btn', function(e) {
                                e.preventDefault();


                                var url = $(this).data('url');
                                var id = $(this).data('id');
                                var name = $(this).data('name');
                                if(row.NameAgencia != null){
                                    var name = $(this).data('name');
                                }


                                Swal.fire({
                                    title: `¿Está seguro de eliminar a ${name}?`,
                                    text: "Esta acción no se puede deshacer.",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#d33',
                                    cancelButtonColor: '#3085d6',
                                    confirmButtonText: 'Sí, eliminar',
                                    cancelButtonText: 'Cancelar',
                                    customClass: {
                                        confirmButton: 'btn-confirm',
                                        cancelButton: 'btn-cancel'
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = url;
                                    }
                                });
                            });
                            var id = row.id;
                            var name = row.name;
                            if(row.NameAgencia != null){
                                name = row.NameAgencia
                                id = row.NameAgencia
                            }


                            var url = `admin/eliminar/${id}`;






                            var ModalInfo = `
                                <a type="button" class="btn btn-outline-secondary" id="modalLink_${id}" data-bs-toggle="modal" data-bs-target="#exampleModal_${id}" data-id="${id}">
                                    <i class="fa-solid fa-eye fs-5"></i>
                                </a>

                                <div class="modal fade" id="exampleModal_${id}" tabindex="-1" aria-labelledby="exampleModalLabel_${id}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-3 fw-bold" id="exampleModalLabel_${id}">
                                                    Información ${row.NameAgencia != null ? `Agencia`:`Usuario`} (<span class="text-primary">${row.NameAgencia != null ? `${row.NameAgencia}` : `${row.name}`}</span>)
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('editarusuario')}}" method="POST" id="dynamicForm_${id}">
                                                    @csrf

                                                            ${row.NameAgencia != null ?
                                                            `
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label for="contraseña" class="form-label fw-bold fs-4">Nombre de la agencia:</label>
                                                                        <input type="text" id="contraseña" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese nombre de la agencia" autocomplete="off"  name="agencianame" value="${row.NameAgencia}">
                                                                        <div class="text-danger" id="error-contraseña"></div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label for="contraseña" class="form-label fw-bold fs-4">Centro de costo de la agencia:</label>
                                                                        <input type="number" id="contraseña" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese el centro de costo" autocomplete="off"  name="cc" value="${row.NumAgencia}">
                                                                        <div class="text-danger" id="error-contraseña"></div>
                                                                        <input type="hidden" name="id" value="${row.ID}">
                                                                    </div>
                                                                </div>
                                                            `

                                                            :




                                                            `
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="nombre" class="form-label fw-bold fs-4">Nombre:</label>
                                                                        <input type="text" id="nombre" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese el nombre" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" name="nombre" value="${row.name}">
                                                                        <div class="text-danger" id="error-nombre"></div>

                                                                        ${row.rol == "Consultante" ?
                                                                            `
                                                                            <label for="agencia" class="form-label fw-bold fs-4">Agencia:</label>
                                                                            <select id="agencia" class="form-control mb-3 fs-4 border-dark border-3 fw-bold" name="agencia">
                                                                                <option value="${row.agenciau}" class="fw-bold" selected>${row.agenciau}</option>
                                                                                @foreach ($agencias as $agencia)
                                                                                    <option value="{{ $agencia->NameAgencia }}">
                                                                                        <b>{{ $agencia->NumAgencia }}</b> - {{ $agencia->NameAgencia }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            <div class="text-danger" id="error-agencia"></div>
                                                                            `
                                                                        : row.rol == "Jefatura" ?
                                                                            `
                                                                            <label for="agencia" class="form-label fw-bold fs-4">Jefatura:</label>
                                                                            <input type="text" class="form-control mb-3 fs-4 border-dark border-3 fw-bold" name="jefatura" list="jefaturas" value="${row.agenciau}">
                                                                            <datalist id="jefaturas">
                                                                                @foreach ($jefaturas as $jefatura)
                                                                                    <option value="{{ $jefatura->agenciau }}">
                                                                                        {{ $jefatura->agenciau }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </datalist>
                                                                            <div class="text-danger" id="error-agencia"></div>
                                                                            `
                                                                        : row.rol == "Coordinacion" ?
                                                                            `
                                                                                <label for="coordinacion2" class="form-label fw-bold fs-4">Coordinación:</label>
                                                                                <select id="coordinacion2" class="form-control mb-3 fs-4 border-dark border-3 fw-bold" name="coordinacion2">
                                                                                    <option selected>${row.agenciau}</option>
                                                                                        <option value="Coordinacion 1">Coordinación 1</option>
                                                                                        <option value="Coordinacion 2">Coordinación 2</option>
                                                                                        <option value="Coordinacion 3">Coordinación 3</option>
                                                                                        <option value="Coordinacion 4">Coordinación 4</option>
                                                                                        <option value="Coordinacion 5">Coordinación 5</option>
                                                                                        <option value="Coordinacion 6">Coordinación 6</option>
                                                                                        <option value="Coordinacion 7">Coordinación 7</option>
                                                                                        <option value="Coordinacion 8">Coordinación 8</option>
                                                                                        <option value="Coordinacion 9">Coordinación 9</option>
                                                                                        <option value="Coordinacion 10">Coordinación 10</option>
                                                                                        <option value="Coordinacion 11">Coordinación 11</option>
                                                                                        <option value="Coordinacion 12">Coordinación 12</option>
                                                                                        <option value="Coordinacion 13">Coordinación 13</option>
                                                                                        <option value="Coordinacion 14">Coordinación 14</option>
                                                                                        <option value="Coordinacion 15">Coordinación 15</option>
                                                                                </select>

                                                                            `
                                                                        : ''}

                                                                    </div>


                                                                    <div class="col-md-6">

                                                                        <label for="celular" class="form-label fw-bold fs-4">Número de celular:</label>
                                                                        <input type="tel" id="celular" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese el número de celular" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" name="celular" maxlength="10" value="${row.celular || 0}">
                                                                        <div class="text-danger" id="error-celular"></div>


                                                                        <label for="correo" class="form-label fw-bold fs-4">Contraseña:</label>
                                                                        <input type="text" id="correo" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese la contraseña" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" name="contrasena"    >
                                                                        <div class="text-danger" id="error-correo"></div>
                                                                    </div>
                                                                </div>


                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label for="contraseña" class="form-label fw-bold fs-4">Correo de ingreso:</label>
                                                                        <input type="email" id="contraseña" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese el correo" autocomplete="off" readonly name="correo" value="${row.email}">
                                                                        <div class="text-danger" id="error-contraseña"></div>
                                                                    </div>
                                                                </div>

                                                                ${row.rol == "Coordinacion" ?
                                                                    `<div class="row">
                                                                        <div class="col-12">

                                                                                <label for="contraseña" class="form-label fw-bold fs-4 d-inline">Agencias vinculadas:
                                                                                    ${row.agencias_id == null ?
                                                                                    `
                                                                                    <select id="agencia2" class="form-control mb-3 fs-4 border-dark border-3 fw-bold" name="agencia">
                                                                                        <option value="" class="fw-bold" selected>Vincular Nueva Agencia:</option>

                                                                                    </select>
                                                                                    `
                                                                                    :`
                                                                                    <select id="agencia2" class="form-control mb-3 fs-4 border-dark border-3 fw-bold" name="agencia">
                                                                                        <option value="" class="fw-bold" selected>Vincular Nueva Agencia:</option>
                                                                                    </select>
                                                                                    `}
                                                                                </label>

                                                                                <ul class="list-group lista-agencias"></ul>

                                                                                <input type="hidden" name="agencias_hidden" value="">
                                                                            <div class="text-danger" id="error-contraseña"></div>
                                                                        </div>
                                                                    </div>`
                                                                : ''}



                                                            `}








                                                    <div class="text-end modal-footer">
                                                         <button id="createButton" type="submit" class="fs-4 btn btn-warning fw-bold w-50">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <a type="button" class="btn btn-outline-danger delete-btn" data-url="${url}" data-id="${id}" data-name="${name}">
                                    <i class="fa-solid fa-trash fs-5"></i>
                                </a>
                            `;

                            return ModalInfo;

                        },
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).css({
                                'font-weight': '500',
                                'font-size': '30px',
                                'text-align': 'center',
                            });
                        }
                    },


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
            var buttonsHtml =
                '<div class="custom-buttons mb-2 fs-5">' +
                    '<span class="fw-bold ">Cargar: </span>' +
                    '<button id="btnDAgencia" class="btn btn-success fw-bold mt-0 mt-lg-1 mt-md-2  mt-sm-2  me-1" title="D. Agencia">D. Agencia</button>' +
                    '<button id="btnCoordinaciones" class="btn btn-dark fw-bold mt-0 mt-lg-1 mt-md-2  mt-sm-2  me-1" title="Coordinaciones">Coordinaciones</button>' +
                    '<button id="btnJefaturas" class="btn btn-dark fw-bold mt-0 mt-lg-1 mt-md-2  mt-sm-2  me-1" title="Jefaturas">Jefaturas</button>' +
                    '<button id="btnAgencias" class="btn btn-dark fw-bold mt-0 mt-lg-1 mt-md-2  mt-sm-2  me-1" title="Agencias">Agencias</button>' +
                '</div>';

            $(buttonsHtml).prependTo('#personas_filter');
                $('#btnDAgencia').on('click', function() {
                    var newAjaxSource = '{{ route("datager.dagencia") }}';

                    $('#personas').DataTable().ajax.url(newAjaxSource).load();
                });

                $('#btnCoordinaciones').on('click', function() {
                    var newAjaxSource = '{{ route("coordinaciones") }}';

                    $('#personas').DataTable().ajax.url(newAjaxSource).load();
                });

                $('#btnJefaturas').on('click', function() {
                    var newAjaxSource = '{{ route("datager.jefaturas") }}';

                    $('#personas').DataTable().ajax.url(newAjaxSource).load();

                });
                $('#btnAgencias').on('click', function() {
                    var newAjaxSource = '{{ route("agenciastabla") }}';

                    $('#personas').DataTable().ajax.url(newAjaxSource).load();

                });
                const buttons = document.querySelectorAll('.custom-buttons button');

                buttons.forEach(button => {
                    button.addEventListener('click', function () {
                        buttons.forEach(btn => {
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-dark');
                        });


                        this.classList.remove('btn-dark');
                        this.classList.add('btn-success');
                    });
                });

                $(document).on('click', `[data-bs-target^="#exampleModal_"]`, function (e) {
                    const id = $(this).data('id');
                    const modalId = `#exampleModal_${id}`;
                    const modal = $(modalId);
                    const listaAgencias = modal.find('.lista-agencias');
                    const inputHidden = modal.find('input[name="agencias_hidden"]');
                    const selectAgencia = modal.find('#agencia2'); // Seleccionar el <select>

                    // Limpiar lista y campo oculto al abrir el modal
                    listaAgencias.empty();
                    inputHidden.val('');

                    // Obtener agencias existentes del backend
                    $.ajax({
                        url: `admin/obtener-agencias/${id}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function (response) {
                            if (response.length > 0) {
                                response.forEach(agencia => {
                                    const listItem = `
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            ${agencia.NumAgencia} - <span class="agencia-name fw-bold">${agencia.NameAgencia}</span>
                                            <button type="button" class="btn btn-danger btn-sm eliminar-agencia" data-id="${agencia.NumAgencia}">x</button>
                                        </li>`;
                                    listaAgencias.append(listItem);
                                });
                                if (response.length > 4) {
                                    listaAgencias.addClass('scrollable-list');
                                } else {
                                    listaAgencias.removeClass('scrollable-list');
                                }

                                const ids = response.map(agencia => agencia.NumAgencia);
                                inputHidden.val(JSON.stringify(ids));
                            } else {
                                listaAgencias.append('<li class="list-group-item text-muted">No hay agencias disponibles.</li>');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Error al obtener agencias: ", error);
                        }
                    });

                        // Manejar la selección de agencias desde el <select>
                        selectAgencia.off('change').on('change', function () {
                            const selectedOption = $(this).find(':selected'); // Obtener opción seleccionada
                            const agenciaId = selectedOption.val();
                            const agenciaNameRaw = selectedOption.text();
                            const agenciaName = agenciaNameRaw.split('-')[1]?.trim();


                            // Verificar si la opción es válida y no está ya en la lista
                            if (agenciaId && !listaAgencias.find(`[data-id="${agenciaId}"]`).length) {
                                const listItem = `
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        ${agenciaId} - <span class="agencia-name fw-bold">${agenciaName}</span>
                                        <button type="button" class="btn btn-danger btn-sm eliminar-agencia" data-id="${agenciaId}">x</button>
                                    </li>`;
                                listaAgencias.append(listItem);

                                // Actualizar el campo oculto
                                const existingIds = JSON.parse(inputHidden.val() || '[]');
                                existingIds.push(agenciaId);
                                inputHidden.val(JSON.stringify(existingIds));
                            }
                            // Reiniciar el select a la opción predeterminada
                            $(this).val('');
                        });


                    });


                $(document).on('click', '.eliminar-agencia', function () {
                    const id = $(this).data('id').toString(); // ID de la agencia a eliminar
                    const listItem = $(this).closest('li'); // Elemento de la lista a eliminar
                    const modal = $(this).closest('.modal'); // Modal que contiene el select
                    const inputHidden = modal.find('input[name="agencias_hidden"]'); // Input hidden que guarda los IDs de las agencias

                    if (!inputHidden.length) {
                        console.error("No se encontró el input hidden dentro del modal.");
                        return;
                    }

                    let currentIds;
                    try {
                        currentIds = JSON.parse(inputHidden.val() || '[]');
                    } catch (error) {
                        console.error("Error al parsear el JSON del input hidden:", error);
                        currentIds = [];
                    }

                    const updatedIds = currentIds.filter(agenciaId => agenciaId !== id);


                    inputHidden.val(JSON.stringify(updatedIds));

                    listItem.remove();

                    const selectAgencia = modal.find('#agencia2');
                    const agenciaName = listItem.find('span.agencia-name').text();
                    console.log(agenciaName);

                    const option = `<option value="${id}">${id} - ${agenciaName}</option>`;
                    selectAgencia.append(option);
                });


                $(document).on('click', `[data-bs-target^="#exampleModal_"]`, function (e) {
                    const id = $(this).data('id');
                    const modalId = `#exampleModal_${id}`;
                    const modal = $(modalId);
                    const listaAgencias = modal.find('.lista-agencias');
                    const inputHidden = modal.find('input[name="agencias_hidden"]');

                    $.ajax({
                        url: `admin/obtener-agencias-select/${id}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function (response) {
                            const selectAgencia = modal.find('#agencia2');

                            selectAgencia.empty();
                            selectAgencia.append('<option value="" class="fw-bold" selected>Vincular Nueva Agencia:</option>');

                            if (response.length > 0) {
                                response.forEach(agencia => {
                                    const option = `<option value="${agencia.NumAgencia}">${agencia.NumAgencia} - ${agencia.NameAgencia}</option>`;
                                    selectAgencia.append(option);
                                });
                            } else {
                                selectAgencia.append(`
                                    @foreach ($agencias as $agencia)
                                        <option value="{{ $agencia->NameAgencia }}">
                                            <b>{{ $agencia->NumAgencia }}</b> - {{ $agencia->NameAgencia }}
                                        </option>
                                    @endforeach`);
                            }


                            selectAgencia.off('change').on('change', function () {
                                const selectedOption = $(this).find(':selected');
                                const agenciaId = selectedOption.val();
                                const agenciaNameRaw = selectedOption.text();
                                const agenciaName = agenciaNameRaw.split('-')[1]?.trim();


                                if (agenciaId) {
                                    const listItem = `
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            ${agenciaId} - <span class="agencia-name fw-bold">${agenciaName}</span>
                                            <button type="button" class="btn btn-danger btn-sm eliminar-agencia" data-id="${agenciaId}">x</button>
                                        </li>`;
                                    listaAgencias.append(listItem);


                                    const existingIds = JSON.parse(inputHidden.val() || '[]');
                                    existingIds.push(agenciaId);
                                    inputHidden.val(JSON.stringify(existingIds));

                                    selectedOption.remove();
                                }

                                $(this).val('');
                            });
                        },
                        error: function (xhr, status, error) {
                            console.error("Error al obtener agencias: ", error);
                        }
                    });
                });




                },
        });



        function csesion() {
            var respuesta = confirm("¿Estas seguro que deseas cerrar sesión?")
            return respuesta
        }




    </script>

    </div>

    </div>
    {{-- ESTILOS --}}
    <style>

                .scrollable-list {
                    max-height: 200px;
                    overflow-y: auto;
                }
                .btn-confirm {
                    font-size: 25px;
                    font-weight: bold;
                    padding: 10px 20px;
                }

                .btn-cancel {
                    font-size: 25px;
                    font-weight: bold;
                    padding: 10px 20px;
                }


                .buttonpro {
                border-radius: 0.9em;
                cursor: pointer;
                padding: 0.8em 1.2em 0.8em 1em;
                transition: all ease-in-out 0.2s;
                font-size: 16px;
                }

                .buttonpro span {
                display: flex;
                justify-content: center;
                align-items: center;
                color: #000000;
                font-weight: 600;
                }


            .input-group-text {
                position: relative; /* Añade posicionamiento relativo */
            }

            .tooltip1:hover::after {
                content: "Cédula / NIT";
                position: absolute;
                bottom: calc(100% + 5px); /* Cambia la posición a la parte superior */
                left: 50%;
                transform: translateX(-50%);
                padding: 5px;
                background-color: rgba(0, 0, 0, 0.8);
                color: white;
                border-radius: 5px;
                font-size: 14px;
            }

            .tooltip2:hover::after {
                content: "Cuenta";
                position: absolute;
                bottom: calc(100% + 5px); /* Cambia la posición a la parte superior */
                left: 50%;
                transform: translateX(-50%);
                padding: 5px;
                background-color: rgba(0, 0, 0, 0.8);
                color: white;
                border-radius: 5px;
                font-size: 14px;
            }

            .tooltip3:hover::after {
                content: "Nombre / Nombre Empresa";
                position: absolute;
                bottom: calc(100% + 5px); /* Cambia la posición a la parte superior */
                left: 50%;
                transform: translateX(-50%);
                padding: 5px;
                background-color: rgba(0, 0, 0, 0.8);
                color: white;
                border-radius: 5px;
                font-size: 14px;
            }

            .tooltip4:hover::after {
                content: "Convención";
                position: absolute;
                bottom: calc(100% + 5px); /* Cambia la posición a la parte superior */
                left: 50%;
                transform: translateX(-50%);
                padding: 5px;
                background-color: rgba(0, 0, 0, 0.8);
                color: white;
                border-radius: 5px;
                font-size: 14px;
            }


        .input {
            max-width: 190px;
            display: none;
        }

        .labelFile {
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 250px;
            height: 100px;
            border: 2px dashed #ccc;
            align-items: center;
            text-align: center;
            padding: 5px;
            color: #404040;
            cursor: pointer;
        }

        #uploadMessage {
            display: none;
            color: green;
            font-weight: bold;
        }

        .custom-buttons {
            display: absolute;

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


        .tooltip-container {
            position: relative;
            display: inline-block;
            margin: 0px;
            }


            .col::-webkit-scrollbar {
            width: 10px;  /*Ancho de la barra de desplazamiento */
            }

            .col::-webkit-scrollbar-track {
            background: #f1eeed; /*Color de fondo de la barra de desplazamiento */
            }

            .col::-webkit-scrollbar-thumb {
            background: #bea232;  /*Color del botón de desplazamiento */
            }




            .text {
            color: #333;
            font-size: 18px;
            cursor: pointer;
            }

            .tooltip {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            visibility: hidden;
            background: #898989;
            color: #fff;
            font-weight: bold;
            padding: 7px;
            border-radius: 4px;
            transition: opacity 0.3s, visibility 0.3s, top 0.3s, background 0.3s;
            z-index: 1;
            width: 500px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);

            }

            .tooltip::before {
            content: "";
            position: absolute;
            bottom: 100%;
            left: 50%;
            border-width: 8px;
            border-style: solid;
            border-color: transparent transparent #898989 transparent;
            transform: translateX(-50%);

            }

            .tooltip-container:hover .tooltip {
            top: 120%;
            opacity: 1;
            visibility: visible;
            background: #898989;
            transform: translate(-50%, 0px);

            }
    </style>
    </div>
    @include('layouts.footer')

</body>

</html>
