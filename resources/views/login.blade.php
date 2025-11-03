<!DOCTYPE html>
<html lang="es">
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
    <title>Autorizaciones | Iniciar Sesión</title>

    <style>
        /* ---------------------------
        Palette & global
        --------------------------- */
        :root{
            --brand-dark: #003d37;
            --brand: #007968;
            --brand-2: #00a08a;
            --muted: #6b7a78;
            --card-bg: rgba(255,255,255,0.98);
            --glass-overlay: rgba(255,255,255,0.06);
            --radius: 14px;
        }
        *{box-sizing:border-box}
        html,body{height:100%;margin:0;font-family: "Inter", "Segoe UI", system-ui, -apple-system, Roboto, "Helvetica Neue", Arial; background: linear-gradient(180deg,#f5f7f7 0%, #eef3f3 100%); -webkit-font-smoothing:antialiased;}

        /* container */
        .center-wrap{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:32px;
        }

        /* card */
        .login-card{
            width:100%;
            max-width:980px;
            display:grid;
            grid-template-columns: 1fr 1fr;
            border-radius:20px;
            overflow:hidden;
            background:var(--card-bg);
            box-shadow: 0 22px 60px rgba(6,24,30,0.15);
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .login-card:hover{ transform: translateY(-4px); box-shadow: 0 32px 80px rgba(6,24,30,0.18); }

        /* left image (glass blur overlay) */
        .login-image{
            position:relative;
            min-height:420px;
            background-image: url('img/log.png');
            background-size: cover;
            background-position: center;
            display:block;
        }
        .login-image::before{
            content:"";
            position:absolute; inset:0;
            background: linear-gradient(180deg, rgba(0,63,56,0.35), rgba(0,63,56,0.10));
            backdrop-filter: blur(6px) saturate(1.05);
            -webkit-backdrop-filter: blur(6px) saturate(1.05);
        }
        /* a subtle brand band in the left image corner */
        .image-brand {
            position:absolute; left:22px; bottom:22px;
            background: rgba(255,255,255,0.06);
            padding:10px 14px; border-radius:10px;
            color:#fff; font-weight:700; letter-spacing:0.6px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.25);
            backdrop-filter: blur(4px);
        }

        /* right form area */
        .login-form{
            padding:36px 42px;
            display:flex;
            flex-direction:column;
            justify-content:center;
            gap:18px;
        }

        .logo-wrap{ text-align:center; margin-bottom:0px; }
        .logo-wrap img{ height:148px; object-fit:contain; transition: transform .25s ease; }
        .logo-wrap img:hover{ transform: scale(1.03); }

        h1.title{
            margin:6px 0 4px; font-size:26px; color:var(--brand-dark); font-weight:800;
            letter-spacing:0.2px;
        }
        p.lead{
            margin:0; color:var(--muted); font-size:14px;
        }

        /* input group */
        .field {
            display:flex; align-items:center; gap:0;
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(250,250,250,0.98));
            border-radius:12px;
            padding:6px;
            border:1px solid #e6ebea;
        }
        .field .icon {
            width:48px; height:48px; display:flex; align-items:center; justify-content:center;
            color: #fff; background: linear-gradient(180deg,var(--brand),var(--brand-2));
            border-radius:10px;
            margin-right:8px; flex:0 0 48px;
        }
        .field input{
            border:0; outline:0; padding:12px 14px; font-size:15px; width:100%;
            background:transparent;
            color:#0b2320;
        }
        .field input::placeholder{ color:#93a3a0; }

        /* show/hide toggle */
        .field .toggle-pass{
            cursor:pointer; margin-left:6px; color:var(--muted); background:transparent; border:none;
        }

        /* actions */
        .actions{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-top:6px; }
        .forgot{ color:var(--muted); font-size:14px; text-decoration:none; }
        .forgot:hover{ text-decoration:underline; color:var(--brand); }

        .btn-primary{
            background: linear-gradient(90deg,var(--brand),var(--brand-2));
            border:0; color:white; padding:12px 18px; border-radius:12px;
            font-weight:700; letter-spacing:0.4px; cursor:pointer;
            box-shadow: 0 10px 30px rgba(0,128,110,0.14);
            transition: transform .18s ease, box-shadow .18s ease;
        }
        .btn-primary:hover{ transform: translateY(-3px); box-shadow: 0 16px 42px rgba(0,128,110,0.18); }

        /* helper text / small */
        .small-muted { font-size:13px; color:var(--muted); }

        /* footer small */
        .login-footer{ margin-top:18px; text-align:center; font-size:13px; color:#8f9b99; }

        /* responsive */
        @media (max-width: 900px){
            .login-card { grid-template-columns: 1fr; }
            .login-image { display:none; }
            .login-form{ padding:28px; }
        }

        @media (max-width: 900px){
            .login-card {
                grid-template-columns: 1fr;
                max-width: 420px;
                width: 100%;
            }

            /* ✅ La imagen vuelve a mostrarse y se va arriba */
            .login-image {
                display: block;
                width: 100%;
                height: 200px;
                min-height: 180px;
                background-size: cover;
                background-position: center;
                border-bottom: 4px solid rgba(0,0,0,0.08);
            }

            /* ✅ Centrado y buen espacio */
            .login-form{
                padding: 22px 20px;
                align-items: center;
                text-align: center;
            }

            .logo-wrap img {
                height: 100px;
            }

            h1.title{
                font-size: 28px;
            }

            .field {
                width: 100%;
            }

            .actions {
                width: 100%;
                justify-content: center;
            }

            button#loginBtn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    {{-- SweetAlert notifications (preserve your server-side messages) --}}
    @if (session('correcto'))
        <div>
            <script>
                Swal.fire({
                    icon: 'warning',
                    title: "¡Advertencia!",
                    html: "{!! session('correcto') !!}",
                    confirmButtonColor: '#646464'
                });
            </script>
        </div>
    @endif

    @if (session('message'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error de acceso',
                text: "{!! session('message') !!}",
                confirmButtonColor: '#007968'
            });
        </script>
    @endif



    <main class="center-wrap" role="main" style="background: rgb(0,94,86);
    background: linear-gradient(90deg, #005e56a3 0%, rgba(0,94,86,0.2189250700280112) 35%, rgba(0,0,0,0.4009978991596639) 100%);">
        <div class="login-card" role="application" aria-labelledby="loginTitle" style="font-size: 20px">

            <!-- LEFT: image (glass blur) -->
            <aside class="login-image" aria-hidden="true">
                <div class="image-brand">COOPSERP</div>
            </aside>

            <!-- RIGHT: form -->
            <section class="login-form" aria-labelledby="loginTitle">
                <div class="logo-wrap text-center">
                    <img src="img/Logo-Coopserp.png" alt="Logo Coopserp" HEI>
                </div>

                <h1 id="loginTitle" class="title" style="font-size: 40px">Iniciar sesión</h1>
                <p class="lead">Ingresa tus credenciales para acceder al panel de autorizaciones</p>

                <form method="POST" novalidate aria-describedby="formHelp">
                    @csrf

                    <!-- Email -->
                    <div class="field" role="group" aria-label="Correo electrónico">
                        <div class="icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 8.5v7a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                <path d="M21 6.5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v0.5l9 6 9-6V6.5z" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" placeholder="Correo electrónico" required aria-required="true" autocomplete="username" />
                    </div>

                    <!-- Password -->
                    <div style="height:12px"></div>
                    <div class="field" role="group" aria-label="Contraseña">
                        <div class="icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="10" rx="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </div>

                        <input id="password" name="password" type="password" placeholder="Contraseña" required aria-required="true" autocomplete="current-password" />
                        <button type="button" class="toggle-pass" aria-label="Mostrar contraseña" style="background:none;border:none;padding:8px;margin-left:6px;color:var(--muted)">
                            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                <path d="M2 12s4-8 10-8 10 8 10 8-4 8-10 8S2 12 2 12z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>

                    <div class="actions" style="margin-top:8px;">
                        <div style="display:flex;gap:12px">
                            <button type="submit" class="btn-primary" id="loginBtn">Ingresar</button>
                        </div>
                    </div>  
                </form>
            </section>
        </div>
    </main>

    <script>
        // Accessibility: focus first input
        (function(){
            const email = document.getElementById('email');
            if(email) email.focus();
        })();

        // Toggle password visibility
        (function(){
            const toggle = document.querySelector('.toggle-pass');
            const pwd = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if(toggle && pwd){
                toggle.addEventListener('click', () => {
                    const type = pwd.getAttribute('type') === 'password' ? 'text' : 'password';
                    pwd.setAttribute('type', type);
                    // swap simple eye / eye-off - we keep shapes inline for local SVGs
                    if(type === 'text'){
                        eyeIcon.innerHTML = '<path d=\"M2 12s4-8 10-8 10 8 10 8-4 8-10 8S2 12 2 12z\"></path><path d=\"M17.94 17.94A10.06 10.06 0 0 1 12 20c-6 0-10-8-10-8a18.32 18.32 0 0 1 4.06-5.06\"></path><line x1=\"1\" y1=\"1\" x2=\"23\" y2=\"23\"></line>';
                    } else {
                        eyeIcon.innerHTML = '<path d=\"M2 12s4-8 10-8 10 8 10 8-4 8-10 8S2 12 2 12z\"></path><circle cx=\"12\" cy=\"12\" r=\"3\"></circle>';
                    }
                });
            }
        })();

        // Optional: gentle prevent double submit
        (function(){
            const form = document.querySelector('form');
            const btn = document.getElementById('loginBtn');
            if(form && btn){
                form.addEventListener('submit', function(e){
                    btn.disabled = true;
                    btn.style.opacity = '0.9';
                    btn.innerText = 'Ingresando...';
                });
            }
        })();
    </script>
</body>
</html>
