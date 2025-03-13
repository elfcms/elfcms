@if (!empty($item) && !empty($menu))
    <div class="menu-item-box" data-id="{{ $item->id }}">
        <div @class(['menu-item-position', 'inactive' => $item->active != 1]) draggable="true" data-title="{{ $item->title ?? $item->name }}">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/drag_indicator_slim.svg', svg: true) !!}
            <span>{{ $item->position }}</span>
        </div>
        <div class="menu-item-data">
            <div class="menu-item-title-box">
                <div class="menu-item-title">
                    @if ($item->active != 1)
                        <span class="menu-item-inactive-title">[{{ __('elfcms::default.inactive') }}]</span>
                    @endif
                    {{ $item->text ?? ($item->title ?? $item->link) }}
                    <a href="{{ $item->link }}" class="menu-item-link" target="_blank">{{ $item->link }}</a>
                </div>
                <div class="menu-item-button-box">
                    <a href="{{ route('admin.menus.items.edit', ['item' => $item, 'menu' => $menu]) }}"
                        class="button icon-button" title="{{ __('elfcms::default.edit') }}">
                        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
                    </a>
                    <form action="{{ route('admin.menus.items.destroy', ['item' => $item, 'menu' => $menu]) }}"
                        method="POST" data-submit="check">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <input type="hidden" name="name" value="{{ $item->title ?? $item->name }}">
                        <button type="submit" class="button icon-button icon-alarm-button"
                            title="{{ __('elfcms::default.delete') }}">
                            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/delete.svg', svg: true) !!}
                        </button>
                    </form>
                </div>
            </div>
            <div class="menu-item-subitems">
                @if (!empty($item->subitems))
                    @foreach ($item->subitems as $subitem)
                        <x-elfcms::admin.menuitem :item="$subitem" :menu="$menu" />
                    @endforeach
                @endif
                <div class="menu-not-item"></div>
            </div>
            <div class="menu-item-buttons">
                <a href="{{ route('admin.menus.items.create', ['menu' => $menu, 'item' => $item->id]) }}"
                    class="button round-button theme-button">
                    {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
                    <span class="button-collapsed-text">
                        {{ __('elfcms::default.create_menu') }}
                    </span>
                </a>
            </div>
        </div>
    </div>
@endif
