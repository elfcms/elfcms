@extends('elfcms::admin.layouts.main')

@section('pagecontent')

    <div class="item-form">
        <h2>{{ __('elfcms::default.edit_menu') }} #{{ $menu->id }}</h2>
        <form action="{{ route('admin.menus.update',$menu->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off" value="{{ $menu->name }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="code">{{ __('elfcms::default.code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="code" id="code" autocomplete="off" value="{{ $menu->code }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="description">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="description" id="description" autocomplete="off" value="{{ $menu->description }}">
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" name="submit" value="save"
                    class="button color-button green-button">{{ __('elfcms::default.submit') }}</button>
                <button type="submit" name="submit" value="save_and_open"
                    class="button color-button blue-button">{{ __('elfcms::default.save_and_open') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-button blue-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.menus.index') }}" class="button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>

@endsection
