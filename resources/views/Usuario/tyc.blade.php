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
        <h1>Términos y Condiciones para Funcionarios</h1>
        <p class="lead">Entrada en vigor: <strong>Noviembre 04 2025</strong></p>
        <div class="meta">Última actualización: Noviembre 04 2025</div>
        </div>
        </header>


        <section>
        <h2>1. Aceptación de los Términos</h2>
        <p>
        Estos Términos y Condiciones regulan el acceso y uso del sistema interno de gestión de autorizaciones de COOPSERP, dirigido exclusivamente a funcionarios, empleados o personal autorizado. 
        Al ingresar al Sistema, usted manifiesta ser un usuario autorizado y acepta cumplir las políticas internas, la Política de Privacidad y la normatividad vigente aplicable en materia laboral y de protección de datos.
        </p>

        <h2>2. Definiciones</h2>
        <p>
        <em>"Funcionario"</em>: cualquier persona autorizada por COOPSERP para acceder al Sistema.<br>
        <em>"Información Confidencial"</em>: toda información institucional, operativa y de asociados a la cual se acceda mediante el uso del Sistema, así como aquella relacionada con procesos de autorizaciones internas.
        </p>

        <h2>3. Uso permitido</h2>
        <ul>
        <li>El Sistema debe utilizarse exclusivamente para el desarrollo de funciones laborales asignadas por COOPSERP.</li>
        <li>Queda estrictamente prohibido divulgar, extraer o utilizar Información Confidencial con fines no laborales.</li>
        <li>No se podrá intentar acceder, manipular o modificar información fuera del alcance y permisos autorizados por el rol del funcionario.</li>
        <li>Todo acceso y operación realizada se registra para fines de trazabilidad, control interno y auditoría.</li>
        </ul>
        <p>
        El uso indebido del Sistema puede derivar en medidas disciplinarias conforme al Código Sustantivo del Trabajo y reglamentos internos de COOPSERP.
        </p>

        <h2>4. Propiedad intelectual y confidencialidad</h2>
        <p>
        El Sistema, su contenido, estructura, documentación y funcionalidades son propiedad exclusiva de COOPSERP. 
        La información gestionada se encuentra protegida bajo la <strong>Ley 1581 de 2012</strong> de Protección de Datos Personales y demás normativas aplicables.
        Queda prohibida su reproducción, transferencia o divulgación sin autorización institucional expresa.
        </p>

        <h2>5. Responsabilidad del funcionario</h2>
        <p>
        El funcionario es responsable de las acciones realizadas con sus credenciales, así como de mantener su confidencialidad y uso personal. 
        COOPSERP podrá ejecutar auditorías y controles internos en virtud de las obligaciones legales y de Habeas Data (Ley 1266 de 2008 y normas complementarias).
        </p>
        <p>
        El uso indebido que comprometa información institucional o de asociados podrá constituir falta disciplinaria e incluso delito conforme a la 
        <strong>Ley 1273 de 2009 — Delitos Informáticos</strong>.
        </p>

        <h2>6. Seguridad de la información</h2>
        <p>
        COOPSERP adopta medidas técnicas, administrativas y organizativas razonables para proteger la información procesada en el Sistema. 
        No obstante, ningún mecanismo es infalible, por lo que es indispensable que el funcionario colabore en la protección del acceso y reporte oportunamente cualquier incidente o vulnerabilidad.
        </p>

        <h2>7. Modificaciones</h2>
        <p>
        COOPSERP podrá actualizar o modificar estos Términos en cualquier momento según cambios legales, tecnológicos u operativos. 
        Las modificaciones serán comunicadas a los funcionarios a través de canales internos corporativos.
        </p>

        <h2>8. Jurisdicción y ley aplicable</h2>
        <p>
        Los presentes Términos se rigen por la legislación colombiana en materia laboral, corporativa y de protección de datos personales. 
        Cualquier controversia será resuelta por las autoridades competentes de la ciudad de Cali, Valle del Cauca.
        </p>
        </section>



        <footer>
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