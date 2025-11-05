    <!-- Estilos Premium -->
    <style>
        /* Estilo general de los tabs */
        .nav-tabs .nav-link {
            font-weight: 600;
            color: #e0e0e0;
            background-color: #2c2c2c;
            border: none;
            margin-right: 5px;
            border-radius: 10px 10px 0 0;
            transition: all 0.3s ease-in-out;
        }

        /* Hover suave */
        .nav-tabs .nav-link:hover {
            color: #ffc107;
            background-color: #3a3a3a;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        /* Tab activo destacado */
        .nav-tabs .nav-link.active {
            color: #1f1f1f;
            background: linear-gradient(90deg, #ffc107, #ffca2c);
            border-radius: 10px 10px 0 0;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(255,193,7,0.4);
        }

        /* Foco al hacer click */
        .nav-tabs .nav-link:focus {
            box-shadow: 0 0 0 3px rgba(255,193,7,0.5);
        }

        /* Separación entre tabs */
        .nav-tabs .nav-item {
            margin-right: 5px;
        }

        /* Transición suave para todo */
        .nav-tabs .nav-link, .nav-tabs .nav-link.active {
            transition: all 0.3s ease-in-out;
        }
        .agencias-scroll {
            display: flex;
            gap: 6px; /* espacio entre badges */
            max-height: 7.5em; /* altura aproximada de dos registros */
            overflow-y: auto; /* scroll vertical si hay más de dos registros */
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background-color: #fafafa;
        }

        .badge-agencia {
            display: inline-block;
            padding: 10px 10px;
            background-color: #e0e0e0;
            border-radius: 5px;
            font-size: 0.9em;
            white-space: nowrap; /* que no se rompa en varias líneas */
        }

        /* Hover moderno para opciones */
        .hover-option {
            transition: all 0.3s ease-in-out;
            border-radius: 0.35rem;
        }

        .hover-option:hover {
            background: rgba(255, 193, 7, 0.15);
            color: #ffc107 !important;
            transform: translateX(5px);
        }

        /* Separadores más suaves */
        .dropdown-divider {
            border-color: rgba(255,255,255,0.1);
        }

        /* Animación dropdown */
        .dropdown-menu {
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease-in-out;
        }

        .show.dropdown-menu {
            opacity: 1;
            transform: translateY(0);
        }

        /* Iconos */
        .dropdown-item i {
            width: 20px;
            text-align: center;
        }
        
        .navbar-nav .nav-link {
            font-size: 1rem;
            transition: all 0.3s ease-in-out;
        }

        .navbar-nav .nav-link:hover {
            color: #ffc107 !important;
            text-shadow: 0 0 5px rgba(255,193,7,0.7);
        }

        .btn-warning {
            transition: all 0.3s ease-in-out;
        }

        .btn-warning:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(255,193,7,0.6);
        }

        .btn-light:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(255,255,255,0.6);
        }

        .toggle-password {
            font-size: 1.1rem;
            color: #6c757d;
            user-select: none;
        }
        .toggle-password:hover {
            color: #495057;
        }
        .btn-version-floating {
            position: fixed;
            bottom: 22px;
            right: 22px;
            background: #0d6efd;
            color: #fff;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 50px;
            border: none;
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.45);
            cursor: pointer;
            transition: all 0.25s ease-in-out;
            z-index: 2000;
        }

        .btn-version-floating:hover {
            background: #084298;
            transform: translateY(-3px) scale(1.04);
            box-shadow: 0 10px 26px rgba(13, 110, 253, 0.65);
        }

        .btn-version-floating:focus {
            outline: none !important;
        }

        /* Badge “Nuevo” */
        .badge-new {
            background: #ffce00;
            color: #000;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 12px;
            animation: glowPulse 1.5s infinite;
        }

        @keyframes glowPulse {
            0% { box-shadow: 0 0 6px rgba(255, 206, 0, 0.6); }
            50% { box-shadow: 0 0 12px rgba(255, 206, 0, 1); }
            100% { box-shadow: 0 0 6px rgba(255, 206, 0, 0.6); }
        }

        /* Sombra extra para modal */
        .shadow-xl {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25) !important;
        }

        /* Botón de cierre moderno */
        .btn-close-white {
            filter: invert(1);
        }

        /* Botón de footer */
        .btn-gradient {
            background: linear-gradient(135deg, #525252ff, #444444ff);
            color: #fff;
            font-weight: 600;
            border-radius: 50px;
            border: none;
            transition: all 0.25s ease-in-out;
            box-shadow: 0 6px 14px rgba(83, 83, 83, 0.6);
        }

        .btn-gradient:hover {
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 8px 20px rgba(119, 119, 119, 0.75);
            color: #fff;
        }

        /* Lista numerada profesional */
        .list-group-numbered .list-group-item {
            background: #fff;
            border: none;
            font-size: 1.05rem;
            color: #2c3e50;
            font-weight: bold;
        }

        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220,53,69, 0.7); }
            70% { transform: scale(1.1); box-shadow: 0 0 0 15px rgba(220,53,69, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220,53,69, 0); }
        }
        button.btn-danger:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px #ff4d4d, 0 0 30px #ff1a1a, 0 0 40px #ff0000;
        }
        .btn-error-nav {
            background: linear-gradient(135deg, #ff4d4d, #b30000);
            color: #fff;
            border: none;
            padding: 9px 20px;
            font-size: 15px;
            font-weight: bold;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            box-shadow:             box-shadow: 
                0 0 15px rgba(255, 77, 77, 0.7),
                0 0 25px rgba(255, 26, 26, 0.5),
                0 0 35px rgba(255, 0, 0, 0.4);
        }

        
        .btn-version-nav {
            background: linear-gradient(135deg, #8e2de2, #4a00e0);
            color: #fff;
            border: none;
            padding: 8px 20px;
            font-size: 15px;
            font-weight: bold;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 14px rgba(138, 43, 226, 0.6);
        }

        .btn-version-nav:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 20px rgba(138, 43, 226, 0.9);
        }

        @media (max-width: 576px) {
            .btn-version-nav {
                padding: 6px 12px;
                font-size: 13px;
                gap: 4px;
            }

            .btn-version-nav strong {
                display: none; /* Oculta "2.0" si quieres simplificar */
            }

            .badge-new {
                font-size: 12px;
                padding: 2px 6px;
            }
        }

        /* Responsive: pantallas medianas */
        @media (max-width: 768px) {
            .btn-version-nav {
                padding: 7px 16px;
                font-size: 14px;
                gap: 5px;
            }

            .badge-new {
                font-size: 13px;
                padding: 3px 8px;
            }
        }

        @keyframes glow-red {
            0% {
                box-shadow:
                    0 0 10px rgba(255, 77, 77, 0.6),
                    0 0 20px rgba(255, 26, 26, 0.5),
                    0 0 30px rgba(255, 0, 0, 0.4);
            }
            100% {
                box-shadow:
                    0 0 20px rgba(255, 77, 77, 1),
                    0 0 35px rgba(255, 26, 26, 1),
                    0 0 50px rgba(255, 0, 0, 1);
            }
        }

        /* Hover */
        .btn-error-nav:hover {
            transform: scale(1.06);
            filter: brightness(1.15);
        }

        /* ✅ Responsive */
        @media (max-width: 480px) {
            .btn-error-nav {
                width: 160px;
                height: 60px;
                font-size: 14px;
            }
        }

    </style>

<div class="navbarBgDark" style="background-color: #646464;">
            <!-- Navbar Ultra Premium -->
            <nav class="navbar navbar-expand-lg navbar-dark p-3 shadow-sm" style="background: linear-gradient(90deg, #343a40, #495057);">
                    <div class="container-fluid">

            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center position-relative" href="#">
                <img src="img/CoopserpPH.png" alt="Coopserp Logo" width="182" height="60"
                    style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));">

                <!-- Botón versión -->
                <button class="btn-version-nav ms-3 me-2" data-bs-toggle="modal" data-bs-target="#versionModal">
                    🚀 Versión <strong>2.0</strong>
                    <span class="badge-new">🛑 Nuevo</span>
                </button>


                <button class="btn-error-nav shadow-lg"
                        data-bs-toggle="modal"
                        data-bs-target="#bugReportModal">
                    🐞 Reportar Error
                </button>

            </a>

            {{-- modal version --}}
            <div class="modal fade" id="versionModal" tabindex="-1" aria-labelledby="versionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                    <div class="modal-content shadow-xl rounded-4 border-0 overflow-hidden ">

                        <!-- Header con gradiente y icono -->
                        <div class="modal-header p-4" style="background: linear-gradient(90deg, #343a40, #495057);">
                            <h5 class="modal-title text-white fw-bold fs-5 d-flex align-items-center">
                                <i class="fa-solid fa-rocket me-2" style="font-size:1.4rem;"></i>
                                    Actualización del Software — Versión 2.0 <span class="badge-new ms-3">🛑 Nuevo</span>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <!-- Body con fondo suave y lista numerada moderna -->
                        <div class="modal-body p-4" style="background: #f7f9fc;">
                            <p class="mb-4 text-dark fw-semibold fs-6">
                                ✅ Mejoras y Cambios implementados en esta actualización (04 Noviembre 2025 - 8:00 AM):
                            </p>

                            <ol class="list-group list-group-numbered mb-4" style="border: none;">
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    Botón para reportar errores para ser solucionados lo mas pronto posible. Parte inferior derecha. <span class="badge-new">🔥USARLO🔥</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    Se añadió el estado Stand By, permitiendo que Dirección General pueda aprobar directamente todas las solicitudes que se encuentren en este estado, agilizando la gestión.
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    Al cargar las solicitudes, el sistema ahora muestra aquellas en estado Rechazado o Bloqueado, priorizando su revisión y garantizando que se atiendan los casos más sensibles.
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    Los campos de selección de conceptos y áreas se actualizan automáticamente cuando se crean nuevos registros, asegurando que siempre aparezcan en los formularios de solicitud sin intervención manual. <span class="badge-new">🛑 Nuevo</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    En los perfiles de Dirección General, Coordinadores, Jefaturas y Dirección de Agencia se añadió la funcionalidad de collapse, permitiendo ocultar o mostrar información extensa y reduciendo el scroll excesivo. <span class="badge-new">🛑 Nuevo</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    Los estados de Gerencia (Aprobar, Rechazar, Bloquear y Stand By) y los de Coordinadores (Validar y Rechazar) se adaptan automáticamente a móviles, garantizando consistencia, alineación y usabilidad en todos los dispositivos.
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    Se renovó la interfaz de login con un diseño moderno y amigable, incluyendo un botón de visualización de contraseña (icono de ojo) que permite alternar entre mostrar u ocultar los caracteres. <span class="badge-new">🛑 Nuevo</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    Se implementó Google reCAPTCHA, que se activa automáticamente tras 3 intentos fallidos, previniendo ataques automatizados y de fuerza bruta sin afectar la experiencia del usuario.<span class="badge-new">🛑 Nuevo</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    Se mejoró la interfaz de Director de Agencia, Coordinación y Dirección General: tablas, encabezados, botones y modales, agregando páginas de Términos y Condiciones y Política de Privacidad según estándares ISO y buenas prácticas de seguridad.<span class="badge-new">🛑 Nuevo</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    Todos los perfiles pueden filtrar autorizaciones desde “Buscar autorización” (Tabla), con alcance según rol: Dirección General (nacional), Coordinación (agencias asociadas) y Jefatura/Dirección de Agencia (solicitudes propias).<span class="badge-new">🛑 Nuevo</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    Las solicitudes tipo “Reporte de novedades” se destacan visualmente en el modal, facilitando su clasificación y revisión rápida por parte de los usuarios.<span class="badge-new">🛑 Nuevo</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    Para solicitudes tipo reporte se habilitó el estado Enterado, sustituyendo el anterior Aprobado cuando no corresponde validación formal, mejorando la coherencia del flujo de autorizaciones.<span class="badge-new">🛑 Nuevo</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    El nombre del funcionario es clickeable y abre un modal con información relevante: foto, nombre, rol, agencia/área, código del centro de costo, email y teléfono.<span class="badge-new">🛑 Nuevo</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    Para Directores de Agencia se muestra la Coordinación asignada, y para Coordinadores se listan las agencias vinculadas a su cargo, proporcionando contexto completo.<span class="badge-new">🛑 Nuevo</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    El modal de usuario muestra último acceso, última acción registrada, sesiones activas y los últimos 3 inicios de sesión, fortaleciendo seguridad y trazabilidad operativa.<span class="badge-new">🛑 Nuevo</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    Todos los usuarios pueden cambiar su contraseña cumpliendo políticas de seguridad estrictas, evitando accesos no autorizados o contraseñas vulnerables.<span class="badge-new">🛑 Nuevo</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    El usuario puede actualizar su nombre y número de celular, asegurando que los datos de contacto se mantengan correctos para notificaciones internas.<span class="badge-new">🛑 Nuevo</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    La base de datos y software fueron rediseñados para almacenar versiones de cada modificación en el ciclo de vida de una autorización, garantizando trazabilidad, control de cambios e integridad de la información.<span class="badge-new">🛑 Nuevo</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center mb-2 shadow-sm rounded-3">
                                    Ante un rechazo, los documentos corregidos generan una nueva versión, manteniendo el historial completo de archivos asociados a la solicitud<span class="badge-new">🛑 Nuevo</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center shadow-sm rounded-3">
                                    Dirección General puede derivar una solicitud a otro funcionario, quien podrá marcarla como Recibida, registrando formalmente que tomó conocimiento de la solicitud.<span class="badge-new">🛑 Nuevo</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center shadow-sm rounded-3">
                                    Sección para filtrar autorizaciones antiguas estará temporalmente ACTIVO.<span class="badge-new">🟢TEMPORAL</span>
                                </li>
                            </ol>
                        </div>

                        <!-- Footer elegante -->
                        <div class="modal-footer border-0 p-4">
                            
                            <div class="alert alert-info border-0 shadow-sm rounded-3 mt-3">
                                📅 Fecha de publicación: <strong>04 Nov 2025</strong>
                            </div>
                            <button type="button" class="btn btn-gradient px-4 py-2" data-bs-dismiss="modal">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Modal -->
            <div class="modal fade" id="bugReportModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                    <form action="{{ route('bug-report.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                    @csrf
                        <div class="modal-header" style="background: linear-gradient(90deg, #343a40, #495057);">
                            <h5 class="modal-title fw-bold text-light fs-4">Reportar Error</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body fs-5 fw-bold">
                            <div class="mb-3">
                            <label>Título del BUG:</label>
                            <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                            <label>Descripción:</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="mb-3">
                            <label>Imagen (opcional):</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary fs-5">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Botón hamburguesa -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú -->
            <div class="collapse navbar-collapse justify-content-between" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto align-items-center">

                    <!-- Bienvenida usuario -->
                    <li class="nav-item me-3">
                        <a class="nav-link d-flex align-items-center text-light fw-bold" href="#" data-bs-toggle="modal" data-bs-target="#userInfoModal">
                            Bienvenido: 
                            <span class="btn btn-warning ms-2 shadow fw-bold px-3 py-2 rounded-pill">
                                {{ $usuario['name'] }} -                           
                                @if(session('agenciau') == 'Gerencia General')
                                    Cali
                                @else
                                    {{ session('agenciau') }}
                                @endif
                            </span>
                        </a>
                    </li>


                    <!-- Dropdown Opciones Ultra Premium -->
                    <li class="nav-item dropdown me-3 position-relative">
                        <a class="nav-link dropdown-toggle fw-bold text-light d-flex align-items-center" href="#" id="navbarDropdownOptions" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            OPCIONES

                        </a>
                        
                        <ul class="dropdown-menu dropdown-menu-dark shadow-lg rounded-3 animate__animated animate__fadeInDown" aria-labelledby="navbarDropdownOptions" style="min-width: 220px; border: 1px solid rgba(255,255,255,0.1);">
                            
                            <!-- Sección Consultante / Jefatura / Coordinacion -->
                            @if(session('rol') == 'Consultante' || session('rol') == 'Jefatura' || session('rol') == 'Coordinacion')
                                <li>
                                    <a class="dropdown-item d-flex align-items-center fw-semibold hover-option" href="solicitudes">
                                        <i class="fas fa-file-alt me-2"></i> Solicitar Autorización
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center fw-semibold hover-option" href="filtrar">
                                        <i class="fas fa-search me-2"></i> Buscar Autorización
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center fw-semibold hover-option" href="ordentrabajo">
                                        <i class="fas fa-tasks me-2"></i> Orden de Trabajo
                                    </a>
                                </li>
                            @elseif(session('rol') == 'Gerencia')
                                <li>
                                    <a class="dropdown-item d-flex align-items-center fw-semibold hover-option" href="solicitudes">
                                        <i class="fas fa-briefcase me-2"></i> Gerencia
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center fw-semibold hover-option" href="filtrar">
                                        <i class="fas fa-search me-2"></i> Buscar Autorización
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center fw-semibold hover-option" href="gerencia">
                                        <i class="fas fa-search me-2"></i> Gerencia antes de 4 Nov
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center fw-semibold hover-option" href="gerenciac9">
                                        <i class="fas fa-search me-2"></i> Coordinación 9 antes de 4 Nov
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center fw-semibold hover-option" href="otrabajo">
                                        <i class="fas fa-tasks me-2"></i> Orden de Trabajo
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center fw-semibold hover-option" href="admin">
                                        <i class="fas fa-cogs me-2"></i> Panel Administrativo
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>

                    <!-- Botón Cerrar Sesión -->
                    <li class="nav-item">
                        <button id="logoutBtn" class="btn btn-light fw-bold px-4 py-2 shadow rounded-pill">
                            Cerrar Sesión
                        </button>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- SweetAlert2 Logout -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('logoutBtn').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Vas a cerrar sesión en tu cuenta.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                background: '#f8f9fa',
                color: '#343a40',
                showClass: { popup: 'animate__animated animate__fadeInDown' },
                hideClass: { popup: 'animate__animated animate__fadeOutUp' }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login.destroy') }}";
                }
            });
        });
    </script>

    <!-- Animate.css CDN para efectos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</div>
        <!-- Modal Profesional Avanzado -->
        <div class="modal fade" id="userInfoModal" tabindex="-1" aria-labelledby="userInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content shadow-lg rounded-4 border-0">

                    <!-- Header -->
                    <div class="modal-header text-light rounded-top-4" style="background: linear-gradient(90deg, #343a40, #495057);">
                        <h5 class="modal-title fw-bold" id="userInfoModalLabel">Panel de Usuario</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">
                        <div class="row">

                            <!-- Lado izquierdo: Perfil -->
                            <div class="col-md-4 text-center mb-3">
                                <img src="https://randomuser.me/api/portraits/lego/1.jpg"
                                    alt="Usuario"
                                    class="img-fluid rounded-circle shadow-sm border border-light"
                                    style="width:150px; height:150px; object-fit: cover;">
                                <h4 class="fw-bold mt-3">{{ session('name') }}</h4>
                                <small class="text-muted">
                                        {{ 
                                            session('rol') === 'Gerencia' ? 'DIRECCIÓN GENERAL' : 
                                            (session('rol') === 'Consultante' ? 'Director de Agencia' : 
                                            (session('rol') === 'Coordinacion' ? 'Coordinación' : session('rol'))) 
                                        }}
                                </small>



                                <div class="mt-3 d-flex flex-column gap-2">
                                    <button class="btn btn-secondary btn-sm shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        <i class="fa-solid fa-key"></i> Cambiar Contraseña
                                    </button>
                                    <button class="btn btn-primary btn-sm shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#editPerfilModal">
                                        <i class="fa-solid fa-pen-to-square"></i> Editar Perfil
                                    </button>
                                </div>
                            </div>

                            <!-- Lado derecho: Tabs de información -->
                            <div class="col-md-8">
                            <!-- Tabs Premium -->
                            <ul class="nav nav-tabs mb-3" id="userTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active TEX" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">
                                        Información
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link TEX" id="actividad-tab" data-bs-toggle="tab" data-bs-target="#actividad" type="button" role="tab" aria-controls="actividad" aria-selected="false">
                                        Actividad
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link TEX" id="permisos-tab" data-bs-toggle="tab" data-bs-target="#permisos" type="button" role="tab" aria-controls="permisos" aria-selected="false">
                                        Permisos
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link TEX" id="documentos-tab" data-bs-toggle="tab" data-bs-target="#documentos" type="button" role="tab" aria-controls="documentos" aria-selected="false">
                                        Documentos
                                    </button>
                                </li>
                            </ul>


                                <div class="tab-content" id="userTabContent">

                                    <!-- Información general -->
                                    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="card shadow-sm mb-2 p-2">
                                                    @if(in_array(session('rol'), ['Jefatura', 'Gerencia', 'Coordinacion']))
                                                        <strong>Área:</strong> {{ session('agenciau') === 'Gerencia General' ? 'DIRECCIÓN GENERAL' : session('agenciau') }}
                                                        <strong>Código:</strong> {{ session('codigo') ?? 'No disponible' }}
                                                    @else
                                                        <strong>Agencia:</strong> {{ session('agenciau') === 'Gerencia General' ? 'DIRECCIÓN GENERAL' : session('agenciau') }}
                                                        <strong>Código:</strong> {{ session('codigo') ?? 'No disponible' }}
                                                    @endif

                                                </div>
                                            </div>
                                            @if(session('rol') == 'Consultante')
                                            <div class="col-6">
                                                <div class="card shadow-sm mb-2 p-2">
                                                    <strong>Coordinación Asignada:</strong> {{ session('coordasignadas') }}
                                                    
                                                </div>
                                            </div>
                                            @elseif(session('rol') == 'Coordinacion')
                                            <div class="col-6">
                                                
                                                <div class="agencias-scroll">
                                                    <strong>Agencias Vinculadas:</strong>
                                                    {!! session('coordasignadas') !!}
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-6">
                                                <div class="card shadow-sm mb-2 p-2">
                                                    <strong>Email:</strong> {{ session('email') ?? 'No disponible' }}
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="card shadow-sm mb-2 p-2">
                                                    <strong>Teléfono:</strong> +57 {{  $usuario['celular']  ?? 'No disponible' }} <br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actividad -->
                                    <div class="tab-pane fade" id="actividad" role="tabpanel" aria-labelledby="actividad-tab">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><strong>Último acceso:</strong> {{ session('ultimo_acceso') }}</li>
                                            <li class="list-group-item"><strong>Última acción:</strong> {{ session('ultima_accion') }}</li>
                                            <li class="list-group-item"><strong>Sesiones activas:</strong> 1</li>
                                            <li class="list-group-item"><strong>Login recientes:</strong> {{ session('logins_recientes') }}</li>
                                        </ul>
                                    </div>

                                    <!-- Permisos -->
                                    <div class="tab-pane fade" id="permisos" role="tabpanel" aria-labelledby="permisos-tab">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Pronto...</li>
                                        </ul>
                                    </div>

                                    <!-- Documentos -->
                                    <div class="tab-pane fade" id="documentos" role="tabpanel" aria-labelledby="documentos-tab">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Pronto...<button class="btn btn-sm btn-outline-secondary float-end">Ver</button></li>

                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer justify-content-center border-0">
                        <button type="button" class="btn btn-warning fw-bold px-4 shadow-sm" data-bs-dismiss="modal">
                            Cerrar
                        </button>
                    </div>

                </div>
            </div>
        </div>



    {{-- MODAL ACTUALIZAR CONTRASENA --}}
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4">
        <div class="modal-header">
            <h5 class="modal-title text-light fw-bold" id="changePasswordLabel">Actualizar Contraseña</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
            <form id="changePasswordForm" method="POST" action="{{ route('password.update') }}">
            @csrf

                <div class="mb-3 position-relative">
                    <label for="current_password" class="form-label fw-bold">Contraseña Actual</label>
                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                    <i class="fa-solid mt-3 fa-eye position-absolute top-50 end-0 translate-middle-y me-3 toggle-password" 
                    data-target="current_password" style="cursor:pointer;"></i>
                    @error('current_password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 position-relative">
                    <label for="new_password" class="form-label fw-bold">Nueva Contraseña</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" required>
                    <i class="mt-3 fa-solid fa-eye position-absolute top-50 end-0 translate-middle-y me-3 toggle-password" 
                    data-target="new_password" style="cursor:pointer;"></i>
                    @error('new_password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 position-relative">
                    <label for="new_password_confirmation" class="form-label fw-bold">Confirmar Nueva Contraseña</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                    <i class="fa-solid mt-3 fa-eye position-absolute top-50 end-0 translate-middle-y me-3 toggle-password" 
                    data-target="new_password_confirmation" style="cursor:pointer;"></i>
                </div>

            <div class="mb-3">
                <p class="fw-bold mb-1">Reglas de la contraseña:</p>
                <ul class="list-unstyled" id="passwordRules">
                <li id="rule-length" class="text-danger">❌ Al menos 8 caracteres</li>
                <li id="rule-uppercase" class="text-danger">❌ Al menos 1 mayúscula</li>
                <li id="rule-lowercase" class="text-danger">❌ Al menos 1 minúscula</li>
                <li id="rule-number" class="text-danger">❌ Al menos 1 número</li>
                <li id="rule-symbol" class="text-danger">❌ Al menos 1 símbolo (@$!%*?&)</li>
                <li id="rule-match" class="text-danger">❌ Las contraseñas coinciden</li>
                </ul>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary" id="submitPassword" disabled>Actualizar Contraseña</button>
            </div>
            </form>
        </div>
        </div>
    </div>
    </div>


    {{-- MODAL ACTUALIZAR PERFIL --}}
    <div class="modal fade" id="editPerfilModal" tabindex="-1" aria-labelledby="editPerfilModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title text-light fw-bold" id="editPerfilModalLabel">Editar Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form method="POST" action="{{ route('perfil.update') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $usuario['name'] }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="celular" class="form-label fw-bold">Celular</label>
                            <input type="text" class="form-control" id="celular" name="celular" value="{{ $usuario['celular'] }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>






    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

<script>
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');
    const submitBtn = document.getElementById('submitPassword');

    const rules = {
        length: document.getElementById('rule-length'),
        uppercase: document.getElementById('rule-uppercase'),
        lowercase: document.getElementById('rule-lowercase'),
        number: document.getElementById('rule-number'),
        symbol: document.getElementById('rule-symbol'),
        match: document.getElementById('rule-match')
    };

    function validatePassword() {
        const password = newPassword.value;
        const confirm = confirmPassword.value;

        // Verificar reglas
        const checks = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            symbol: /[@$!%*?&]/.test(password),
            match: password && password === confirm
        };

        // Actualizar UI de reglas
        for (let key in checks) {
            rules[key].textContent = (checks[key] ? '✅' : '❌') + ' ' + rules[key].textContent.slice(2);
            rules[key].className = checks[key] ? 'text-success' : 'text-danger';
        }

        // Activar botón si todas las reglas son verdaderas
        submitBtn.disabled = !Object.values(checks).every(Boolean);
    }

    // Escuchar eventos
    newPassword.addEventListener('input', validatePassword);
    confirmPassword.addEventListener('input', validatePassword);

    document.querySelectorAll('.toggle-password').forEach(function(icon) {
        icon.addEventListener('click', function() {
            const targetId = icon.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

