@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    {{-- @if (Session::has('success'))
        <x-elf-notify type="success" title="{{ __('elfcms::default.success') }}" text="{{ Session::get('success') }}" />
    @endif
    @if ($errors->any())
        <x-elf-notify type="error" title="{{ __('elfcms::default.error') }}" text="{!! '<ul><li>' . implode('</li><li>', $errors->all()) . '</li></ul>' !!}" />
    @endif --}}

    <div class="item-form">
        <h2>{{ __('elfcms::default.edit_email_address') }} #{{ $address->id }}</h2>
        <form action="{{ route('admin.email.addresses.update',$address->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off" value="{{ $address->name }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="email">{{ __('elfcms::default.email_address') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="email" id="email" autocomplete="off" value="{{ $address->email }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="description">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="description" id="description" autocomplete="off" value="{{ $address->description }}">
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="button color-button green-button">{{ __('elfcms::default.submit') }}</button>
                <a href="{{ route('admin.email.addresses') }}" class="button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>

@endsection
