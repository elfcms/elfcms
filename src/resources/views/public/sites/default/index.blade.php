@extends('public.sites.default.layouts.main')

@section('head')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('main')
    <h1>{{ $page['title'] ?? $elfSiteSettings['title'] ?? '' }}</h1>
    {{ $pageData->content }}
@endsection
