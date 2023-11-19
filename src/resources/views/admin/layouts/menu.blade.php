@extends('elfcms::admin.layouts.main')

@section('pagecontent')

<div class="big-container">

    {{-- <nav class="pagenav">
        <ul>
            <li>
                <a href="{{ route('admin.menus.menus') }}" class="button button-left">{{ __('elfcms::default.menu') }}</a>
                <a href="{{ route('admin.menus.menus.create') }}" class="button button-right">+</a>
            </li>
            <li>
                <a href="{{ route('admin.menus.items') }}" class="button button-left">{{ __('elfcms::default.menu_items') }}</a>
                <a href="{{ route('admin.menus.items.create') }}" class="button button-right">+</a>
            </li>
        </ul>
    </nav> --}}
    @section('menupage-content')
    @show

</div>
@endsection
