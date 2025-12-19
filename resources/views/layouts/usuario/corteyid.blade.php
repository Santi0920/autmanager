<div class="caja-cortes tooltip-custom" data-tooltip="LAS SOLICITUDES O REPORTES QUE SE ENCUENTREN DESPUES DEL CORTE DEL MES ACTUAL SERAN ASIGNADAS COMO VENCIDAS!">
    
    <!-- Corte Anterior -->
    <div class="item-corte">
        <div class="icono">📅</div>
        <div class="info">
            <div class="titulo">Corte Anterior:</div>
            <div class="valor">{{ $corteMesAnterior }}</div>
            <div class="sub fw-bold">Último ID: {{ $ultimoConsecutivoMesAnterior }}</div>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Corte Actual -->
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
    /* ===========================
    CONTENEDOR GENERAL
    =========================== */
    .caja-cortes {
        background: #ffffff;
        border-radius: 14px;
        padding: 10px 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border: 1px solid #eef2f3;

        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;

        max-width: 540px;
        margin: 5px 0;

        flex-wrap: nowrap; /* 🖥️ escritorio en una sola línea */
    }

    /* ===========================
    ITEM
    =========================== */
    .item-corte {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 230px;
    }

    /* ===========================
    ICONO
    =========================== */
    .icono {
        font-size: 26px;
        background: #f5f7fa;
        padding: 10px;
        border-radius: 10px;
    }

    /* ===========================
    TEXTOS
    =========================== */
    .info {
        text-align: left;
    }

    .titulo {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
    }

    .valor {
        font-size: 22px;
        font-weight: 800;
        color: #1f2e3d;
    }

    .sub {
        font-size: 20px;
        font-weight: 700;
        color: #ff0000;
    }

    /* ===========================
    DIVIDER
    =========================== */
    .divider {
        width: 2px;
        height: 48px;
        background: #e9ecef;
        flex-shrink: 0;
    }

    /* ===========================
    📱 MÓVIL RESPONSIVE
    =========================== */
    @media (max-width: 600px) {

        .caja-cortes {
            flex-direction: column; /* ⬇️ pasa a la siguiente línea */
            align-items: stretch;
            text-align: center;
            gap: 12px;
        }

        .item-corte {
            width: 100%;
            flex-direction: column;
            align-items: center;
        }

        .info {
            text-align: center;
        }

        .divider {
            width: 100%;
            height: 1.5px;
            margin: 6px 0;
        }

        .titulo { font-size: 17px; }
        .valor  { font-size: 20px; }
        .sub    { font-size: 19px; }
    }

    /* ===========================
    TOOLTIP
    =========================== */
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
        max-width: 260px;
        padding: 10px 14px;
        background: rgba(0, 0, 0, 0.85);
        color: #fff;
        font-size: 0.85rem;
        border-radius: 8px;
        opacity: 0;
        pointer-events: none;
        transition: 0.25s ease;
        text-align: center;
    }

    .tooltip-custom:hover::after {
        opacity: 1;
        transform: translateX(-50%) translateY(-5px);
    }
</style>
