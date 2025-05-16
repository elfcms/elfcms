@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    {{-- @if (Session::has('success'))
        <x-elf-notify type="success" title="{{ __('elfcms::default.success') }}" text="{{ Session::get('success') }}" />
    @endif
    @if ($errors->any())
        <x-elf-notify type="error" title="{{ __('elfcms::default.error') }}" text="{!! '<ul><li>' . implode('</li><li>', $errors->all()) . '</li></ul>' !!}" />
    @endif --}}
    <div class="user-form item-form">
        <h2>{{ __('elfcms::default.create_new_role') }}</h2>
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
                        <x-elfcms::ui.checkbox.autoslug checked="true" textid="name" slugid="code" />
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
@endsection
