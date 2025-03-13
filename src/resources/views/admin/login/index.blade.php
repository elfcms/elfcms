<!DOCTYPE html>
<html lang="{{ config('app.locale') ?? 'en' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELF CMS: Login</title>
    <link rel="stylesheet" href="{{ asset('elfcms/admin/fonts/afacad/afacad.css') }}">
    <link rel="stylesheet" href="{{ asset('elfcms/admin/fonts/inter/inter.css') }}">
    <link rel="stylesheet" href="{{ asset('elfcms/admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('elfcms/admin/css/login.css') }}">
</head>

<body class="dark-mode">
    <div class="welcome-bg"></div>
    <div class="cms_logo">
        @if ($elfSiteSettings['logo'])
            <img src="{{ asset(file_path($elfSiteSettings['logo'])) }}" alt="{{ $elfSiteSettings['title'] }}">
        @else
            {!! iconHtmlLocal('elfcms/admin/images/logo/logo.svg', svg: true) !!}
        @endif
    </div>
    <form action="{{ route('admin.login') }}" method="post">
        @csrf
        <div class="input-wrapper">
            <input type="email" name="email" id="email" placeholder="{{ __('elfcms::default.email') }}" autocomplete="nope">
        </div>
        <div class="input-wrapper">
            <x-elfcms::ui.input.password name="password" id="password" autocomplete="nope"
                placeholder="{{ __('elfcms::default.password') }}" />
        </div>
        <div class="button-box single-box">
            <button type="submit" name="submit" value="save" class="button color-text-button info-button wide-button">{{ __('elfcms::default.login') }}</button>
        </div>
    </form>
    @if (Session::has('success'))
        <x-elf-notify type="success" title="{{ __('elfcms::default.success') }}" text="{{ Session::get('success') }}" />
    @endif
    @if ($errors->any())
        <x-elf-notify type="error" title="{{ __('elfcms::default.error') }}" text="{!! '<ul><li>' . implode('</li><li>', $errors->all()) . '</li></ul>' !!}" />
    @endif
    @stack('footerscript')
</body>

</html>