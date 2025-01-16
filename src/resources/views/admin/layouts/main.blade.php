<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $page['title'] }}</title>
    @isset($elfSiteSettings['icon'])
        <link rel="shortcut icon" href="{{ asset(file_path($elfSiteSettings['icon'])) }}" type="image/x-icon">
    @endisset
    <link rel="stylesheet" href="{{ asset('elfcms/admin/fonts/roboto/roboto.css') }}">
    <link href="{{ asset('elfcms/admin/css/gnommy.min.css') }}" rel="stylesheet">
    @foreach ($admin_styles as $style)
        <link rel="stylesheet" href="{{ asset($style) }}">
    @endforeach
    <script src="{{ asset('elfcms/admin/js/gnommy.js') }}"></script>
    @foreach ($admin_scripts as $script)
        <script src="{{ asset($script) }}"></script>
    @endforeach
    @section('head')
    @show
</head>

<body>
    @inject('currentUser', 'Elfcms\Elfcms\Aux\User')
    @inject('menu', 'Elfcms\Elfcms\Aux\Admin\Menu')
    <header id="header">
        <a href="/" class="logobox">
            <div class="logoimg">
                @isset($elfSiteSettings['logo'])
                    <img src="{{ asset(file_path($elfSiteSettings['logo'])) }}" alt="logo">
                @else
                @endisset
            </div>
            <div class="logoname">
                @isset($elfSiteSettings['name'])
                    {{ $elfSiteSettings['name'] }}
                @endisset
            </div>
        </a>

        <nav id="mainmenu">
            <ul>
                @forelse ($menu::accessGet() as $item)
                    <li @if (Str::startsWith(Route::currentRouteName(), $item['parent_route'])) class="active" @endif>
                        <a href="{{ route($item['route']) }}">
                            <img src="{{ $item['icon'] }}" alt="">
                            <span>
                                @if (empty($item['lang_title']))
                                    {{ $item['title'] }}
                                @else
                                    {{ __($item['lang_title']) }}
                                @endif
                                @if (!empty($item['lang_subtitle']))
                                    <br><span>{{ __($item['lang_subtitle']) }}</span>
                                @elseif (!empty($item['subtitle']))
                                    <br><span>{{ $item['subtitle'] }}</span>
                                @endif
                            </span>
                        </a>
                        @if (!empty($item['submenu']))
                            <ul class="submenu">
                                @foreach ($item['submenu'] as $subitem)
                                    <li @if (Str::startsWith(Route::currentRouteName(), $subitem['route']) &&
                                            (empty($item['submenu'][$loop->iteration]) ||
                                                !Str::startsWith(Route::currentRouteName(), $item['submenu'][$loop->iteration]['route']))) class="active" @endif>
                                        <a href="{{ route($subitem['route']) }}">
                                            @if (empty($subitem['lang_title']))
                                                {{ $subitem['title'] }}
                                            @else
                                                {{ __($subitem['lang_title']) }}
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                    </li>
                @empty

                @endforelse

                <li class="only-mobile">
                    <a href="/admin/logout">
                        <img src="/elfcms/admin/images/icons/logout.png" alt="">
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <div id="toppanel">
            <div class="pageinfo">
                <div class="paginfo_title">
                    <h1>{{ $page['title'] }}</h1>
                </div>
            </div>
            <div class="userinfo">
                {{ $currentUser->user->name() }}
                <div class="userinfo_avatar">
                </div>
                <nav class="userdata">
                    <ul>
                        <li><a href="/admin/logout">Logout</a></li>
                    </ul>
                </nav>
            </div>
            <div class="menubutton closed"></div>
        </div>
        <div class="main-container">
            @section('pagecontent')

            @show

        </div>
    </main>
    @section('footerscript')
    @show
    <footer id="footer">
        &copy; Maxim Klassen, 2022-{{ date('Y') }}. ELF CMS v{{ config('elfcms.elfcms.version') }} @if (config('elfcms.elfcms.is_beta'))
            (beta)
        @endif
    </footer>
    <script src="{{ asset('elfcms/admin/js/app.js') }}"></script>
</body>

</html>
