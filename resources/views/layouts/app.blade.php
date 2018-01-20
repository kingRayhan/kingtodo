{{--
    Created by KingRayhan<https://github.com/kingRayhan>.
    Date: 1/19/2018
    Time: 2:07 PM
--}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title')</title>

    <link rel="shortcut icon" type="image/x-icon" href="favicon.jpg">
    
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>

    <div id="app"> @yield('page-content') </div>

<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>