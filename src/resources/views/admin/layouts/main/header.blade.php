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
