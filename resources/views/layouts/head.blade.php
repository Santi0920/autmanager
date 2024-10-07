<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Autorizaciones</title>

    <!-- Fonts -->
    <link href="ResourcesAll/Bootstrap/Bootstrap.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/logoo.png" type="image/png">
    <script src="ResourcesAll/jquery/jquery-3.6.0.js"></script>
    <script src="ResourcesAll/jquery/jquery-ui.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="ResourcesAll/fontawesome/fontawesome.js"></script>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <script src="ResourcesAll/Sweetalert/sweetalert2.js"></script>
    <link rel="stylesheet" href="ResourcesAll/Sweetalert/sweetalert2.css">
    <link rel="stylesheet" href="ResourcesAll/Bootstrap/Bootstrap2.css">
    <link rel="stylesheet" href="ResourcesAll/Bootstrap/dataTablesbootstrap5.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">

</head>

