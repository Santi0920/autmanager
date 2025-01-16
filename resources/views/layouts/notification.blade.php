
    <a href="
    @if (session('rol') == 'Gerencia')
    otrabajo
    @else
    ordentrabajo
    @endif

    ">
        <button class="buttonnotification fw-bold fs-5">
            <i class="fa-solid fa-bell"></i>&nbsp&nbspOrden de Trabajo
        </button>
    </a>

    <style>
    .buttonnotification {
        position: fixed;
        top: 52rem;
        right: 1rem;
        z-index: 9999;
        margin: 1rem;
        border-radius: 8px;
        appearance: none;
        padding: 0.8em 1.5em;
        font-size: 1em;
        transition: all 0.2s;
    }

    .buttonnotification::before {
        content: "{{ $notificaciones }}";
        position: absolute;
        display: flex;
        top: -0.75em;
        right: -0.75em;
        height: 1.5em;
        width: 1.5em;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        background-color: red;
        color: white;
        font-size: 0.8em;
        transition: all 0.2s;
    }

    .buttonnotification:hover {
        border-radius: 3px;
        border-color: red;
    }

    .buttonnotification:hover::before {
        height: 2em;
        width: 2em;
        font-size: 1.2em;
    }

    /* Media query para pantallas medianas */
    @media (max-width: 1900px) {
        .buttonnotification {
            top: 47rem;
            right: 1rem;
            padding: 0.7em 1.2em;
            font-size: 0.9em;
        }
        .buttonnotification::before {
            height: 1.3em;
            width: 1.3em;
            font-size: 0.75em;
        }
    }

    @media (max-width: 1600px) {
        .buttonnotification {
            top: 40rem;
            right: 1rem;
            padding: 0.7em 1.2em;
            font-size: 0.9em;
        }
        .buttonnotification::before {
            height: 1.3em;
            width: 1.3em;
            font-size: 0.75em;
        }
    }

    /* Media query para pantallas pequeñas */
    @media (max-width: 576px) {
        .buttonnotification {
            top: auto;
            bottom: 2rem;
            right: 1rem;
            padding: 0.6em 1em;
            font-size: 0.8em;
        }
        .buttonnotification::before {
            height: 1.2em;
            width: 1.2em;
            font-size: 0.7em;
            top: -0.6em;
            right: -0.6em;
        }
    }
    </style>
