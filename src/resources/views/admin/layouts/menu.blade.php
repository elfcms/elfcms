@extends('elfcms::admin.layouts.main')

@section('pagecontent')

<div class="big-container">

    <nav class="pagenav">
        <ul>
            <li>
                <a href="{{ route('admin.menu.menus') }}" class="button button-left">{{ __('elfcms::default.menu') }}</a>
                <a href="{{ route('admin.menu.menus.create') }}" class="button button-right">+</a>
            </li>
            <li>
                <a href="{{ route('admin.menu.items') }}" class="button button-left">{{ __('elfcms::default.menu_items') }}</a>
                <a href="{{ route('admin.menu.items.create') }}" class="button button-right">+</a>
            </li>
        </ul>
    </nav>
    @section('menupage-content')
    @show

</div>
@endsection
