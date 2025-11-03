        <style>

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
                display: inline-block;
                margin-right: 10px;
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
                width: 10px;
                /*Ancho de la barra de desplazamiento */
            }

            .col::-webkit-scrollbar-track {
                background: #f1eeed;
                /*Color de fondo de la barra de desplazamiento */
            }

            .col::-webkit-scrollbar-thumb {
                background: #bea232;
                /*Color del botón de desplazamiento */
            }




            .text {
                color: #333;
                font-size: 18px;
                cursor: pointer;
            }

            .custom-btn {
                background-color: #646464;
                font-weight: bold;
                font-size: 21px;
                color: white;
                padding: 6px 12px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            .custom-btn:hover {
                background-color: #aeaeae;
            }

            .custom-btn2 {
                background-color: #168400;
                font-weight: bold;
                font-size: 21px;
                color: white;
                padding: 6px 16px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            .custom-btn2:hover {
                background-color: #1eb200;
            }
            .estado-container {
                max-height: calc(2 * 80px); /* Ajusta 40px si tus labels tienen otra altura */
                overflow-y: auto;
                overflow-x: hidden;
            }

            /* Opcional: para mejorar la apariencia del scroll */
            .estado-container::-webkit-scrollbar {
                width: 6px;
            }
            .estado-container::-webkit-scrollbar-track {
                background: #e1e1e1;
                border-radius: 3px;
            }
            .estado-container::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 3px;
            }

            .boton-numero {
            background-color: #646464;
            color: rgb(255, 255, 255);
            font-size: 40px;
            width: 100px;
            transition: background-color 0.3s ease;
            }

            .boton-numero:hover {
            background-color: #7a7a7a; /* Cambia a un color más claro en hover */
            }

            .boton-buscar {
            background-color: #646464;
            color: white;
            font-size: 40px;
            transition: background-color 0.3s ease;
            }

            .boton-buscar:hover {
            background-color: #7a7a7a; /* Cambia a un color más claro en hover */
            }


            .loader {
            width: fit-content;
            height: fit-content;
            display: flex;
            align-items: center;
            justify-content: center;
            }

            .truckWrapper {
            width: 420px;
            height: 100px;
            display: flex;
            flex-direction: column;
            position: relative;
            align-items: center;
            justify-content: flex-end;
            overflow-x: hidden;
            }
            /* truck upper body */
            .truckBody {
            width: 130px;
            height: fit-content;
            margin-bottom: 6px;
            animation: motion 1s linear infinite;
            }
            /* truck suspension animation*/
            @keyframes motion {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(3px);
            }
            100% {
                transform: translateY(0px);
            }
            }
            /* truck's tires */
            .truckTires {
            width: 130px;
            height: fit-content;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0px 10px 0px 15px;
            position: absolute;
            bottom: 0;
            }
            .truckTires svg {
            width: 24px;
            }

            .road {
            width: 100%;
            height: 1.5px;
            background-color: #282828;
            position: relative;
            bottom: 0;
            align-self: flex-end;
            border-radius: 3px;
            }
            .road::before {
            content: "";
            position: absolute;
            width: 20px;
            height: 100%;
            background-color: #282828;
            right: -50%;
            border-radius: 3px;
            animation: roadAnimation 1.4s linear infinite;
            border-left: 10px solid white;
            }
            .road::after {
            content: "";
            position: absolute;
            width: 10px;
            height: 100%;
            background-color: #282828;
            right: -65%;
            border-radius: 3px;
            animation: roadAnimation 1.4s linear infinite;
            border-left: 4px solid white;
            }

            .lampPost {
            position: absolute;
            bottom: 0;
            right: -0%;
            height: 90px;
            animation: roadAnimation 3.4s linear infinite;
            }

            @keyframes roadAnimation {
            0% {
                transform: translateX(0px);
            }
            100% {
                transform: translateX(-350px);
            }
            }


            /* Botón Estadísticas Premium */
            .btn-gradient-primary {
                background: linear-gradient(90deg, #ffc107, #ffca2c);
                color: #1f1f1f;
                border: none;
                padding: 0.65rem 1.2rem;
                font-size: 16px;
                border-radius: 10px;
                transition: all 0.3s ease-in-out;
                box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
            }
            .btn-gradient-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(255,193,7,0.6);
                background: linear-gradient(90deg, #ffca2c, #ffc107);
                color: #1f1f1f;
            }

            /* Tabla Premium */
            #personas tbody tr:hover {
                background: rgba(255, 193, 7, 0.1);
                transition: all 0.3s ease-in-out;
            }
            #personas th, #personas td {
                vertical-align: middle;
                text-align: center;
                font-size: 16px;
            }
            #personas thead tr {
                letter-spacing: 0.5px;
            }

            .modal-content {
                border-radius: 1rem;
                overflow: hidden;
                box-shadow: 0 10px 40px rgba(0,0,0,0.25);
                transition: all 0.3s ease-in-out;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            /* Cabecera */
            .modal-header {
                background: linear-gradient(90deg, #343a40, #495057);
                color: #ffc107;
                font-weight: 700;
                font-size: 22px;
                justify-content: space-between;
                align-items: center;
                padding: 1rem 2rem;
            }

            /* Botón cerrar premium */
            .modal-header .btn-close {
                background-color: #ffca2c;
                border-radius: 50%;
                width: 35px;
                height: 35px;
                transition: all 0.3s ease-in-out;
            }
            .modal-header .btn-close:hover {
                transform: scale(1.1);
                background-color: #ffc107;
            }

            /* Tabla interna y inputs */
            .input-group .form-control {
                border-radius: 0.5rem 0 0 0.5rem;
                box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
                transition: all 0.2s ease-in-out;
            }
            .input-group .form-control:focus {
                border-color: #ffc107;
                box-shadow: 0 0 5px rgba(255,193,7,0.5) inset;
            }

            /* Tooltips modernos */
            .tooltip-inner {
                background-color: #343a40;
                color: #ffc107;
                font-weight: 600;
                padding: 0.5rem 0.75rem;
            }

            /* Collapse hover effect */
            .hover-trigger:hover {
                background-color: rgba(255, 193, 7, 0.1);
                cursor: pointer;
                transition: all 0.3s ease-in-out;
            }


            @keyframes blink-animation {
                0%, 50%, 100% { opacity: 1; }
                25%, 75% { opacity: 0.4; }
            }

            /* Botón principal modal */
            .btn-primary-subtle {
                background: linear-gradient(135deg, #ffc107, #ffca2c);
                color: #1f1f1f;
                font-weight: 700;
                border-radius: 0.5rem;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            }
            .btn-primary-subtle:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(0,0,0,0.3);
            }

                .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    }

    .dropdown-menu li a:hover {
        background-color: #f0f0f0;
        color: #333;
    }

    /* Icono giratorio al hacer hover en actualizar */
    #btnT:hover i {
        transform: rotate(90deg);
        transition: transform 0.3s;
    }
        .btn-premium-action {
        background: linear-gradient(135deg, #007b5e 0%, #00a67a 100%);
        border: none !important;
        color: #fff !important;
        padding: 0.65rem 2.2rem;
        font-size: 15px;
        border-radius: 50px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        box-shadow: 0 4px 12px rgba(0, 167, 122, 0.35);
        transition: all .25s ease-in-out;
    }

    .btn-premium-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 167, 122, 0.45);
    }

    .btn-premium-action:active {
        transform: scale(.96);
    }

    .btn-premium-action i {
        font-size: 17px;
    }

    /* FORM CONTAINER PREMIUM */
.premium-form {
    background: #fff;
    border-radius: 25px;
    padding: 2.2rem;
    box-shadow: 0 6px 28px rgba(0,0,0,0.08);
}

/* TITLE */
.form-title {
    color: #005c4b;
    font-size: 1.8rem;
    font-weight: 800;
    text-transform: uppercase;
}

.form-description {
    font-size: .95rem;
    color: #6a6a6a;
    margin-top: -4px;
}

/* LABEL */
.premium-label {
    font-size: 1.1rem;
    font-weight: 700;
    color: #005c4b;
}

/* REQUIRED */
.required {
    color: #c70000;
    font-size: 20px;
}

/* SELECT */
.premium-select {
    border-radius: 14px;
    font-size: 1rem;
    font-weight: 600;
    padding: .75rem 1rem;
    border: 2px solid #e0e0e0;
    transition: .25s ease;
}

.premium-select:focus {
    border-color: #00a67a;
    box-shadow: 0 0 10px rgba(0,166,122,0.25);
}

/* TITULOS DE AGRUPADORES */
.group-title {
    font-weight: 900 !important;
    color: #005c4b !important;
    background-color: #e6f7f2 !important;
    font-size: .86rem;
}



        </style>
        