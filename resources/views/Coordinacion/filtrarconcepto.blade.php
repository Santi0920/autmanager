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
                    title: "{{ session('incorrecto') }}",
                    text: '',
                    confirmButtonColor: '#646464',
                    timer: 10000

                });
            </script>
        </div>
    @endif
    {{-- FECHA --}}
    <div class="col-11" style="margin-left:3.5%">
        <div class="">
            <form action="" method="post">
                <div class="d-flex justify-content-between align-items-center" style="margin-top: 8px; margin-right: -14px;">
                    <span class="d-inline mb-0 text-dark text-end" style="font-size: 35px"><b>⭐FILTRAR POR CONCEPTO⭐</b></span>
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
        </div>
        <div class="table-responsive mb-5">
            <table id="personas" class="hover table table-striped shadow-lg mt-4 table-hover table-bordered">
                <thead style="background-color: #646464;">
                    <tr class="text-white">
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center" style="">CONCEPTO</th>
                        <th scope="col" class="text-center">FECHA</th>
                        <th scope="col" class="text-center">C.U</th>
                        <th scope="col" class="text-center">CÉDULA</th>
                        <th scope="col" class="text-center">CUENTA</th>
                        <th scope="col" class="text-center">NOMBRE</th>
                        <th scope="col" class="text-center" style="">DETALLE</th>
                        <th scope="col" class="text-center">SOLICITADORPOR</th>
                        <th scope="col" class="text-center">CONVENCION</th>
                        <th scope="col" class="text-center">ESTADO</th>
                        <th scope="col" class="text-center">VALIDADOPOR</th>
                        <th scope="col" class="text-center">FECHA VALIDACIÓN</th>
                        <th scope="col" class="text-center">OBSERVACIONES GERENCIA</th>
                        <th scope="col" class="text-center">FECHA APROBACION</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">

                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <input type="text" class="form-control filter-input fs-4 fw-bold" name="" id="" placeholder="ID" data-column="0" />
                        </td>
                        <td>
                            <select name="" id="" data-column="1" class="form-control filter-select fs-4 fw-bold">
                                <option value="">CONCEPTO...</option>
                                @foreach ($user as $autorizacion)
                                        <option class="fw-semibold" value="{{ $autorizacion->Concepto }}">
                                            {{ $autorizacion->Concepto }}
                                        </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control filter-input fs-4 fw-bold" name="" id="" placeholder="FECHA" data-column="2" />
                        </td>
                        <td>
                            <input type="text" class="form-control filter-input fs-4 fw-bold" name="" id="" placeholder="C.U" data-column="3" />
                        </td>
                        <td>
                            <input type="text" class="form-control filter-input fs-4 fw-bold" name="" id="" placeholder="CÉDULA" data-column="4" />
                        </td>
                        <td>
                            <input type="text" class="form-control filter-input fs-4 fw-bold" name="" id="" placeholder="CUENTA" data-column="5" />
                        </td>
                        <td>
                            <input type="text" class="form-control filter-input fs-4 fw-bold" name="" id="" placeholder="NOMBRE" data-column="6" />
                        </td>
                        <td>
                            <input type="text" class="form-control filter-input fs-4 fw-bold" name="" id="" placeholder="DETALLE" data-column="7" />
                        </td>
                        <td>
                            <input type="text" class="form-control filter-input fs-4 fw-bold" name="" id="" placeholder="SOLICITADO POR" data-column="8" />
                        </td>
                        <td>
                            <input type="text" class="form-control filter-input fs-4 fw-bold" name="" id="" placeholder="CONVENCION" data-column="9" />
                        </td>
                        <td>
                            <input type="text" class="form-control filter-input fs-4 fw-bold" name="" id="" placeholder="ESTADO" data-column="10" />
                        </td>
                        <td>
                            <input type="text" class="form-control filter-input fs-4 fw-bold" name="" id="" placeholder="VALIDADO POR" data-column="11" />
                        </td>
                        <td>
                            <input type="text" class="form-control filter-input fs-4 fw-bold" name="" id="" placeholder="FECHA VALIDACION" data-column="12" />
                        </td>
                        <td>
                            <input type="text" class="form-control filter-input fs-4 fw-bold" name="" id="" placeholder="OBSERVACIONES GER" data-column="13" />
                        </td>
                        <td>
                            <input type="text" class="form-control filter-input fs-4 fw-bold" name="" id="" placeholder="FECHA APROBACION" data-column="14" />
                        </td>
                    </tr>

                </tfoot>
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
    <script src="js/condicionNit.js"></script>
    <script>
        var table = $('#personas').DataTable({
            "ajax": "{{ route('datacoor.filtrarconcepto') }}",
            "order": [
                [0, 'asc']
            ],
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
                    data: 'CodigoAutorizacion',
                    render: function(data, type, row) {
                        var Codigo =
                            `${row.Concepto}`

                        return Codigo
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
                    data: 'Fecha',
                    render: function(data, type, row) {
                        var dato =
                            `${row.Fecha}`

                        return dato
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
                    data: null,
                    render: function(data, type, row) {
                        var dato =
                            `${row.NumAgencia}`

                        return dato
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
                    data: null,
                    render: function(data, type, row) {
                        var dato =
                            `${row.Cedula}`

                        return dato
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
                    data: null,
                    render: function(data, type, row) {
                        var dato =
                            `${row.CuentaAsociado}`

                            if(row.CuentaAsociado == null){
                            return 'N/A'
                        }else{
                            return dato
                        }
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
                    data: null,
                    render: function(data, type, row) {
                        var dato =
                            `${row.NombrePersona}`

                        return dato
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
                    data: null,
                    render: function(data, type, row) {
                        var textoCorto = row.Detalle.substring(0, 100); // Ajusta el número de caracteres
                        var textoCompleto = row.Detalle;

                        var dato = `
                            <span class="texto-corto">${textoCorto}...</span>
                            <span class="texto-completo">${textoCompleto}</span>
                            <span class="leer-mas">Leer más</span>
                        `;

                        return dato;
                    },
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).css({
                            'font-weight': '500',
                            'font-size': '20px',
                            'text-align': 'justify'
                        });

                        $(td).find('.leer-mas').on('click', function() {
                            var $textoCorto = $(td).find('.texto-corto');
                            var $textoCompleto = $(td).find('.texto-completo');
                            var $leerMas = $(this);

                            if ($textoCorto.is(':visible')) {
                                $textoCorto.hide();
                                $textoCompleto.show();
                                $leerMas.text('Leer menos');
                            } else {
                                $textoCorto.show();
                                $textoCompleto.hide();
                                $leerMas.text('Leer más');
                            }
                        });
                    }
                },

                {
                    data: null,
                    render: function(data, type, row) {
                        var dato =
                            `${row.SolicitadoPor}`

                        return dato
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
                    data: null,
                    render: function(data, type, row) {
                        var dato =
                            `${row.Convencion}`

                        if(row.Convencion == null){
                            return 'N/A'
                        }else{
                            return dato
                        }

                    },
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).css({
                            'font-weight': 'bold',
                            'font-size': '20px',
                            'text-align': 'justify'
                        });
                    }
                },


                {
                    data: 'Estado',
                    render: function(data, type, row) {
                        // Supongo que deseas mostrar el ID, no un botón de Aprobado, por lo que he cambiado el nombre de la variable a 'IDLabel'
                        if (row.Estado == 1) {
                            var Estado = `<div class="btn btn-warning shadow" style="padding: 0.4rem 1.4rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">
                                <i class="fa-solid fa-check"></i> VALIDADO</label></div>`
                        } else if (row.Estado == 4) {
                            var Estado =
                                '<div class="btn btn-success blink shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">APROBADO</div>'
                        } else {
                            var Estado =
                                '<div class="btn btn-info shadow" style="padding: 0.4rem 1.6rem; border-radius: 10%;font-weight: 600;font-size: 14px;"><label style="margin-bottom: 0px;">SOLICITUD DE COORDINACIÓN</div>'
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
                    data: null,
                    render: function(data, type, row) {
                        var dato =
                            `${row.ValidadoPor}`

                        return dato
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
                    data: null,
                    render: function(data, type, row) {
                        var dato =
                            `${row.FechaValidacion}`

                        return dato
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
                    data: null,
                    render: function(data, type, row) {
                        var dato =
                            `${row.ObservacionesGer}`

                        if(row.ObservacionesGer==null){
                            return 'Ninguna.'
                        }else{
                            return dato
                        }
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
                    data: null,
                    render: function(data, type, row) {
                        var dato =
                            `${row.FechaAprobacion}`

                        return dato
                    },
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).css({
                            'font-weight': '500',
                            'font-size': '20px',
                            'text-align': 'justify'
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
            responsive: "true",
                    dom: 'Bfrtilp',
                    buttons:[
                        {
                            extend:    'excelHtml5',
                            text:      '<i class="fas fa-file-excel"></i> ',
                            titleAttr: 'Exportar a Excel',
                            className: 'btn btn-success btn-lg'
                        }
                ],
            "initComplete": function(settings, json) {
                var buttonsHtml = '<div class="custom-buttons">' +
                    '<button id="btnT" class="custom-btn" title="ACTUALIZAR INFORMACIÓN"><i class="fa-solid fa-rotate-right"></i></button>' +
                    //   '<button id="btnFA" class="custom-btn" title="FALTA POR APROBAR">FA</button>' +
                    '</div>';
                $(buttonsHtml).prependTo('.dataTables_filter');
                $('#btnT').on('click', function() {
                    table.ajax.reload(null, false);

                });
            $('.filter-input').keyup(function() {
                table.column($(this).data('column')).search($(this).val()).draw();
            });

            $('.filter-select').change(function() {
                table.column($(this).data('column')).search($(this).val()).draw();
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
    <style>
            .texto-corto {
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
                max-width: 400px; /* Ajusta el ancho según necesites */
                display: inline-block;
                vertical-align: top;
            }

            .texto-completo {
                display: none;
            }

            .leer-mas {
                color: blue;
                cursor: pointer;
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
            margin-right: 10px;
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
