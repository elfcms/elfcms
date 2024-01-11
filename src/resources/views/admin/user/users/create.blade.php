@extends('elfcms::admin.layouts.users')

@section('userpage-content')

@error('email')
<div class="alert alert-danger">{{ $message }}</div>
@enderror
@error('password')
<div class="alert alert-danger">{{ $message }}</div>
@enderror
<div class="user-form item-form">
    <h3>{{ __('elfcms::default.create_new_user') }}</h3>
    <form action="{{ route('admin.user.users.store') }}" method="POST">
        @csrf
        <div class="colored-rows-box">
            <div class="input-box colored">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <input type="email" name="email" id="email" autocomplete="off">
                </div>
            </div>
            <div class="input-box colored">
                <label for="password">{{ __('elfcms::default.password') }}</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" autocomplete="off">
                </div>
            </div>
            <div class="input-box colored">
                <label for="password_confirmation">{{ __('elfcms::default.confirm_password') }}</label>
                <div class="input-wrapper">
                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="off">
                </div>
            </div>

            <div class="input-box colored">
                <div class="checkbox-wrapper">
                    <div class="checkbox-switch-wrapper">
                        <div class="checkbox-switch blue">
                            <input type="checkbox" name="is_confirmed" id="is_confirmed" value="1" checked>
                            <i></i>
                        </div>
                        <label for="is_confirmed">
                            {{ __('elfcms::default.confirmed') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="roles-list">
            <h4>{{ __('elfcms::default.roles') }}</h4>
            @foreach ($roles as $role)
            <div class="input-box">
                <div class="checkbox-wrapper">
                    <div class="checkbox-switch-wrapper">
                        <div class="checkbox-switch blue">
                            <input type="checkbox" name="role[]" id="role_{{ $role->id }}" value="{{ $role->id }}" @if ($role->code == $default_role_code) checked @endif>
                            <i></i>
                        </div>
                        <label for="role_{{ $role->id }}">
                            {{ $role->name }}
                        </label>
                    </div>
                </div>
                {{-- <div class="checkbox-wrapper low-wrapper">
                    <div class="checkbox-inner">
                        <input
                            type="checkbox"
                            name="role[]"
                            id="role_{{ $role->id }}"
                            value="{{ $role->code }}"
                            @if ($role->code == $default_role_code)
                            checked
                            @endif
                        >
                        <i></i>
                        <label for="role_{{ $role->id }}">
                            {{ $role->name }}
                        </label>
                    </div>
                </div> --}}
            </div>
            @endforeach
        </div>
        <div class="button-box single-box">
            <button type="submit" class="default-btn success-button">{{ __('elfcms::default.submit') }}</button>
            <a href="{{ route('admin.user.users') }}" class="default-btn">{{ __('elfcms::default.cancel') }}</a>
        </div>
    </form>
</div>
<script>
    const cb = document.querySelector('#is_confirmed')
    if (cb) {
        cb.addEventListener('click',function(){
            this.value
        })
    }
</script>
@endsection
