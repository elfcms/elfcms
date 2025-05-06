<nav aria-label="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
        @if (!empty($breadcrumbs))
            @if ($breadcrumbs[0]['url'] != '/' && $breadcrumbs[0]['url'] != url('/') && !empty($sethome))
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="{{ url('/') }}" itemprop="item">
                    <span itemprop="name">{{ is_string($sethome) ? $sethome : 'Home' }}</span>
                </a>
                <meta itemprop="position" content="0" />
            </li>
            @endif
            @foreach ($breadcrumbs as $breadcrumb)
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    @if ($loop->last || !empty($breadcrumb['current']))
                        <span itemprop="name">{{ $breadcrumb['name'] }}</span>
                        <meta itemprop="item" content="{{ $breadcrumb['url'] }}" />
                    @else
                        <a href="{{ $breadcrumb['url'] }}" itemprop="item">
                            <span itemprop="name">{{ $breadcrumb['name'] }}</span>
                        </a>
                    @endif
                    <meta itemprop="position" content="{{ $loop->iteration }}" />
                </li>
            @endforeach
        @endif
    </ol>
</nav>