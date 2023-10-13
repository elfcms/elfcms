

@extends('elfcms::admin.layouts.guest')

@section('head')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('main')
    <div class="login-form">
    <h1>{{ $page['title'] ?? $elfSiteSettings['title'] }}</h1>
    {{-- <div class="container">
        @if (Session::has('toemailconfirm'))
            <div class="alert alert-success">{{ Session::get('toemailconfirm') }}</div>
        @endif
    </div> --}}
    <form action="{{ route('admin.login') }}" method="post" class="">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="errors-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @csrf
        @method('POST')
        <div class="input-box text-box">
            <div class="input-wrapper">
                <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('elfcms::default.email') }}" required>
                <label for="email">{{ __('elfcms::default.email') }}</label>
            </div>
        </div>
        <div class="input-box text-box">
            <div class="input-wrapper">
                <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('elfcms::default.password') }}" required autocomplete="off">
                <label for="password">{{ __('elfcms::default.password') }}</label>
            </div>
        </div>
        <div class="input-box">
            <div class="checkbox-switch-wrapper">
                <div class="checkbox-switch darkblue">
                    <input type="checkbox" name="remember" id="remember" value="1">
                    <i></i>
                </div>
                <label for="remember">
                    {{ __('elfcms::default.remember_me') }}
                </label>
            </div>
            {{-- <div class="checkbox-wrapper">
                <div class="checkbox-inner">
                    <input type="checkbox" name="remember" id="remember">
                    <i></i>
                    <label for="remember">
                        {{ __('elfcms::default.remember_me') }}
                    </label>
                </div>
            </div> --}}
        </div>
        <div class="button-box single-box">
            <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.login') }}</button>
            <a href="{{ route('admin.getrestore') }}" class="forgot-pass-link">{{ __('elfcms::default.restore_password') }}</a>
        </div>
    </form>
</div>
<script>
const form = document.querySelector('form');
if (form) {
    form.addEventListener('submit',function(){
        let startPreload = preloadSet(document.body);
        setTimeout(() => {
            preloadUnset(startPreload);
        }, 20000);
    });
}

function preloadSet(element) {
    if (typeof element === 'string') {
        element = document.querySelector(element);
    }
    if (!(element instanceof HTMLElement) && element !== document) {
        return false;
    }
    const preloader = document.createElement('div')
    preloader.classList.add('preload-wrapper');
    preloader.insertAdjacentHTML('beforeend','<div class="preload-box"><div></div><div></div><div></div></div>');
    element.append(preloader);

    return preloader;
}

function preloadUnset(preloader) {
    if (typeof preloader === 'string') {
        preloader = document.querySelector(preloader);
    }
    if (!(preloader instanceof HTMLElement)) {
        return false;
    }
    preloader.remove();
}
</script>
@endsection
