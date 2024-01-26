@if (!empty($menu))
    <nav>
        <div id="mobile-nav-button"></div>
        <ul>
            <li @class(['active' => (request()->is('/'))])>
                <a href="/" class="header-title">{{ $elfSiteSettings['title'] ?? $slot ?? '' }}</a>
            </li>
    @if (!empty($menu->items))
        @forelse ($menu->items as $item)
            <li @class(['active' => (request()->is(trim($item->link,'/')))])>
                @if ($item->clickable)
                    <a
                        href="{{$item->link}}"
                    @if (!empty($item->attributes))
                    @forelse ($item->attributes as $key => $value)
                            {{$key}}="{{$value}}"
                    @empty
                    @endforelse
                    @endif
                    @if (!empty($item->handler))
                        onclick="{{$item->handler}}"
                    @endif
                    @if (!empty($item->title))
                        title="{{$item->title}}"
                    @endif
                    >
                        {{ $item->text }}
                    </a>
                @else
                    <span> {{ $item->text }} </span>
                @endif
            </li>
        @empty
        @endforelse
    @endif
        </ul>
    </nav>
    <script>
        const mobileNavButton = document.querySelector('#mobile-nav-button');
        if (mobileNavButton) {
            mobileNavButton.addEventListener('click', () => {
                mobileNavButton.classList.toggle('opened')
            });
        }
    </script>
@endif
