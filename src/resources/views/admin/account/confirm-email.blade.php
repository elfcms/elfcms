@extends('elfcms::admin.layouts.guest')

@section('head')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('main')
<div class="login-form">
    <h1>{{ $page['title'] ?? $elfSiteSettings['title'] }}</h1>
    <div class="message-box">
    @if(session('error'))
        <div class="alert alert-warning">{{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    </div>
</div>
@endsection
