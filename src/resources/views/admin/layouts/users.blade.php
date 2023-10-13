@extends('elfcms::admin.layouts.main')

@section('pagecontent')

<div class="big-container">

    {{-- <nav class="subpagenav">
        <ul>
            <li><a href="{{ route('admin.users') }}">{{__('elfcms::default.users_list')}}</a></li>
            <li><a href="{{ route('admin.users.create') }}">{{__('elfcms::default.create_new_user')}}</a></li>
            <li><a href="{{ route('admin.users.roles') }}">{{__('elfcms::default.roles_list')}}</a></li>
            <li><a href="{{ route('admin.users.roles.create') }}">{{__('elfcms::default.create_new_role')}}</a></li>
        </ul>
    </nav> --}}
    @section('userpage-content')
    @show

</div>
@endsection
