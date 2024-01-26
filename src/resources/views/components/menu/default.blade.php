@if (!empty($menu))
    <nav>
        <ul>
    @if (!empty($menu->items))
        @forelse ($menu->items as $item)
            <li>
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
@endif
