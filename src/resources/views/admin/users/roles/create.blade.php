@extends('elfcms::admin.layouts.users')

@section('userpage-content')

@if ($errors->any())
<div class="alert alert-danger">
    <h4>{{ __('elfcms::default.errors') }}</h4>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="user-form item-form">
    <h3>{{ __('elfcms::default.create_new_role') }}</h3>
    <form action="{{ route('admin.users.roles.store') }}" method="POST">
        @csrf
        <div class="colored-rows-box">
            <div class="input-box colored">
                <label for="name">{{ __('elfcms::default.name') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="name" id="name" autocomplete="off">
                </div>
            </div>
            <div class="input-box colored">
                <label for="code">{{ __('elfcms::default.code') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="code" id="code" autocomplete="off">
                </div>
                <div class="input-wrapper">
                    <div class="autoslug-wrapper">
                        <input type="checkbox" data-text-id="name" data-slug-id="code" class="autoslug" checked>
                        <div class="autoslug-button"></div>
                    </div>
                </div>
            </div>
            <div class="input-box colored">
                <label for="description">{{ __('elfcms::default.description') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="description" id="description" autocomplete="off">
                </div>
            </div>

        </div>

        <div class="button-box single-box">
            <button type="submit" class="default-btn success-button">{{ __('elfcms::default.submit') }}</button>
            <a href="{{ route('admin.users.roles') }}" class="default-btn">{{ __('elfcms::default.cancel') }}</a>
        </div>
    </form>
</div>
<script>
autoSlug('.autoslug');
</script>
@endsection
