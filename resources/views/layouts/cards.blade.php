
   <div class="p-5">
        <section id="stats-subtitle">
            <div class="row">

                <span class="fs-2 fw-bold">Filtrar por:</span>
                <form action="d-inline" class="d-flex align-items-center" id="form-actualizar-datos">
                    <input class="input1 ms-3 mb-3 p-2 btn btn-outline-secondary text-dark fw-bold" style="width: 9em;" type="date" id="start" name="start" value="" min="2024-03-01" max="{{ now()->format('Y-m-d') }}" title="Mes Inicial"/>

                    <input class="input2 ms-3 mb-3 p-2 btn btn-outline-secondary text-dark fw-bold d-none" style="width: 9em;" type="date" id="end" name="end" value="" min="2024-03-01" max="{{ now()->format('Y-m-d') }}" title="Mes Final"/>

                    <div class="select ms-3 mb-3 d-none" id="agencia">
                        <select name="agencia"  class="fw-semibold">
                            <option selected disabled class="fw-bold" style="font-size: 19px">Agencia</option>
                            @foreach ($nombresAgencia as $agencia)
                                <option style="font-size: 19px" value="{{ $agencia->NomAgencia }}">{{ $agencia->NomAgencia }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                {{-- <div class="select ms-3 mb-3">
                    <select name="year" id="year" class="fw-semibold">
                        <option selected disabled class="fw-bold" style="font-size: 19px" value="">Año</option>
                        @foreach ($year as $anio)
                            <option style="font-size: 19px" value="{{ $anio->year }}">{{ $anio->year }}</option>
                        @endforeach
                    </select>
                </div> --}}


                <div class="col-12 mt-3 mb-1">
                    <h4 class="text-uppercase fw-bold fs-2 text-end"><button class="custom-btn me-2 fs-4" title="ACTUALIZAR ESTADÍSTICAS" onclick="reloadPage()"><i class="fa-solid fa-rotate-right"></i></button>Estadísticas GENERALES</h4>
                </div>
            </div>

            <div class="row mb-2 text-center">
                <a href="" class="text-decoration-none text-dark"><div class="col-xl-6 col-md-12">
                    <div class="card">
                      <div class="card-content">
                        <div class="card-body cleartfix">
                          <div class="media align-items-stretch">
                            <div class="align-self-center">
                              <i class="icon-speech warning font-large-2 mr-2"></i>
                            </div>
                            <div class="media-body">
                              <h3 class="fw-bold">🟡EN TRÁMITE🟡</h3>
                              <span class="fs-4 fst-italic">Directores, Coordinadores y Jefaturas</span>
                            </div>
                                <div class="text-center mt-3">
                                    <div id="porcentaje-tramite-container">
                                        <div style="display:inline;width:150px;height:150px;">
                                            <canvas width="0" height="150"></canvas>
                                            <input type="text" value="{{$porcentajetramite}}" id="porcentaje-tramite" class="knob hide-value responsive angle-offset" data-angleoffset="0" data-thickness=".15" data-linecap="round" data-width="150" data-height="150" data-inputcolor="black" data-readonly="true" data-fgcolor="#EBDC1E " readonly="readonly" style="width: 79px; height: 50px; position: absolute; vertical-align: middle; margin-top: 50px; margin-left: -114px; border: 0px; background: none; font: bold 30px Arial; text-align: center; color: rgb(225, 225, 225); padding: 0px; appearance: none; display: none;">
                                            <i class="knob-center-icon icon-note" style="width: 79px; height: 50px; position: absolute; vertical-align: middle; margin-top: 50px; margin-left: -114px; border: 0px; background: none; font: normal 30px Arial; text-align: center; color: rgb(225, 225, 225); padding: 0px; appearance: none;font-size: 50px;">
                                                <h1 class="fw-bold value text-dark fs-3" akhi="{{$tramite}}" id="tramite">0</h1>
                                            </i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div></a>
                </div>
                <div class="col-xl-6 col-md-12">
                    <a href="" class="text-decoration-none text-dark"><div class="card overflow-hidden">
                    <div class="card-content">
                        <div class="card-body cleartfix">
                        <div class="media align-items-stretch">
                            <div class="align-self-center">
                            <i class="icon-pencil primary font-large-2 mr-2"></i>
                            </div>
                            <div class="media-body">
                            <h3 class="fw-bold">🟢VALIDADOS🟢</h3>
                            <span class="fs-4 fst-italic">por Coordinaciones 1,2,3,4,5,9</span>
                            </div>
                            <div class="text-center mt-3">
                                <div style="display:inline;width:150px;height:150px;"><canvas width="0" height="150"></canvas><input type="text" value="{{$decimalvalidados}}" class="knob hide-value responsive angle-offset" data-angleoffset="0" data-thickness=".15" data-linecap="round" data-width="150" data-height="150" data-inputcolor="black" data-readonly="true" data-fgcolor="#008000" readonly="readonly" style="width: 79px; height: 50px; position: absolute; vertical-align: middle; margin-top: 50px; margin-left: -114px; border: 0px; background: none; font: bold 30px Arial; text-align: center; color: rgb(225, 225, 225); padding: 0px; appearance: none; display: none;"><i class="knob-center-icon icon-note" style="width: 79px; height: 50px; position: absolute; vertical-align: middle; margin-top: 50px; margin-left: -114px; border: 0px; background: none; font: normal 30px Arial; text-align: center; color: rgb(225, 225, 225); padding: 0px; appearance: none;font-size: 50px;"><h1 class="fw-bold value text-dark fs-3" akhi="{{$validadocoordinadores}}" id="validados">0</h1></i></div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div></a>
                </div>
            </div>

            <div class="row text-center">
                <div class="col-xl-6 col-md-12">
                    <a href="" class="text-decoration-none text-dark"><div class="card overflow-hidden">
                    <div class="card-content">
                        <div class="card-body cleartfix">
                        <div class="media align-items-stretch">
                            <div class="align-self-center">
                            <i class="icon-pencil primary font-large-2 mr-2"></i>
                            </div>
                            <div class="media-body">
                            <h3 class="fw-bold">🔴RECHAZADOS🔴</h3>
                            <span class="fs-4 fst-italic">por Coordinación 1,2,3,4,5,9 y GERENCIA</span>
                            </div>
                            <div class="text-center mt-3">
                                <div style="display:inline;width:150px;height:150px;"><canvas width="0" height="150"></canvas><input type="text" value="{{$decimalrechazados}}" class="knob hide-value responsive angle-offset" data-angleoffset="0" data-thickness=".15" data-linecap="round" data-width="150" data-height="150" data-inputcolor="black" data-readonly="true" data-fgcolor="#FF0000" readonly="readonly" style="width: 79px; height: 50px; position: absolute; vertical-align: middle; margin-top: 50px; margin-left: -114px; border: 0px; background: none; font: bold 30px Arial; text-align: center; color: rgb(225, 225, 225); padding: 0px; appearance: none; display: none;"><i class="knob-center-icon icon-note" style="width: 79px; height: 50px; position: absolute; vertical-align: middle; margin-top: 50px; margin-left: -114px; border: 0px; background: none; font: normal 30px Arial; text-align: center; color: rgb(225, 225, 225); padding: 0px; appearance: none;font-size: 50px;"><h1 class="fw-bold value text-dark fs-3" akhi="{{$rechazados}}" id="rechazados">0</h1></i></div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div></a>
                </div>

                <div class="col-xl-6 col-md-12">
                    <a href="" class="text-decoration-none text-dark"><div class="card">
                    <div class="card-content">
                        <div class="card-body cleartfix">
                        <div class="media align-items-stretch">
                            <div class="align-self-center">
                            <i class="icon-speech warning font-large-2 mr-2"></i>
                            </div>
                            <div class="media-body">
                            <h3 class="fw-bold">🟢APROBADOS🟢</h3>
                            <span class="fs-4 fst-italic">por GERENCIA</span>
                            </div>
                            <div class="text-center mt-3">
                                <div style="display:inline;width:150px;height:150px;"><canvas width="0" height="150"></canvas><input type="text" value="{{$decimalaprobados}}" class="knob hide-value responsive angle-offset" data-angleoffset="0" data-thickness=".15" data-linecap="round" data-width="150" data-height="150" data-inputcolor="black" data-readonly="true" data-fgcolor="#008000" readonly="readonly" style="width: 79px; height: 50px; position: absolute; vertical-align: middle; margin-top: 50px; margin-left: -114px; border: 0px; background: none; font: bold 30px Arial; text-align: center; color: rgb(225, 225, 225); padding: 0px; appearance: none; display: none;"><i class="knob-center-icon icon-note" style="width: 79px; height: 50px; position: absolute; vertical-align: middle; margin-top: 50px; margin-left: -114px; border: 0px; background: none; font: normal 30px Arial; text-align: center; color: rgb(225, 225, 225); padding: 0px; appearance: none;font-size: 50px;"><h1 class="fw-bold value text-dark fs-3" akhi="{{$aprobadogerencia}}" id="aprobados">0</h1></i></div>
                            </div>
                        </div>
                        </div>
                    </div></a>
                    </div>

                </div>
            </div>
        </section>
        <p class="text-center mt-3 fw-bold fs-2">
            TOTAL: <span class="btn btn-secondary shadow value " style="padding: 0rem 0.8rem; border-radius: 10%; font-weight: 600; font-size: 25px;" akhi="{{$total}}" id="total"><b>0</b></span>
        </p>
    </div>

    <script src="ResourcesAll/cards/vendors.min.js.descarga"></script>
    <script src="ResourcesAll/cards/jquery.knob.min.js.descarga"></script>
    <script src="ResourcesAll/cards/card-statistics.min.js.descarga"></script>
    <script>
        const counters = document.querySelectorAll('.value');
        const speed = 300;

        counters.forEach( counter => {
        const animate = () => {
            const value = +counter.getAttribute('akhi');
            const data = +counter.innerText;

            const time = value / speed;
            if(data < value) {
                counter.innerText = Math.ceil(data + time);
                setTimeout(animate, 1);
                }else{
                counter.innerText = value;
                }

        }

            animate();
        });

        $(document).ready(function() {
            $('#start, #end, #agencia').on('change', function() {
                var formData = $('#form-actualizar-datos').serialize();
                $.ajax({
                    type: 'GET',
                    url: '{{ route("actualizardatos") }}',
                    data: formData,

                    success: function(response) {
                        console.log(response.porcentaje_tramite)
                        $('#tramite').text(response.tramite);
                        $('#porcentaje-tramite').val(response.porcentaje_tramite);
                        $('#validados').text(response.validadocoordinadores);
                        $('#rechazados').text(response.rechazados);
                        $('#aprobados').text(response.aprobadogerencia);
                        $('#aprobados').text(response.aprobadogerencia);
                        $('#total').text(response.total);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });


        $(document).ready(function(){
            $("#start").change(function(){
                $("#end").removeClass("d-none");
            });

            $("#end").change(function(){
                $("#agencia").removeClass("d-none");
            });
        });


        function csesion() {
            var respuesta = confirm("¿Estas seguro que deseas cerrar sesión?")
            return respuesta
        }

        function reloadPage() {
            location.reload();
        }


    </script>


    <style>
            select {
                -webkit-appearance:none;
                -moz-appearance:none;
                -ms-appearance:none;
                appearance:none;
                outline:0;
                box-shadow:none;
                border:0!important;
                background: #646464;
                background-image: none;
                flex: 1;
                padding: 0 .5em;
                color:#fff;
                cursor:pointer;
                font-size: 1em;
                font-family: 'Open Sans', sans-serif;
            }
            select::-ms-expand {
                display: none;
            }
            .select {
                position: relative;
                display: flex;
                width: 12em;
                height: 3em;
                line-height: 3;
                background: #646464;
                overflow: hidden;
                border-radius: .25em;
            }
            .select::after {
                content: '\25BC';
                position: absolute;
                top: 0;
                right: 0;
                padding: 0 1em;
                background: #7d8080;
                cursor:pointer;
                pointer-events:none;
                transition:.25s all ease;
            }
            .select:hover::after {
                color: #ffffff;
            }

            .custom-buttons {
                display: inline;
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
    </style>


