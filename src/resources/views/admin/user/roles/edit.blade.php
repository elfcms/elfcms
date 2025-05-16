@extends('elfcms::admin.layouts.users')

@section('userpage-content')

{{-- @if (Session::has('roleedited'))
    <div class="alert alert-success">{{ Session::get('roleedited') }}</div>
@endif
@if (Session::has('rolecreated'))
    <div class="alert alert-success">{{ Session::get('rolecreated') }}</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <h4>{{ __('elfcms::default.errors') }}</h4>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif --}}
<div class="user-form item-form">
    <h2>{{ __('elfcms::default.edit_role') }} #{{ $role->id }}</h2>
    <form action="{{ route('admin.user.roles.update',$role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="colored-rows-box">
            <div class="input-box colored">
                <label for="name">{{ __('elfcms::default.name') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="name" id="name" autocomplete="off" value="{{ $role->name }}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="code">{{ __('elfcms::default.code') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="code" id="code" autocomplete="off" value="{{ $role->code }}">
                </div>
                <div class="input-wrapper">
                    <x-elfcms::ui.checkbox.autoslug checked="true" textid="name" slugid="code" />
                </div>
            </div>
            <div class="input-box colored">
                <label for="description">{{ __('elfcms::default.description') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="description" id="description" autocomplete="off" value="{{ $role->description }}">
                </div>
            </div>
            @if (!empty($accessRoutes))
            <div class="input-box colored">
                <label>{{ __('elfcms::default.permissions') }}</label>
                <div class="input-wrapper">
                    <div class="user-permissions-box">
                        <div class="user-permissions-col-title">
                            {{ __('elfcms::default.sys_section') }}
                        </div>
                        <div class="user-permissions-col-title">
                            {{ __('elfcms::default.perm_read') }}
                        </div>
                        <div class="user-permissions-col-title">
                            {{ __('elfcms::default.perm_write') }}
                        </div>
                        @foreach ($accessRoutes as $module => $routes)
                            @if (!empty($routes))
                                @foreach ($routes as $routeData)
                                @empty($routeData['actions'])
                                @else
                                    <div class="user-permissions-row">
                                        {{ $routeData['title'] }}
                                    </div>
                                    <div class="user-permissions-row">
                                        @if (in_array('read', $routeData['actions']))
                                            <x-elfcms::ui.checkbox.switch
                                                name="permissions[{{ $routeData['name'] }}][read]"
                                                id="{{ $routeData['title'] }}_read"
                                                checked="{{ !empty($routeData['permissions']['read']) }}" />
                                        @endif
                                    </div>
                                    <div class="user-permissions-row">
                                        @if (in_array('write', $routeData['actions']))
                                            <x-elfcms::ui.checkbox.switch
                                                name="permissions[{{ $routeData['name'] }}][write]"
                                                id="{{ $routeData['title'] }}_write"
                                                checked="{{ !empty($routeData['permissions']['write']) }}" />
                                        @endif
                                    </div>
                                @endempty
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>

        <div class="button-box single-box">
            <button type="submit" class="button color-text-button success-button">{{ __('elfcms::default.save') }}</button>
            <a href="{{ route('admin.user.roles') }}" class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
        </div>
    </form>
</div>
<script>
autoSlug('.autoslug');
</script>
@endsection
