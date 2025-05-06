<nav aria-label="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
    <ol class="breadcrumb">
        {{-- <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="{{ url($page->path) }}" itemprop="item">
                <span itemprop="name">{{ $page->name }}</span>
            </a>
            <meta itemprop="position" content="1" />
        </li>

        @php $position = 2; @endphp
 --}}
        @if (!empty($breadcrumbs))
            @foreach ($breadcrumbs as $breadcrumb)
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    @if ($loop->last)
                        <span itemprop="name">{{ $breadcrumb['name'] }}</span>
                        <meta itemprop="item" content="{{ $breadcrumb['url'] }}" />
                    @else
                        <a href="{{ $breadcrumb['url'] }}" itemprop="item">
                            <span itemprop="name">{{ $breadcrumb['name'] }}</span>
                        </a>
                    @endif
                    <meta itemprop="position" content="{{ $position++ }}" />
                </li>
            @endforeach
        @endif
    </ol>
</nav>