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
                                <img src="{{ file_path(imgCropCache($currentUser->user->avatar, 32, 32)) }}" alt="User avatar">
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
