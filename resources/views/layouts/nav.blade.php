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
                                {{ $usuario['name'] }}
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
                                    {{ session('agenciau') }}
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
                                        <i class="fas fa-search me-2"></i> Buscar Autorización(Antes 4 Nov)
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

                            </style>


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