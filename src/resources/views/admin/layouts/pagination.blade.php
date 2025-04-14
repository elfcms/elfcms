@if ($paginator->hasPages())
    <nav class="pagination-wrapper">

        <ul>
            @if ($paginator->onFirstPage())
                <li class="disabled"><span class="pagination-backward">
                        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/navigate_before.svg', svg: true) !!}
                    </span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" class="pagination-backward">
                        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/navigate_before.svg', svg: true) !!}
                    </a></li>
            @endif
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" class="pagination-forward">
                        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/navigate_next.svg', svg: true) !!}
                    </a></li>
            @else
                <li class="disabled"><span class="pagination-forward">
                        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/navigate_next.svg', svg: true) !!}
                    </span></li>
            @endif
        </ul>

    </nav>
@endif
