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
    <link rel="stylesheet" href="/fonts/roboto.css">
    <link rel="stylesheet" href="/css/style.css">
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
                <x-elfcms-menu menu="social"></x-elfcms-menu>
                <x-elfcms-menu menu="bottom"></x-elfcms-menu>
            </div>
            <div class="footer-copyright">
                <a href="https://elfweb.de" target="_blank" rel="noopener noreferrer"><img src="https://cdn.elfweb.de/logos/logotext_dark_25.png" alt="ELF Webagentur"></a>
            </div>
        </div>
    </footer>
    @show
</body>
</html>
