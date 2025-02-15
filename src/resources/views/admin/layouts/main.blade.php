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
    <link rel="stylesheet" href="{{ asset('elfcms/admin/fonts/afacad/afacad.css') }}">
    <link rel="stylesheet" href="{{ asset('elfcms/admin/fonts/teachers/teachers.css') }}">
    <link rel="stylesheet" href="{{ asset('elfcms/admin/fonts/inter/inter.css') }}">
    <link href="{{ asset('elfcms/admin/css/gnommy.min.css') }}" rel="stylesheet">
    @foreach ($admin_styles as $style)
        <link rel="stylesheet" href="{{ asset($style) }}">
    @endforeach
    <script src="/js/system.js"></script>
    <script src="{{ asset('elfcms/admin/js/gnommy.js') }}"></script>
    @foreach ($admin_scripts as $script)
        <script src="{{ asset($script) }}"></script>
    @endforeach
    @section('head')
    @show
</head>

<body class="dark-scheme">
    @inject('currentUser', 'Elfcms\Elfcms\Aux\User')
    @inject('menu', 'Elfcms\Elfcms\Aux\Admin\Menu')
    <header id="header">
        <a href="/" class="logobox">
            <div class="logoimg">
                @isset($elfSiteSettings['logo'])
                    <img src="{{ asset(file_path($elfSiteSettings['logo'])) }}" alt="logo">
                @else
                    <div class="logoimg-empty"></div>
                @endisset
            </div>
            <div class="logoname">
                {{-- @isset($elfSiteSettings['name'])
                    {{ $elfSiteSettings['name'] ?? 'Website' }}
                @endisset --}}
                {{ $elfSiteSettings['name'] ?? 'Website' }}
            </div>
        </a>

        <nav id="mainmenu">
            <ul>
                @forelse ($menu::accessGet() as $item)
                    <li @class([
                        'active' => Str::startsWith(
                            Route::currentRouteName(),
                            $item['parent_route']),
                    ]) style="--i-color:{{ $item['color'] }}">
                        <a href="{{ route($item['route']) }}">
                            {{--  <img src="{{ $item['icon'] }}" alt=""> --}}
                            <div class="menu-icon">
                                {!! iconHtmlLocal($item['icon'], 24, 24, true) !!}
                            </div>
                            <div class="menu-title">{{ $item['title'] }}</div>
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
                    <a href="{{ $adminPath }}/logout">
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
                <div class="paginfo-title">
                    <h1>{{ $page['title'] }}</h1>
                </div>
            </div>
            <div class="userinfo">
                <div class="userinfo-content">
                    <div class="userinfo-content-inner">
                        <div class="userinfo-top">
                            <div class="userinfo-name">{{ $currentUser->user->name() }}</div>
                            <div class="userinfo-avatar">
                                @if ($currentUser->user->avatar)
                                    <img src="{{ $currentUser->user->avatar }}" alt="User avatar">
                                @else
                                    {!! iconHtmlLocal('/elfcms/admin/images/icons/user.svg', svg: true) !!}
                                @endif
                            </div>
                        </div>
                        <nav class="userdata">
                            <ul>
                                <li class="logout"><a href="{{ $adminPath }}/logout">Logout</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
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
        ELF CMS v{{ config('elfcms.elfcms.version') }}
        @if (config('elfcms.elfcms.is_dev'))
            (dev)
        @elseif (config('elfcms.elfcms.is_alpha'))
            (alpha)
        @elseif (config('elfcms.elfcms.is_beta'))
            (beta)
        @endif
        | &copy; M.Klassen, 2022-{{ date('Y') }}.
    </footer>
    <script src="{{ asset('elfcms/admin/js/app.js') }}"></script>
</body>

</html>
