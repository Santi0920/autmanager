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
                                            <option value="{{$agencia->ID}}">{{$agencia->NumAgencia}} - @if($agencia->NameAgencia == "La Unión")La Unión Valle @else {{$agencia->NameAgencia}} @endif
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="selectedPeopleInput" name="selectedPeople" value="">
                                    <div class="text-danger" id="error-agencias"></div>
                                    <div class="mt-3" id="selectedPeople">

                                    </div>

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
                                <input type="text" id="centrocosto" list="cuList" class="form-control mb-3 fs-4 border-dark border-3" placeholder="Ingrese la ubicación de la agencia" name="centrocosto">
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
            <table id="personas" class="hover table table-striped shadow-lg mt-4 table-hover table-bordered">
                <thead style="background-color: #646464;">
                    <tr class="text-white">
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">NOMBRE</th>
                        <th scope="col" class="text-center">ROL / AGENCIA</th>
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
                data: 'name',
                        render: function(data, type, row) {
                            var ID = `<span class='fw-bold'>${row.name}</span>`

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
                data: 'name',
                        render: function(data, type, row) {
                            var agenciau = `<span class='text-danger fw-bold'>${row.agenciau}</span>`

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
                data: 'name',
                        render: function(data, type, row) {
                            id = row.id
                            var ModalInfo = `
                            <a type="button" class="btn btn-outline-secondary" id="modalLink_${id}" data-bs-toggle="modal" data-bs-target="#exampleModal_${id}"
                                        data-id="${id}">
                                        <i class="fa-solid fa-eye fs-5"></i>
                            </a>
                            `

                            return ModalInfo
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
            var buttonsHtml = '<div class="custom-buttons mb-2 fs-5">' +
                    '<span class="fw-bold ">Cargar: </span>' +
                    '<button id="btnDAgencia" class="btn btn-success fw-bold mt-0 mt-lg-1 mt-md-2  mt-sm-2  me-1" title="D. Agencia">D. Agencia</button>' +
                    '<button id="btnCoordinaciones" class="btn btn-dark fw-bold mt-0 mt-lg-1 mt-md-2  mt-sm-2  me-1" title="Coordinaciones">Coordinaciones</button>' +
                    '<button id="btnJefaturas" class="btn btn-dark fw-bold mt-0 mt-lg-1 mt-md-2  mt-sm-2  me-1" title="Jefaturas">Jefaturas</button>' +
                    '<button id="btnAgencias" class="btn btn-dark fw-bold mt-0 mt-lg-1 mt-md-2  mt-sm-2  me-1" title="Agencias">Agencias</button>' +
                '</div>';

            $(buttonsHtml).prependTo('.dataTables_filter');
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
