<div class="caja-cortes tooltip-custom" data-tooltip="LAS SOLICITUDES O REPORTES QUE SE ENCUENTREN DESPUES DEL CORTE DEL MES ACTUAL SERAN ASIGNADAS COMO VENCIDAS!">
    <!-- Corte Anterior -->
    <div class="item-corte">
        <div class="icono">📅</div>

        <div class="info">
            <div class="titulo">
                Corte Anterior:
            </div>
            <div class="valor">{{ $corteMesAnterior }}</div>
            <div class="sub fw-bold">Último ID: {{ $ultimoConsecutivoMesAnterior }}</div>
        </div>
    </div>

    <div class="divider"></div>


    <div class="item-corte">
        <div class="icono">⬇️</div>

        <div class="info">
            <div class="titulo">Corte Actual:</div>
            <div class="valor">{{ $fechaCorteActualTexto }}</div>
            <div class="sub fw-bold">Último ID: {{ $ultimoConsecutivoMesActual }}</div>
        </div>
    </div>

</div>

<style>
    /* CONTENEDOR GENERAL */
    .caja-cortes {
        background: #ffffff;
        border-radius: 14px;
        padding: 5px 5px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        display: flex;
        align-items: center;

        justify-content: flex-start; 

        gap: 28px;
        max-width: 540px;
        flex-wrap: wrap;
        margin: 5px 0;               
        border: 1px solid #eef2f3;
    }

    /* ITEM */
    .item-corte {
        display: flex;
        align-items: center;
        gap: 12px;                      
        min-width: 220px;               
    }

    /* ICONOS */
    .icono {
        font-size: 26px;              
        background: #f5f7fa;
        padding: 8px;                  
        border-radius: 10px;
    }

    /* TEXTOS */
    .info {
        text-align: left;
    }

    .titulo {
        font-size: 18px;                 
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0;
    }

    .valor {
        font-size: 23px;             
        font-weight: 800;
        color: #1f2e3d;
    }

    .sub {
        font-size: 25px;                 
        color: #ff0000;
        margin-top: 0;
    }

    /* DIVIDER */
    .divider {
        width: 2px;
        height: 45px;                  
        background: #e9ecef;
    }

    /* ---------------------------
        📱 MÓVIL ULTRA-OPTIMIZADO
    ----------------------------*/
    @media (max-width: 600px) {

    .caja-cortes {
        padding: 14px 16px;       
        gap: 20px;
        border-radius: 12px;
        max-width: 94%;
        margin: 0 auto; /* centra la caja horizontalmente */
        text-align: center; /* centra el contenido de texto */
    }

    .item-corte {
        gap: 12px;
        width: 100%;
        display: flex;
        flex-direction: column; /* apila los elementos verticalmente */
        align-items: center; /* centra los elementos hijos */
    }

    .icono {
        font-size: 26px; /* un poco más grande */
        padding: 8px;
    }

    .titulo {
        font-size: 18px; /* más grande */
    }

    .valor {
        font-size: 19px; /* más grande */
    }

    .sub {
        font-size: 22px; /* más grande */
    }

    /* Divider horizontal */
    .divider {
        width: 100%;
        height: 1px;
        margin: 4px 0;
    }
   

    .tooltip-custom {
        position: relative;
        cursor: help;
    }

    .tooltip-custom::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 110%;
        left: 50%;
        transform: translateX(-50%);
        width: max-content;
        max-width: 260px;
        padding: 10px 14px;
        background: rgba(0, 0, 0, 0.85);
        color: #fff;
        font-size: 0.85rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease, transform 0.25s ease;
        transform-origin: bottom;
        transform: translateX(-50%) translateY(5px);
        z-index: 999;
    }

    .tooltip-custom:hover::after {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }



</style>
