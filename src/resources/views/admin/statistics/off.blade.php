@extends('elfcms::admin.layouts.main')

@section('pagecontent')

<div class="big-container">
    @if (Session::has('settingedited'))
        <div class="alert alert-success">{{ Session::get('settingedited') }}</div>
    @endif
    @if (Session::has('settingcreated  '))
        <div class="alert alert-success">{{ Session::get('settingcreated') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="errors-list">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif
    <div class="alert alert-alternate">
        {{ __('elfcms::default.the_use_of_statistics_is_disabled') }}
    </div>
</div>

@endsection
