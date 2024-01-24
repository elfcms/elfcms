@extends('elfcms::public.layouts.main')

@section('head')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('main')
    <div class="page-line-box">
        2222222
        <h1>{{ $page['title'] ?? $elfSiteSettings['title'] ?? '' }}</h1>
        <div class="content-box">
            {!! $pageData->html ?? $pageData->content !!}
        </div>
    </div>
@endsection
