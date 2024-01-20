<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    @section('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ !empty($page['title']) ? $page['title'].' | ' : '' }} {{ $elfSiteSettings['title'] }}</title>
    @isset($elfSiteSettings['icon'])
    <link rel="shortcut icon" href="{{ asset($elfSiteSettings['icon']) }}" type="image/x-icon">
    @endisset
    @isset($elfSiteSettings['keywords'])
    <meta name="keywords" content="{{ $page['keywords'] ?? $elfSiteSettings['keywords'] }}">
    @endisset
    @isset($elfSiteSettings['description'])
    <meta name="description" content="{{ $page['description'] ?? $elfSiteSettings['description'] }}">
    @endisset
    <link rel="stylesheet" href="/vendor/elfcms/basic/fonts/raleway/raleway.css">
    <link rel="stylesheet" href="/vendor/elfcms/basic/fonts/roboto/roboto.css">
    <link rel="stylesheet" href="/vendor/elfcms/basic/css/style.css">
    {!! $chatbox_include ?? '' !!}
    @show
</head>
<body>
    @section('header')
    <header class="header">
        <div class="header-top">
            <x-elfcms-menu menu="top"></x-elfcms-menu>
        </div>
        <div class="header-box">
            <div class="header-title-box">
                <a href="/" class="header-title">{{ $elfSiteSettings['title'] }}</a>
                <div class="header-subtitle">{{ $elfSiteSettings['slogan'] }}</div>
            </div>
        </div>
    </header>
    @show

    <main class="main">
        <div class="main-content-box">
    @section('main')
    @show
        </div>
    </main>

    @section('footer')
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-navigation">
                <div class="society-box">
                    <ul>
                        <li><a href="#" class="facebook"></a></li>
                        <li><a href="#" class="instagram"></a></li>
                        <li><a href="#" class="twitter"></a></li>
                    </ul>
                </div>
                <nav>
                    <ul>
                        <li><a href="/privacy">{{ __('elfcms::default.privacy_policy') }}</a></li>
                        <li><a href="/impressum">Impressum</a></li>
                    </ul>
                </nav>
            </div>
            <div class="footer-copyright">
                &copy; 2022 Maxim Klassen
            </div>
        </div>
    </footer>
    {!! $chatbox_init ?? '' !!}
    @show
</body>
</html>
