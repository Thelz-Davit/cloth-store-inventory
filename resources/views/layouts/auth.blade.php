<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/auth.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('mazer/dist/assets/static/images/logo/favicon.png') }}">
    
    <link rel="shortcut icon" href="{{ asset('mazer/dist/assets/static/images/favicon.ico') }}">
</head>

<body>
    <script src="assets/static/js/initTheme.js"></script>
    <div id="auth">
        @yield('content')
    </div>
</body>

</html>
