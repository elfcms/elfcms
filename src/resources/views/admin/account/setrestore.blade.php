@extends('elfcms::admin.layouts.guest')

@section('head')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('main')
    <div class="login-form">
    <h1>{{ $page['title'] ?? $elfSiteSettings['title'] }}</h1>
    @if (Session::has('passwordchangesuccess'))
        <div class="message-box">
            <div class="alert alert-success">{{ Session::get('passwordchangesuccess') }}</div>
            <div class="button-box single-box">
                <a href="{{ route('admin.login') }}" class="forgot-pass-link">{{ __('elfcms::default.login') }}</a>
            </div>
        </div>
    @elseif ($linkError)
        <div class="alert alert-danger">
            {{ $linkErrorText }}
        </div>
    @else
        <x-elfcms-account-setrestore template="admin" :token="$token" />
    @endif
</div>

@endsection
