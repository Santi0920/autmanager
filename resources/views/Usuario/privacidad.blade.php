@include('layouts/head')

    <body class="antialiased">
        @include('layouts/nav')
        <style>

            :root{--bg:#f7f8fb;--card:#ffffff;--accent:#0b63d6;--muted:#6b7280;--radius:12px}
            *{box-sizing:border-box}
            body{font-family:Inter,ui-sans-serif,system-ui,Segoe UI,Roboto,'Helvetica Neue',Arial; margin:0; background:linear-gradient(180deg,var(--bg),#eef2f7); color:#0f172a}
            .container{max-width:920px;margin:48px auto;padding:28px}
            header{display:flex;align-items:center;gap:18px}
            .brand{width:86px;height:86px;border-radius:10px;background:linear-gradient(135deg,var(--accent),#2ea0ff);display:flex;align-items:center;justify-content:center;color:white;font-weight:700}
            .card{background:var(--card);padding:28px;border-radius:var(--radius);box-shadow:0 6px 24px rgba(11,99,214,0.08)}
            h1{margin:0 0 8px;font-size:22px}
            p.lead{margin:0;color:var(--muted)}
            h2{margin-top:20px}
            ul{margin-left:1.1rem}
            .meta{display:flex;gap:12px;color:var(--muted);font-size:14px}
            footer{margin-top:26px;color:var(--muted);font-size:13px}
            a{color:var(--accent);text-decoration:none}
            .small{font-size:14px;color:var(--muted)}
        </style>
        <main class="container">
        <div class="card">
        <header>
        <div class="brand">
            COOPSERP
        </div>
        <div>
        <h1>Política de Privacidad para Funcionarios</h1>
        <p class="lead">Entrada en vigor: <strong>Noviembre 04 2025</strong></p>
        <div class="meta">Última actualización: Noviembre 04 2025</div>
        </div>
        </header>

        <section>
        <h2>1. Información que recopilamos</h2>
        <p>Podemos recopilar y tratar los siguientes datos personales de los funcionarios:</p>
        <ul>
        <li>Datos de identificación: nombre completo, tipo y número de documento.</li>
        <li>Datos de contacto: correo electrónico corporativo, teléfono.</li>
        <li>Datos laborales: área, cargo, roles y permisos dentro del sistema.</li>
        <li>Datos de uso: direcciones IP, logs de acceso, acciones ejecutadas en la plataforma.</li>
        </ul>

        <h2>2. Finalidad del tratamiento</h2>
        <p>La información recopilada se usa exclusivamente para fines laborales, tales como:</p>
        <ul>
        <li>Gestionar accesos, permisos y solicitudes internas.</li>
        <li>Garantizar el funcionamiento y mejora del sistema.</li>
        <li>Implementar controles de seguridad y auditoría.</li>
        <li>Cumplir obligaciones legales y regulatorias.</li>
        </ul>

        <h2>3. Base legal del tratamiento</h2>
        <p>El tratamiento de los datos personales de funcionarios se fundamenta en la relación laboral, el cumplimiento de obligaciones legales y los intereses legítimos de COOPSERP para la adecuada gestión corporativa.</p>

        <h2>4. Conservación de datos</h2>
        <p>
        Los datos se conservarán durante la vigencia de la relación laboral del funcionario y, posteriormente, únicamente para mantener el historial y la trazabilidad de las autorizaciones gestionadas en la plataforma.
        </p>


        <h2>5. Seguridad de la información</h2>
        <p>Aplicamos medidas técnicas y organizativas para proteger la información. Sin embargo, ningún sistema es completamente seguro. Promovemos el uso responsable de las credenciales y el reporte inmediato de incidentes.</p>

        <h2>6. Derechos del funcionario</h2>
        <p>Como titular de la información, puede ejercer derechos de acceso, actualización, rectificación y supresión dentro de los límites legales aplicables.</p>

        <h2>7. Actualizaciones de esta Política</h2>
        <p>COOPSERP podrá modificar esta Política en cualquier momento. La nueva versión se publicará en la plataforma con su correspondiente fecha de actualización.</p>
        </section>

        <footer class="meta">
        <p>
        Esta Política se aplica exclusivamente al uso interno por parte de funcionarios de COOPSERP en sus labores y responsabilidades institucionales.
        </p>
        </footer>

        </div>
        </main>

        <!-- Solicitar celular si la cuenta no tiene vinculado un numero -->
        @include('layouts.celular')
        @include('layouts.footer')
        
        <!-- si se cierra la sesion que retorne -->
        @include('layouts.retornar')
    </body>
</html>