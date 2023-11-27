@extends('elfcms::admin.layouts.menu')

@section('menupage-content')

    @if (Session::has('success'))
    <div class="alert alert-alternate">{{ Session::get('success') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="table-search-box">
        <a href="{{ route('admin.menus.items.create',$menu) }}" class="default-btn success-button icon-text-button light-icon plus-button">{{__('elfcms::default.create_menu_item')}}</a>
    </div>
    <div class="menu-items" data-menu="{{ $menu->id }}">
        @foreach ($menu->topitems as $item)
        <x-elfcms::admin.menuitem :item="$item" :menu="$menu" />
        @endforeach
    </div>

@endsection
@section('footerscript')
<script src="{{ asset('elfcms/admin/js/popnotifi.js') }}"></script>
<script src="{{ asset('elfcms/admin/js/menuitemorder.js') }}"></script>
@endsection
