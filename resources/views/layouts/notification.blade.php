
    <a href="
    @if (auth()->user()->rol == 'Gerencia')
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
            padding: 1em 2em;
            transition: all 0.2s;
        }
    
        .buttonnotification::before {
            content: "{{ $notificaciones }}";
            position: absolute;
            display: flex;
            top: -0.75em;
            right: -0.75em;
            height: 1.8em;
            width: 1.8em;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            background-color: red;
            color: white;
            z-index: 10000; /* Asegura que la notificación también esté encima */
            transition: all 0.2s;
        }
    
        .buttonnotification:hover {
            border-radius: 3px;
            border-color: red;
        }
    
        .buttonnotification:hover::before {
            height: 2em;
            width: 2em;
            font-size: 1.5em;
        }
    </style>
