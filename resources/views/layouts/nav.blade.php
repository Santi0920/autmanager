<div class="navbarBgDark" style="background-color: #646464;">
<!-- Navbar Ultra Premium -->
<nav class="navbar navbar-expand-lg navbar-dark p-3 shadow-sm" style="background: linear-gradient(90deg, #343a40, #495057);">
        <div class="container-fluid">

            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="img/CoopserpPH.png" alt="Coopserp Logo" width="182" height="60" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));">
            </a>

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
                                {{ session('name') }}
                            </span>
                        </a>
                    </li>

                    <!-- Agencia -->
                    <li class="nav-item me-3">
                        <span class="nav-link d-flex align-items-center text-light fw-bold">
                            Agencia:
                            <div class="btn btn-warning ms-2 shadow fw-bold px-3 py-2 rounded-pill">
                                @if(session('agenciau') == 'Gerencia General')
                                    Cali
                                @else
                                    {{ session('agenciau') }} ({{ session('coordasignadas') }})
                                @endif
                            </div>
                        </span>
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

                    <!-- Estilos dropdown ultra premium -->
                    <style>
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
                    </style>

                    <!-- Font Awesome para iconos -->
                    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>




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

    <!-- Estilos Ultra Premium -->
    <style>
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
    </style>

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
                                <small class="text-muted">{{ session('rol') }}</small>

                                <div class="mt-3 d-flex flex-column gap-2">
                                    <button class="btn btn-primary btn-sm shadow-sm">
                                        <i class="fa-solid fa-pen-to-square"></i> Editar Perfil
                                    </button>
                                    <button class="btn btn-secondary btn-sm shadow-sm">
                                        <i class="fa-solid fa-key"></i> Cambiar Contraseña
                                    </button>
                                    <button class="btn btn-info btn-sm shadow-sm">
                                        <i class="fa-solid fa-envelope"></i> Enviar Email
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
                            </style>


                                <div class="tab-content" id="userTabContent">

                                    <!-- Información general -->
                                    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="card shadow-sm mb-2 p-2">
                                                    <strong>Agencia:</strong> {{ session('agenciau') }}
                                                </div>
                                            </div>
                                            @if(session('rol') == 'Consultante')
                                            <div class="col-6">
                                                <div class="card shadow-sm mb-2 p-2">
                                                    <strong>Coordinación:</strong> {{ session('coordasignadas') }}
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
                                                    <strong>Teléfono:</strong> +57 300 1234567 <br>
                                                    <strong>Ciudad:</strong> Cali
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actividad -->
                                    <div class="tab-pane fade" id="actividad" role="tabpanel" aria-labelledby="actividad-tab">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><strong>Último acceso:</strong> 2025-10-30 14:23</li>
                                            <li class="list-group-item"><strong>Última acción:</strong> Creó un informe</li>
                                            <li class="list-group-item"><strong>Sesiones activas:</strong> 2</li>
                                            <li class="list-group-item"><strong>Login recientes:</strong> 2025-10-28, 2025-10-27</li>
                                        </ul>
                                    </div>

                                    <!-- Permisos -->
                                    <div class="tab-pane fade" id="permisos" role="tabpanel" aria-labelledby="permisos-tab">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Lectura</li>
                                            <li class="list-group-item">Escritura</li>
                                            <li class="list-group-item">Reportes</li>
                                            <li class="list-group-item">Administración</li>
                                        </ul>
                                    </div>

                                    <!-- Documentos -->
                                    <div class="tab-pane fade" id="documentos" role="tabpanel" aria-labelledby="documentos-tab">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Contrato.pdf <button class="btn btn-sm btn-outline-secondary float-end">Ver</button></li>
                                            <li class="list-group-item">Identificación.png <button class="btn btn-sm btn-outline-secondary float-end">Ver</button></li>
                                            <li class="list-group-item">Informe.xlsx <button class="btn btn-sm btn-outline-secondary float-end">Ver</button></li>
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

<script>
        function csesion() {
            var respuesta = confirm("¿Estas seguro que deseas cerrar sesión?")
            return respuesta
        }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

