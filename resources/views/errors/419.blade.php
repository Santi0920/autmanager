<!DOCTYPE html>
<html>
<head>
    <title>Sesión Expirada</title>
    <link rel="shortcut icon" href="img/logoo.png" type="image/png">
    <style>
        body {
            text-align: center;
            padding: 50px;
            font-family: "Helvetica", sans-serif;
        }
        h1 {
            font-size: 50px;
        }
        body {
            font: 20px Helvetica, sans-serif;
            color: #333;
        }
        article {
            display: block;
            text-align: left;
            width: 650px;
            margin: 0 auto;
        }
        a {
            color: #dc8100;
            text-decoration: none;
        }
        a:hover {
            color: #333;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <article>
        <h1>La sesión se expiró.</h1>
            <p style="font-size: 30px; font-weight:bold"><a href="">Redirigiendo a inicio de sesión...</a></p>
    </article>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                window.location.href = './';
            }, 1);
        });
    </script>
</body>
</html>
