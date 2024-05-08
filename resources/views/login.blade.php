
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="ResourcesAll/Bootstrap/Bootstrap.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/logoo.png" type="img/png">
    <script src="ResourcesAll/Sweetalert/sweetalert2.js"></script>
    <link rel="stylesheet" href="ResourcesAll/Sweetalert/sweetalert2.css">
    <script src="ResourcesAll/fontawesome/fontawesome.js"></script>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <title>Login | Autorizaciones</title>
</head>

<body>
    <section class="vh-100 shadow-lg"
        style="background: rgb(0,94,86);
    background: linear-gradient(90deg, rgba(0,94,86,0.639093137254902) 0%, rgba(0,94,86,0.2189250700280112) 35%, rgba(0,0,0,0.4009978991596639) 100%);">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
                                <img src="img/log.png" alt="login form" class="img-fluid"
                                    style="border-radius: 1rem 0 0 1rem;" />
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">

                                    <form method="POST">
                                        <input type="hidden" name='_token' value="{{csrf_token()}}">
                                        <div class="text-center mb-3 pb-1 img-fluid">
                                            <img src="img/Logo-Coopserp.png" alt="LOGO COOPSERP" class=""
                                                style="height: 7.2rem; width: 17.5rem" />
                                        </div>

                                        <h2 class="fw-normal mb-3 pb-3 fw-bold" style="letter-spacing: 1px;">Iniciar
                                            Sesión</h2>

                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="email" id="form2Example17" name="email"
                                                class="form-control form-control-lg" />
                                            <label class="form-label" for="form2Example17">Correo Electrónico</label>
                                        </div>

                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="password" id="form2Example27" name="password" required
                                                class="form-control form-control-lg" />
                                            <label class="form-label" for="form2Example27">Contraseña</label>
                                        </div>
                                        @error('message')
                                        <div>
                                            <script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: "Error!\n Contraseña incorrecta!",
                                                    text: '',
                                                    confirmButtonColor: '#646464'

                                                });
                                            </script>
                                        </div>
                                        @enderror

                                        <div class="pt-1 mb-4">
                                            <button data-mdb-button-init data-mdb-ripple-init
                                                class="btn btn-dark btn-lg btn-block" type="submit">Ingresar</button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</body>

</html>
