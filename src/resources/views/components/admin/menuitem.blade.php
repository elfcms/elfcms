@if (!empty($item) && !empty($menu))
<div class="menu-item-box" data-id="{{ $item->id }}">
    <div @class(['menu-item-position', 'inactive' => $item->active != 1]) draggable="true" data-title="{{ $item->title ?? $item->name }}">
        {{ $item->position }}
    </div>
    <div class="menu-item-data">
        <div class="menu-item-title-box">
            <div class="menu-item-title">
            @if ($item->active != 1)
                <span class="menu-item-inactive-title">[{{ __('elfcms::default.inactive') }}]</span>
            @endif
                {{ $item->text ?? $item->title ?? $item->link }}
                <a href="{{ $item->link }}" class="menu-item-link" target="_blank">{{ $item->link }}</a>
            </div>
            <div class="menu-item-button-box">
                <a href="{{ route('admin.menus.items.edit', ['item'=>$item,'menu'=>$menu]) }}" class="inline-button circle-button alternate-button" title="{{ __('elfcms::default.edit') }}"></a>
                <form action="{{ route('admin.menus.items.destroy', ['item'=>$item,'menu'=>$menu]) }}" method="POST" data-submit="check">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{ $item->id }}">
                    <input type="hidden" name="name" value="{{ $item->title ?? $item->name }}">
                    <button type="submit" class="inline-button circle-button delete-button" title="{{ __('elfcms::default.delete') }}"></button>
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
            <a href="{{ route('admin.menus.items.create',['menu'=>$menu, 'item'=>$item->id]) }}" class="button success-button icon-text-button light-icon plus-button"></a>
        </div>
    </div>
</div>
@endif
