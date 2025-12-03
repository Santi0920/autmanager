<!-- Footer Ultra Premium -->
<footer class="mt-5 position-relative">
    <div class="container-fluid p-5 text-light" style="background: linear-gradient(135deg, #4e4e4e, #646464); overflow: hidden;">
        
        <!-- Animación decorativa -->
        <div class="position-absolute top-0 start-50 translate-middle-x" style="opacity:0.1; font-size: 200px; pointer-events:none;">
            <i class="fas fa-leaf"></i>
        </div>

        <div class="row g-4">

            <!-- Logo y Ubicación -->
            <div class="col-12 col-md-6 col-lg-3 animate__fadeInUp">
                <h3 class="fw-bold">&copy; Coopserp Web</h3>
                <p class="text-light-50">Cali, Colombia</p>
                <img src="img/CoopserpPH.png" alt="Coopserp Logo" class="img-fluid mt-2" style="max-width: 220px;">
            </div>

            <!-- Servicios -->
            <div class="col-12 col-md-6 col-lg-3 animate__fadeInUp">
                <h5 class="fw-bold mb-3">Servicios</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="http://app.coopserp.com/menu-datacredito/inicia-sesion" target="_blank" class="text-light text-decoration-none fw-medium hover-effect">Datacrédito</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-light text-decoration-none fw-medium hover-effect">Otro Servicio</a>
                    </li>
                </ul>
            </div>

            <!-- Enlaces -->
            <div class="col-12 col-md-6 col-lg-3 animate__fadeInUp">
                <h5 class="fw-bold mb-3">Enlaces</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="tyc" target="_blank" class="text-light text-decoration-none fw-medium hover-effect">Términos & Condiciones</a>
                    </li>
                    <li class="mb-2">
                        <a href="privacidad" target="_blank" class="text-light text-decoration-none fw-medium hover-effect">Política de Privacidad</a>
                    </li>
                </ul>
            </div>

            <!-- Contactos y Clientes -->
            <div class="col-12 col-md-6 col-lg-3 animate__fadeInUp">
                <h5 class="fw-bold mb-3">Otros Contactos</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li>
                        <a href="https://github.com/Santi0920/Coopserp" target="_blank" class="text-light text-decoration-none fw-medium hover-effect">
                            <i class="fab fa-github me-2"></i> Github
                        </a>
                    </li>
                    <li>
                        <a href="https://www.linkedin.com/in/santiago-henao/" class="text-light text-decoration-none fw-medium hover-effect" target="_blank">
                            <i class="fab fa-linkedin me-2"></i> LinkedIn
                        </a>
                    </li>
                </ul>

            </div>

        </div>

        <!-- Pie de copyright -->
        <div class="row mt-5">
            <div class="col text-center animate__fadeInUp">
                <p class="mb-0 small text-light-50">
                    Coopserp Web &copy; <?php echo date('Y'); ?>. Diseñado y Desarrollado por 
                    <a href="https://github.com/Santi0920" target="_blank" class="text-warning text-decoration-none fw-semibold hover-effect">Santiago Henao</a>
                </p>
            </div>
        </div>

    </div>
</footer>

<!-- Estilos adicionales -->
<style>
    .hover-effect:hover {
        color: #ffc107 !important;
        text-decoration: underline;
        transition: all 0.3s ease-in-out;
    }

    .text-light-50 {
        color: rgba(255,255,255,0.7);
    }

    footer h3, footer h5 {
        letter-spacing: 0.5px;
    }

    /* Animaciones suaves al aparecer (usando Animate.css) */
    @keyframes fadeInUp {
        0% {opacity: 0; transform: translateY(20px);}
        100% {opacity: 1; transform: translateY(0);}
    }

    .animate__fadeInUp {
        animation: fadeInUp 1s ease forwards;
    }
</style>

