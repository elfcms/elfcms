@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="big-container">

        <div class="item-form">
            <h2>{{ __('elfcms::default.license_MIT') }}</h2>
            <div class="system-license-text">{!! $text !!}</div>
        </div>
    </div>
@endsection
