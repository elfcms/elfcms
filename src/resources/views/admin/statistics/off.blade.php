@extends('elfcms::admin.layouts.main')

@section('pagecontent')

    <div class="alert alert-alternate">
        {{ __('elfcms::default.the_use_of_statistics_is_disabled') }}
    </div>
{{-- <x-elf-notify type="error" title="{{ __('elfcms::default.error') }}" text="{{ __('elfcms::default.the_use_of_statistics_is_disabled') }}" /> --}}

@endsection
