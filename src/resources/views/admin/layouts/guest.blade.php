<!DOCTYPE html>
<html lang="en">
<head>
    @section('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ELF CMS Login</title>
    @isset($elfSiteSettings['icon'])
    <link rel="shortcut icon" href="{{ asset(file_path($elfSiteSettings['icon'])) }}" type="image/x-icon">
    @endisset
    <link rel="stylesheet" href="{{ asset('elfcms/admin/fonts/roboto/roboto.css') }}">
    <link rel="stylesheet" href="{{ asset('elfcms/admin/css/style.css') }}">
    @show
</head>
<body class="system-login">
    @section('header')
    <header class="header">

    </header>
    @show

    <main class="main">
        <div class="main-content-box">
    @section('main')
    @show

    </main>

    @section('footer')
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-navigation">

            </div>
            <div class="footer-copyright">
                &copy; 2020 - {{ date('Y') }} Maxim Klassen
            </div>
        </div>
    </footer>
    @show
</body>
</html>
