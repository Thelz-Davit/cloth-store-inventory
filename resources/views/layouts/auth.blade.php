<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/auth.css') }}">
    <link rel="icon" type="image/x-icon" sizes="16x16"
        href="https://d2kchovjbwl1tk.cloudfront.net/favicon/favicon_web_1632967746769_resized16-jpg.webp">
</head>

<body>
    <script src="assets/static/js/initTheme.js"></script>
    <div id="auth">
        @yield('content')
    </div>
</body>

</html>
