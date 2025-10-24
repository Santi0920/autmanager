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


        </style>