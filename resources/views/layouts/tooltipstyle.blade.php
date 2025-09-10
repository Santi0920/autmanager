<style>
            .tooltip-container {
                position: relative;
                display: inline-block;
                margin: 0px;
            }
            /* Tooltip base */
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

                width: 250px;
                min-width: 220px;
                max-width: 90vw;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                word-wrap: break-word;
            }

            /* Flecha */
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

            /* Mostrar tooltip */
            .tooltip-container:hover .tooltip {
                top: 120%;
                opacity: 1;
                visibility: visible;
                background: #898989;
                transform: translate(-50%, 0px);
            }



</style>