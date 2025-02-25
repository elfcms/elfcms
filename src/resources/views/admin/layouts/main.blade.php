<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $page['title'] }}</title>
    @push('styles')
        @isset($elfSiteSettings['icon'])
            <link rel="shortcut icon" href="{{ asset(file_path($elfSiteSettings['icon'])) }}" type="image/x-icon">
        @endisset
        <link rel="stylesheet" href="{{ asset('elfcms/admin/fonts/afacad/afacad.css') }}">
        {{-- <link rel="stylesheet" href="{{ asset('elfcms/admin/fonts/teachers/teachers.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('elfcms/admin/fonts/inter/inter.css') }}">
        <link rel="stylesheet" href="{{ asset('elfcms/admin/css/gnommy.min.css') }}">
        <link rel="stylesheet" href="{{ asset('elfcms/admin/notify/notify.css') }}">
        @foreach ($admin_styles as $style)
            <link rel="stylesheet" href="{{ asset($style) }}">
        @endforeach
    @endpush
    @stack('styles')
    @push('scripts')
        <script src="/js/system.js"></script>
        <script src="{{ asset('elfcms/admin/js/gnommy.js') }}"></script>
        <script src="{{ asset('elfcms/admin/notify/notify.js') }}"></script>
        @foreach ($admin_scripts as $script)
            <script src="{{ asset($script) }}"></script>
        @endforeach
    @endpush
    @stack('scripts')

    @section('head')
    @show
</head>

<body class="dark-scheme" style="--default-color:{{ $pageConfig['color'] ?? 'transparent' }}">
    @inject('currentUser', 'Elfcms\Elfcms\Aux\User')
    @inject('menu', 'Elfcms\Elfcms\Aux\Admin\Menu')
    @include('elfcms::admin.layouts.main.header')
    @include('elfcms::admin.layouts.main.main')
    @include('elfcms::admin.layouts.main.footer')
    @section('footerscript')
    @show
    @stack('footerscript')
    <script src="{{ asset('elfcms/admin/js/app.js') }}"></script>
</body>

</html>
