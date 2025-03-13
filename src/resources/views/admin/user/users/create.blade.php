@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    {{-- @if (Session::has('success'))
        <x-elf-notify type="success" title="{{ __('elfcms::default.success') }}" text="{{ Session::get('success') }}" />
    @endif
    @if ($errors->any())
        <x-elf-notify type="error" title="{{ __('elfcms::default.error') }}" text="{!! '<ul><li>' . implode('</li><li>', $errors->all()) . '</li></ul>' !!}" />
    @endif --}}
<div class="user-form item-form">
    <h2>{{ __('elfcms::default.create_new_user') }}</h2>
    <form action="{{ route('admin.user.users.store') }}" method="POST">
        @csrf
        <div class="colored-rows-box">
            <div class="input-box colored">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <input type="email" name="email" id="email" value="{{ old('email') }}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="password">{{ __('elfcms::default.password') }}</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" value="">
                </div>
            </div>
            <div class="input-box colored">
                <label for="password_confirmation">{{ __('elfcms::default.confirm_password') }}</label>
                <div class="input-wrapper">
                    <input type="password" name="password_confirmation" id="password_confirmation" value="">
                </div>
            </div>

            <div class="input-box colored">
                <label for="is_confirmed">
                    {{ __('elfcms::default.confirmed') }}
                </label>
                <div class="input-wrapper">
                    <x-elfcms::ui.checkbox.switch name="is_confirmed" id="is_confirmed" checked="{{!empty(old('is_confirmed'))}}" />
                </div>
            </div>
        </div>

        <div class="roles-list">
            <h4>{{ __('elfcms::default.roles') }}</h4>
            @foreach ($roles as $role)
            <div class="input-box">
                <label for="role_{{ $role->id }}">
                    {{ $role->name }}
                </label>
                <div class="input-wrapper">
                    <x-elfcms::ui.checkbox.switch name="role[]" id="role_{{ $role->id }}" checked="{{$role->code == $default_role_code}}" />
                </div>
            </div>
            @endforeach
        </div>
        <div class="button-box single-box">
            <button type="submit" class="button color-text-button success-button">{{ __('elfcms::default.submit') }}</button>
            <a href="{{ route('admin.user.users') }}" class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
