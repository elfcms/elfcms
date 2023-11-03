@extends('elfcms::admin.layouts.guest')

@section('head')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('main')
<div class="login-form">
    <h1>{{ $page['title'] ?? $elfSiteSettings['title'] }}</h1>
        <x-elfcms-account-getrestore template="admin" />
</div>

@endsection
