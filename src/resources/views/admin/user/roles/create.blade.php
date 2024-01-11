@extends('elfcms::admin.layouts.users')

@section('userpage-content')

@if ($errors->any())
<div class="alert alert-danger">
    <h4>{{ __('elfcms::default.errors') }}</h4>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="user-form item-form">
    <h3>{{ __('elfcms::default.create_new_role') }}</h3>
    <form action="{{ route('admin.user.roles.store') }}" method="POST">
        @csrf
        <div class="colored-rows-box">
            <div class="input-box colored">
                <label for="name">{{ __('elfcms::default.name') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="name" id="name" autocomplete="off">
                </div>
            </div>
            <div class="input-box colored">
                <label for="code">{{ __('elfcms::default.code') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="code" id="code" autocomplete="off">
                </div>
                <div class="input-wrapper">
                    <div class="autoslug-wrapper">
                        <input type="checkbox" data-text-id="name" data-slug-id="code" class="autoslug" checked>
                        <div class="autoslug-button"></div>
                    </div>
                </div>
            </div>
            <div class="input-box colored">
                <label for="description">{{ __('elfcms::default.description') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="description" id="description" autocomplete="off">
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
                                    <div class="user-permissions-row">
                                        {{ $routeData['title'] }}
                                    </div>
                                    @empty($routeData['actions'])
                                    <div class="user-permissions-row"></div>
                                    <div class="user-permissions-row"></div>
                                    @else
                                        @if (in_array('read',$routeData['actions']))
                                        <div class="checkbox-switch-wrapper user-permissions-row">
                                            <div class="checkbox-switch blue">
                                                <input
                                                    type="checkbox"
                                                    name="permissions[{{ $routeData['name'] }}][read]"
                                                    id="{{ $routeData['title'] }}_read"
                                                    value="1"
                                                    @if (!empty($routeData['permissions']['read']))
                                                        checked
                                                    @endif
                                                />
                                                <i></i>
                                            </div>
                                        </div>
                                        @else
                                        <div class="user-permissions-row"></div>
                                        @endif
                                        @if (in_array('write',$routeData['actions']))
                                        <div class="checkbox-switch-wrapper user-permissions-row">
                                            <div class="checkbox-switch blue">
                                                <input
                                                    type="checkbox"
                                                    name="permissions[{{ $routeData['name'] }}][write]"
                                                    id="{{ $routeData['title'] }}_write"
                                                    value="1"
                                                    @if (!empty($routeData['permissions']['write']))
                                                        checked
                                                    @endif
                                                />
                                                <i></i>
                                            </div>
                                        </div>
                                        @else
                                        <div class="user-permissions-row"></div>
                                        @endif
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
            <button type="submit" class="default-btn success-button">{{ __('elfcms::default.submit') }}</button>
            <a href="{{ route('admin.user.roles') }}" class="default-btn">{{ __('elfcms::default.cancel') }}</a>
        </div>
    </form>
</div>
<script>
autoSlug('.autoslug');
</script>
@endsection
