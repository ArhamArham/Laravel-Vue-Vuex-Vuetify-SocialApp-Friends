<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Laravel Vue Vuetify Social App</title>

    <!-- Fonts -->
    <link href="" rel="stylesheet">
    <link href="" rel="stylesheet">

</head>

<body>
    <div id="app">
        <App />
    </div>
</body>
<script src="{{ asset('js/app.js') }}"></script>

</html>
