@extends('elfcms::admin.layouts.users')

@section('userpage-content')

    @if (Session::has('usercreated'))
        <div class="alert alert-success">{{ Session::get('usercreated') }}</div>
    @endif

@endsection
