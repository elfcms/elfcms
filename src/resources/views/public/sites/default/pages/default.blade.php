@extends('public.layouts.main')

@section('head')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('main')
<div class="page-line-box">
    <div class="container">
        <h1>{{ $page['title'] ?? $elfSiteSettings['title'] ?? '' }}</h1>
        @if (!empty($pageData->image))
        <div class="image-box">
            <img src="{{ $pageData->image }}" alt="{{ $pageData->browser_title ?? $pageData->name ?? '' }}">
        </div>
        @endif
        <div class="content-box">
            {!! $pageData->html ?? $pageData->content !!}
        </div>
    </div>
</div>
@endsection
