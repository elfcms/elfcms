<header id="header">
    <a href="/" class="logobox" target="_blank">
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
                    'active' =>
                        Str::startsWith($currentRoute, $item['parent_route']),
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
                                <li @if (Str::startsWith($currentRoute, $subitem['route']) &&
                                        (empty($item['submenu'][$loop->iteration]) ||
                                            !Str::startsWith($currentRoute, $item['submenu'][$loop->iteration]['route']))) class="active" @endif>
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

            {{-- <li class="only-mobile">
                <a href="{{ $adminPath }}/logout">
                    <img src="/elfcms/admin/images/icons/logout.png" alt="">
                    <span>Logout</span>
                </a>
            </li> --}}
        </ul>
    </nav>
    <div class="mode-switch-box">
        <div class="mode-switc-icon mode-switch-dark">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/dark_mode.svg', width: 24, height: 24, svg: true) !!}
        </div>
        <div class="switchbox double-color"
            style="--switch-color:var(--gold-color);--switch-color-alt:var(--blue-color)">
            <input type="checkbox" name="color-mode" id="color-mode" value="1" {{-- @if ($setting['value'] && $setting['value'] == 1) checked @endif --}}>
            <i></i>
        </div>
        <div class="mode-switc-icon mode-switch-light">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/light_mode.svg', width: 24, height: 24, svg: true) !!}
        </div>
    </div>
    @push('footerscript')
        <script>
            const modeSwitch = document.querySelector('input[name="color-mode"]');
            if (modeSwitch) {
                modeSwitch.addEventListener('change', function() {
                    if (modeSwitch.checked) {
                        localStorage.setItem('colorMode', 'light');
                        document.body.classList.remove('dark-mode');
                    } else {
                        document.body.classList.add('dark-mode');
                        localStorage.setItem('colorMode', 'dark');
                    }
                });
            }
            if (localStorage.getItem('colorMode') == 'dark') {
                document.body.classList.add('dark-mode');
                if (modeSwitch) modeSwitch.checked = false;
            } else {
                document.body.classList.remove('dark-mode');
                if (modeSwitch) modeSwitch.checked = true;
            }
        </script>
    @endpush
</header>
