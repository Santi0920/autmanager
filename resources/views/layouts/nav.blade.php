<div class="navbarBgDark" style="background-color: #646464;">
    <nav class="navbar navbar-expand-lg justify-content-center justify-content-lg-between p-0">

        <button class="navbar-toggler m-3 w-100 text-light" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon text-light" style="color: white;"></span>

            Menu
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navbarNavDropdown">
            <ul class="navbar-nav text-uppercase ps-3 fw-bold">
                <li class="nav-item">
                    <span class="nav-link pe-3" style="font-weight: 600;font-size: 18px;" aria-current="page"
                        href="#">
                        Bienvenido: <div class="btn btn-warning shadow"
                            style="padding: 0.4rem 0.7rem; border-radius: 10%;font-weight: 600;font-size: 16px;"><label
                                style="margin-bottom: 0px;">{{ auth()->user()->name }}</strong></div>
                    </span>
                </li>

                <li class="nav-item">
                    <span class="nav-link pe-3" style="font-weight: 600;font-size: 18px;" aria-current="page"
                        href="#">
                        Rol:
                        <div class="btn btn-warning shadow"
                            style="padding: 0.4rem 0.7rem; border-radius: 10%;font-weight: 600;font-size: 16px;">
                            <label style="margin-bottom: 0px;">
                                @if (auth()->user()->rol == 'Consultante')
                                    Director
                                @elseif(auth()->user()->rol == 'Jefatura')
                                    Gerencia
                                @else
                                    {{ auth()->user()->rol }}
                                @endif
                            </label>
                        </div>
                    </span>
                </li>

                <li class="nav-item">
                    <span class="nav-link pe-3" style="font-weight: 600;font-size: 18px;" aria-current="page"
                        href="#">
                        Agencia: <div class="btn btn-warning shadow"
                            style="padding: 0.4rem 0.7rem; border-radius: 10%;font-weight: 600;font-size: 16px;"><label
                                style="margin-bottom: 0px;">
                                @if (auth()->user()->agenciau == 'Gerencia General')
                                    Cali
                                @else
                                    {{ auth()->user()->agenciau }}
                                @endif
                                </strong></div>
                    </span>
                </li>

            </ul>
            <div class="text-white m-1 p-3">
                <button class="buttontroll me-3" data-text="Awesome">
                    <span class="actual-text">&nbsp;Software Autorizaciones&nbsp;</span>
                    <span aria-hidden="true" class="hover-text">&nbsp;Software&nbsp;Autorizaciones&nbsp;</span>
                </button>
                <a onclick="return csesion()" href="{{ route('login.destroy') }}"><button class="btn btn-light"><b
                            style="font-size: 18px;">Cerrar Sesión</b></button></a>
            </div>
        </div>

    </nav>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<style>
    /* === removing default button style ===*/
    .buttontroll {
        margin: 0;
        cursor: default;
        height: auto;
        background: transparent;
        padding: 0;
        border: none;
        cursor: pointer;
    }

    /* button styling */
    .buttontroll {
        cursor: default;
        --border-right: 6px;
        --text-stroke-color: rgba(255, 255, 255, 0.6);
        --animation-color: #ffffff;
        --fs-size: 2em;
        letter-spacing: 3px;
        text-decoration: none;
        font-size: 25px;
        font-family: "Arial";
        position: relative;
        text-transform: uppercase;
        color: transparent;
        -webkit-text-stroke: 1px var(--text-stroke-color);
    }

    /* this is the text, when you hover on button */
    .hover-text {
        position: absolute;
        cursor: default;
        box-sizing: border-box;
        content: attr(data-text);
        color: var(--animation-color);
        width: 0%;
        inset: 0;
        border-right: var(--border-right) solid var(--animation-color);
        overflow: hidden;
        transition: 0.5s;
        -webkit-text-stroke: 1px var(--animation-color);
    }

    /* hover */
    .buttontroll:hover .hover-text {
        cursor: default;
        width: 100%;
        filter: drop-shadow(0 0 23px var(--animation-color))
    }


    body {
        font-family: "Poppins", sans-serif;
    }

    .textColor {
        color: #030F27;
    }

    .navbarBgDark {
        background: #030F27;
    }

    .navbar-nav .nav-link.active {
        color: #EDA72F;
    }

    .nav-link:hover {
        color: #EDA72F;
    }

    .nav-link {
        color: #fff;
    }

    .getBtn {
        color: #fff;
    }

    .sideLine {
        border-right: 1px solid #030F27;
    }

    .iconHeight {
        height: 24px;
        width: 24px;
    }

    @media screen and (min-width: 992px) {
        .sideLine {
            border-right: none;
        }

        .iconHeight {
            height: 46px;
            width: 46px;
        }
    }
</style>
