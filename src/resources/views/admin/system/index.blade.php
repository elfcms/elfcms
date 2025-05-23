@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.system.updates') }}" class="button round-button theme-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/update.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.update') }}
            </span>
        </a>
    </div>

    <div class="medium-container">
        <div class="item-form">
            <h2>{{ __('elfcms::default.system_data') }}</h2>

            <div class="system-top-box">
                <div class="system-top-logo">
                    {!! iconHtmlLocal('elfcms/admin/images/logo/logo.svg', svg: true) !!}
                </div>
                <div class="system-top-data">
                    <p @class([
                        'system-name',
                        'unstable' => in_array($data['release_status'], ['dev', 'alpha', 'beta']),
                    ]) data-status="{{ $data['release_status'] }}">ELF CMS
                        {{ $data['version'] }}</p>
                    <p>{{ __('elfcms::default.developed_by_mk') }}</p>
                    <p>&copy; 2022-{{ date('Y') }}</p>
                    <p>
                        <a href="{{ route('admin.system.license') }}">{{ __('elfcms::default.license_MIT') }}</a>
                    </p>
                    <p>
                        <a href="https://github.com/elfcms/elfcms" target="_blank">GitHub</a>
                    </p>
                </div>
            </div>
            @if (!empty($modules))
                <h3>{{ __('elfcms::default.modules') }}</h3>
                @foreach ($modules as $name => $module)
                    <div class="module-box colored">
                        <h4 @class([
                            'module-name',
                            'unstable' =>
                                !empty($module['release_status']) &&
                                in_array($module['release_status'], ['dev', 'alpha', 'beta']),
                        ]) data-status="{{ $data['release_status'] ?? 'stable' }}">
                            {{ $module['title'] ?? ucfirst($name) }} {{ $module['version'] ?? '' }}
                        </h4>
                        @if (!empty($module['description']))
                            <p class="system-module-description">
                                {!! $module['description'] !!}
                            </p>
                        @endif
                        @if (!empty($module['author']))
                            <p>{{ __('elfcms::default.developed_by') . ' ' . $module['author'] }}</p>
                        @endif
                        @if (!empty($module['url']))
                            <p>
                                <a href="{{ $module['url'] }}" target="_blank">Website</a>
                            </p>
                        @endif
                        @if (!empty($module['github']))
                            <p>
                                <a href="{{ $module['github'] }}" target="_blank">GitHub</a>
                            </p>
                        @endif
                        @if (!empty($module['license']))
                            <p>{{ __('elfcms::default.license') }}: {{ $module['license'] }}</p>
                        @endif
                        @if (!empty($module['release_date']))
                            <p>&copy; {{ $module['release_date'] }}</p>
                        @endif
                    </div>
                @endforeach
            @endif
            @if (!empty($modulesToInstall))
                <h3>{{ __('elfcms::default.available_to_install') }}</h3>
                @foreach ($modulesToInstall as $module)
                    <div class="module-box colored">
                        <h4 @class([
                            'module-name',
                            'unstable' =>
                                !empty($module['release_status']) &&
                                in_array($module['release_status'], ['dev', 'alpha', 'beta']),
                        ]) data-status="{{ $data['release_status'] ?? 'stable' }}">
                            {{ $module['title'] ?? ucfirst($name) }} {{ $module['version'] ?? '' }}
                        </h4>
                        @if (!empty($module['description']))
                            <p class="system-module-description">
                                {!! $module['description'] !!}
                            </p>
                        @endif
                        @if (!empty($module['author']))
                            <p>{{ __('elfcms::default.developed_by') . ' ' . $module['author'] }}</p>
                        @endif
                        @if (!empty($module['repo']))
                            <p>
                                <a href="{{ $module['repo'] }}" target="_blank">GitHub</a>
                            </p>
                        @endif
                        @if (!empty($module['license']))
                            <p>{{ __('elfcms::default.license') }}: {{ $module['license'] }}</p>
                        @endif
                        <form action="{{ route('admin.system.install.module',['moduleName'=>$module['name']]) }}" method="POST">
                            @method('POST')
                            @csrf
                            <button class="button color-text-button">{{ __('elfcms::default.install') }}</button>
                        </form>
                    </div>
                @endforeach
            @endif

        </div>
    </div>
@endsection
