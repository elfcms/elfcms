@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="item-form">
        <h2>{{ __('elfcms::default.edit_site_settings') }}</h2>
        <form action="{{ route('admin.settings.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                @foreach ($settings as $setting)
                    @if (empty($setting['params']['type']) || $setting['params']['type'] == 'string')
                        <div class="input-box colored">
                            <label for="{{ $setting['code'] }}">
                                @if (__('elfcms::default.' . $setting['code']) != 'elfcms::default.' . $setting['code'])
                                    {{ __('elfcms::default.' . $setting['code']) }}
                                @else
                                    {{ $setting['title'] }}
                                @endif
                            </label>
                            <div class="input-wrapper">
                                <input type="text" name="{{ $setting['code'] }}" id="{{ $setting['code'] }}"
                                    autocomplete="off" value="{{ $setting['value'] }}">
                            </div>
                        </div>
                    @elseif (in_array($setting['params']['type'], [
                            'number',
                            'tel',
                            'email',
                            'password',
                            'search',
                            'date',
                            'time',
                            'datetime',
                            'month',
                            'week',
                            'color',
                        ]))
                        <div class="input-box colored">
                            <label for="{{ $setting['code'] }}">
                                @if (__('elfcms::default.' . $setting['code']) != 'elfcms::default.' . $setting['code'])
                                    {{ __('elfcms::default.' . $setting['code']) }}
                                @else
                                    {{ $setting['title'] }}
                                @endif
                            </label>
                            <div class="input-wrapper">
                                <input type="{{ $setting['params']['type'] }}" name="{{ $setting['code'] }}"
                                    id="{{ $setting['code'] }}" autocomplete="off" value="{{ $setting['value'] }}">
                            </div>
                        </div>
                    @elseif ($setting['params']['type'] == 'text')
                        <div class="input-box colored">
                            <label for="{{ $setting['code'] }}">
                                @if (__('elfcms::default.' . $setting['code']) != 'elfcms::default.' . $setting['code'])
                                    {{ __('elfcms::default.' . $setting['code']) }}
                                @else
                                    {{ $setting['title'] }}
                                @endif
                            </label>
                            <div class="input-wrapper">
                                <textarea name="{{ $setting['code'] }}" id="{{ $setting['code'] }}" cols="30" rows="3">{{ $setting['value'] }}</textarea>
                            </div>
                        </div>
                    @elseif ($setting['params']['type'] == 'checkbox')
                        <div class="input-box colored">
                            <label for="{{ $setting['code'] }}">
                                @if (__('elfcms::default.' . $setting['code']) != 'elfcms::default.' . $setting['code'])
                                    {{ __('elfcms::default.' . $setting['code']) }}
                                @else
                                    {{ $setting['title'] }}
                                @endif
                            </label>
                            <div class="input-wrapper">
                                <div class="switchbox">
                                    <input type="checkbox" name="{{ $setting['code'] }}" id="{{ $setting['code'] }}"
                                        value="1" @if ($setting['value'] && $setting['value'] == 1) checked @endif>
                                    <i></i>
                                </div>
                            </div>
                        </div>
                    @elseif ($setting['params']['type'] == 'image')
                        <div class="input-box colored">
                            <label for="{{ $setting['code'] }}">
                                @if (__('elfcms::default.' . $setting['code']) != 'elfcms::default.' . $setting['code'])
                                    {{ __('elfcms::default.' . $setting['code']) }}
                                @else
                                    {{ $setting['title'] }}
                                @endif
                            </label>
                            <div class="input-wrapper">
                                <x-elf-input-file :params="$setting" :download="true" />
                            </div>
                        </div>
                    @elseif ($setting['code'] == 'site_locale')
                        <div class="input-box colored">
                            <label for="{{ $setting['code'] }}">
                                @if (__('elfcms::default.' . $setting['code']) != 'elfcms::default.' . $setting['code'])
                                    {{ __('elfcms::default.' . $setting['code']) }}
                                @else
                                    {{ $setting['title'] }}
                                @endif
                            </label>
                            <div class="input-wrapper">
                                <select name="{{ $setting['code'] }}" id="{{ $setting['code'] }}">
                                    @foreach ($locales as $locale)
                                        <option value="{{ $locale['code'] }}"
                                            @if ($locale['code'] == $setting['value']) selected @endif>{{ $locale['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @elseif ($setting['code'] == 'admin_locale')
                        <div class="input-box colored">
                            <label for="{{ $setting['code'] }}">
                                @if (__('elfcms::default.' . $setting['code']) != 'elfcms::default.' . $setting['code'])
                                    {{ __('elfcms::default.' . $setting['code']) }}
                                @else
                                    {{ $setting['title'] }}
                                @endif
                            </label>
                            <div class="input-wrapper">
                                <select name="{{ $setting['code'] }}" id="{{ $setting['code'] }}">
                                    @foreach ($locales as $locale)
                                        <option value="{{ $locale['code'] }}"
                                            @if ($locale['code'] == $setting['value']) selected @endif>{{ $locale['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                @endforeach

                <h3>{{ __('elfcms::default.contacts') }}</h3>
                @foreach ($contacts as $contact)
                    @if (empty($contact['params']['type']) || $contact['params']['type'] == 'string')
                        <div class="input-box colored">
                            <label for="{{ $contact['code'] }}">
                                @if (__('elfcms::default.' . $contact['code']) != 'elfcms::default.' . $contact['code'])
                                    {{ __('elfcms::default.' . $contact['code']) }}
                                @else
                                    {{ $contact['name'] }}
                                @endif
                            </label>
                            <div class="input-wrapper">
                                <input type="text" name="{{ $contact['code'] }}" id="{{ $contact['code'] }}"
                                    autocomplete="off" value="{{ $contact['value'] }}">
                            </div>
                        </div>
                    @elseif (in_array($contact['params']['type'], [
                            'number',
                            'tel',
                            'email',
                            'password',
                            'search',
                            'date',
                            'time',
                            'datetime',
                            'month',
                            'week',
                            'color',
                        ]))
                        <div class="input-box colored">
                            <label for="{{ $contact['code'] }}">
                                @if (__('elfcms::default.' . $contact['code']) != 'elfcms::default.' . $contact['code'])
                                    {{ __('elfcms::default.' . $contact['code']) }}
                                @else
                                    {{ $contact['name'] }}
                                @endif
                            </label>
                            <div class="input-wrapper">
                                <input type="{{ $contact['params']['type'] }}" name="{{ $contact['code'] }}"
                                    id="{{ $contact['code'] }}" autocomplete="off" value="{{ $contact['value'] }}">
                            </div>
                        </div>
                    @elseif ($contact['params']['type'] == 'text')
                        <div class="input-box colored">
                            <label for="{{ $contact['code'] }}">
                                @if (__('elfcms::default.' . $contact['code']) != 'elfcms::default.' . $contact['code'])
                                    {{ __('elfcms::default.' . $contact['code']) }}
                                @else
                                    {{ $contact['name'] }}
                                @endif
                            </label>
                            <div class="input-wrapper">
                                <textarea name="{{ $contact['code'] }}" id="{{ $contact['code'] }}" cols="30" rows="3">{{ $contact['value'] }}</textarea>
                            </div>
                        </div>
                    @elseif ($contact['params']['type'] == 'checkbox')
                        <div class="input-box colored">
                            <div class="checkbox-wrapper">
                                <div class="checkbox-switch-wrapper">
                                    <div class="checkbox-switch blue">
                                        <input type="checkbox" name="{{ $contact['code'] }}" id="{{ $contact['code'] }}"
                                            value="1" @if ($contact['value'] && $contact['value'] == 1) checked @endif>
                                        <i></i>
                                    </div>
                                    <label for="{{ $contact['code'] }}">
                                        @if (__('elfcms::default.' . $contact['code']) != 'elfcms::default.' . $contact['code'])
                                            {{ __('elfcms::default.' . $contact['code']) }}
                                        @else
                                            {{ $contact['name'] }}
                                        @endif
                                    </label>
                                </div>
                            </div>
                        </div>
                    @elseif ($contact['params']['type'] == 'image')
                        <div class="input-box colored">
                            <label for="{{ $contact['code'] }}">
                                @if (__('elfcms::default.' . $contact['code']) != 'elfcms::default.' . $contact['code'])
                                    {{ __('elfcms::default.' . $contact['code']) }}
                                @else
                                    {{ $contact['name'] }}
                                @endif
                            </label>
                            <div class="input-wrapper">
                                <x-elf-input-file :params=$contact />
                            </div>
                        </div>
                    @elseif ($contact['code'] == 'site_locale')
                        <div class="input-box colored">
                            <label for="{{ $contact['code'] }}">
                                @if (__('elfcms::default.' . $contact['code']) != 'elfcms::default.' . $contact['code'])
                                    {{ __('elfcms::default.' . $contact['code']) }}
                                @else
                                    {{ $contact['name'] }}
                                @endif
                            </label>
                            <div class="input-wrapper">
                                <select name="{{ $contact['code'] }}" id="{{ $contact['code'] }}">
                                    @foreach ($locales as $locale)
                                        <option value="{{ $locale['code'] }}"
                                            @if ($locale['code'] == $contact['value']) selected @endif>{{ $locale['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="button-box single-box">
                <button type="submit" class="button color-text-button success-button">{{ __('elfcms::default.submit') }}</button>
            </div>
        </form>
    </div>
    <script>
        const useStatisticCheckbox = document.querySelector('[name="site_statistics_use"]')
        if (useStatisticCheckbox) {
            useStatisticCheckbox.addEventListener('change', function(e) {
                if (this.checked) {
                    self = this
                    let aa = popup({
                        title: '{{ __('elfcms::default.warning') }}',
                        content: '<p>{!! __('elfcms::default.user_data_warning_html') !!}</p>',
                        buttons: [{
                                title: '{{ __('elfcms::default.confirm') }}',
                                class: 'button color-text-button red-button',
                                callback: 'close'
                            },
                            {
                                title: '{{ __('elfcms::default.cancel') }}',
                                class: 'button color-text-button',
                                callback: [
                                    function() {
                                        self.checked = false;
                                    },
                                    'close'
                                ]
                            }
                        ],
                        class: 'danger'
                    })
                }
            })
        }
        const useMaintenanceCheckbox = document.querySelector('[name="site_maintenance"]')
        if (useMaintenanceCheckbox) {
            useMaintenanceCheckbox.addEventListener('change', function(e) {
                if (this.checked) {
                    self = this
                    let aa = popup({
                        title: '{{ __('elfcms::default.warning') }}',
                        content: '<p>{!! __('elfcms::default.are_you_sure_to_enable_maintenance_mode') !!}</p>',
                        buttons: [{
                                title: '{{ __('elfcms::default.confirm') }}',
                                class: 'button color-text-button red-button',
                                callback: 'close'
                            },
                            {
                                title: '{{ __('elfcms::default.cancel') }}',
                                class: 'button color-text-button',
                                callback: [
                                    function() {
                                        self.checked = false;
                                    },
                                    'close'
                                ]
                            }
                        ],
                        class: 'danger'
                    })
                } else {
                    self = this
                    let aa = popup({
                        title: '{{ __('elfcms::default.warning') }}',
                        content: '<p>{!! __('elfcms::default.are_you_sure_to_disable_maintenance_mode') !!}</p>',
                        buttons: [{
                                title: '{{ __('elfcms::default.confirm') }}',
                                class: 'button color-text-button red-button',
                                callback: 'close'
                            },
                            {
                                title: '{{ __('elfcms::default.cancel') }}',
                                class: 'button color-text-button',
                                callback: [
                                    function() {
                                        self.checked = false;
                                    },
                                    'close'
                                ]
                            }
                        ],
                        class: 'danger'
                    })
                }
            })
        }
    </script>
@endsection
