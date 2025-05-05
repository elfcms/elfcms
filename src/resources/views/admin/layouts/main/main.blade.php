<main>
    <div id="toppanel">
        <div class="pageinfo">
            <div class="paginfo-title">
                <h1>{{ $page['title'] ?? $pageConfig['title'] }}</h1>
            </div>
        </div>
        <div class="userinfo">
            <div class="userinfo-content">
                <div class="userinfo-content-inner">
                    <div class="userinfo-top">
                        <div class="userinfo-name">{{ $currentUser->user->name() }}</div>
                        <div class="userinfo-avatar">
                            @if ($currentUser->user->avatar)
                                <img src="{{ file_path(imgCropCache($currentUser->user->avatar, 32, 32)) }}"
                                    alt="User avatar">
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
        @if (Session::has('success'))
            <x-elf-notify type="success" title="{{ __('elfcms::default.success') }}"
                text="{!! Session::get('success') !!}" />
        @endif
        @if (Session::has('error'))
            <x-elf-notify type="error" title="{{ __('elfcms::default.error') }}"
                text="{!! Session::get('error') !!}" />
        @endif
        @if (Session::has('warning'))
            <x-elf-notify type="warning" title="{{ __('elfcms::default.warning') }}"
                text="{!! Session::get('warning') !!}" />
        @endif
        @if (Session::has('info'))
            <x-elf-notify type="info" title="{{ __('elfcms::default.info') }}"
                text="{!! Session::get('info') !!}" />
        @endif
        @if ($errors->any())
            <x-elf-notify type="error" title="{{ __('elfcms::default.error') }}" text="{!! '<ul><li>' . implode('</li><li>', $errors->all()) . '</li></ul>' !!}" />
        @endif
        @section('pagecontent')

        @show

    </div>
</main>
