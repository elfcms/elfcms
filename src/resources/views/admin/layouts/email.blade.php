@extends('elfcms::admin.layouts.main')

@section('pagecontent')

<div class="big-container">

    {{-- <nav class="pagenav">
        <ul>
            <li>
                <a href="{{ route('admin.email.addresses') }}" class="button button-left">{{ __('elfcms::default.email_addresses') }}</a>
                <a href="{{ route('admin.email.addresses.create') }}" class="button button-right">+</a>
            </li>
            <li>
                <a href="{{ route('admin.email.events') }}" class="button button-left">{{ __('elfcms::default.email_events') }}</a>
                <a href="{{ route('admin.email.events.create') }}" class="button button-right">+</a>
            </li>
        </ul>
    </nav> --}}
    @section('emailpage-content')
    @show

</div>
@endsection
