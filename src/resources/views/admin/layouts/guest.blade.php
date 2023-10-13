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
    <link rel="shortcut icon" href="{{ asset($elfSiteSettings['icon']) }}" type="image/x-icon">
    @endisset
    {{-- @isset($elfSiteSettings['keywords'])
    <meta name="keywords" content="{{ $elfSiteSettings['keywords'] }}">
    @endisset
    @isset($elfSiteSettings['description'])
    <meta name="description" content="{{ $elfSiteSettings['description'] }}">
    @endisset --}}
    {{-- <link rel="stylesheet" href="/storage/fonts/water-brush.css">
    <link rel="stylesheet" href="/storage/fonts/raleway.css"> --}}
    <link rel="stylesheet" href="{{ asset('elfcms/admin/fonts/roboto/roboto.css') }}">
    <link rel="stylesheet" href="{{ asset('elfcms/admin/css/style.css') }}">
    @show
</head>
<body class="system-login">
    @section('header')
    <header class="header">
        {{-- <div class="header-top">
            <x-menu.cupatea.top menu="4" />
        </div>
        <div class="header-box">
            <a href="/" class="header-title">{{ $elfSiteSettings['title'] }}</a>
            <div class="header-subtitle">{{ $elfSiteSettings['slogan'] }}</div>
        </div> --}}
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
                {{-- <nav>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/blog">Blog</a></li>
                        <li><a href="/about">About</a></li>
                    </ul>
                </nav> --}}
                {{-- <nav>
                    <ul>
                        <li><h5>Categories</h5></li>
                        <li><a href="#">Have tea alone</a></li>
                        <li><a href="#">In a good company</a></li>
                    </ul>
                </nav> --}}
                {{-- <div class="society-box">
                    <ul>
                        <li><a href="" class="facebook"></a></li>
                        <li><a href="" class="instagram"></a></li>
                        <li><a href="" class="twitter"></a></li>
                    </ul>
                </div> --}}
            </div>
            <div class="footer-copyright">
                &copy; 2020 - {{ date('Y') }} Maxim Klassen
            </div>
        </div>
    </footer>
    @show
</body>
</html>
