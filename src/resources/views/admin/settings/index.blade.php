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

    <div class="item-form">
        <h3>{{ __('elfcms::default.edit_site_settings') }}</h3>
        <form action="{{ route('admin.settings.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
            @foreach ($settings as $setting)
                @if (empty($setting['params']['type']) || $setting['params']['type'] == 'string')
                <div class="input-box colored">
                    <label for="{{ $setting['code'] }}">@if (__('elfcms::default.'.$setting['code']) != 'elfcms::default.'.$setting['code']) {{__('elfcms::default.'.$setting['code'])}} @else {{ $setting['name'] }} @endif</label>
                    <div class="input-wrapper">
                        <input type="text" name="{{ $setting['code'] }}" id="{{ $setting['code'] }}" autocomplete="off" value="{{ $setting['value'] }}">
                    </div>
                </div>
                @elseif (in_array($setting['params']['type'],['number','tel','email','password','search','date','time','datetime','month','week','color']))
                <div class="input-box colored">
                    <label for="{{ $setting['code'] }}">@if (__('elfcms::default.'.$setting['code']) != 'elfcms::default.'.$setting['code']) {{__('elfcms::default.'.$setting['code'])}} @else {{ $setting['name'] }} @endif</label>
                    <div class="input-wrapper">
                        <input type="{{$setting['params']['type']}}" name="{{ $setting['code'] }}" id="{{ $setting['code'] }}" autocomplete="off" value="{{ $setting['value'] }}">
                    </div>
                </div>
                @elseif ($setting['params']['type'] == 'text')
                <div class="input-box colored">
                    <label for="{{ $setting['code'] }}">@if (__('elfcms::default.'.$setting['code']) != 'elfcms::default.'.$setting['code']) {{__('elfcms::default.'.$setting['code'])}} @else {{ $setting['name'] }} @endif</label>
                    <div class="input-wrapper">
                        <textarea name="{{ $setting['code'] }}" id="{{ $setting['code'] }}" cols="30" rows="3">{{ $setting['value'] }}</textarea>
                    </div>
                </div>
                @elseif ($setting['params']['type'] == 'checkbox')
                <div class="input-box colored">
                    <div class="checkbox-wrapper">
                        <div class="checkbox-switch-wrapper">
                            <div class="checkbox-switch blue">
                                <input type="checkbox" name="{{ $setting['code'] }}" id="{{ $setting['code'] }}" value="1"
                                @if ($setting['value'] && $setting['value'] == 1)
                                    checked
                                @endif>
                                <i></i>
                            </div>
                            <label for="{{ $setting['code'] }}">
                                @if (__('elfcms::default.'.$setting['code']) != 'elfcms::default.'.$setting['code'])
                                {{__('elfcms::default.'.$setting['code'])}}
                            @else
                                {{ $setting['name'] }}
                            @endif
                            </label>
                        </div>
                    </div>
                </div>
                @elseif ($setting['params']['type'] == 'image')
                <div class="input-box colored">
                    <label for="{{ $setting['code'] }}">@if (__('elfcms::default.'.$setting['code']) != 'elfcms::default.'.$setting['code']) {{__('elfcms::default.'.$setting['code'])}} @else {{ $setting['name'] }} @endif</label>
                    <div class="input-wrapper">
                        <x-elfcms-input-image :inputData=$setting />
                    </div>
                </div>
                @elseif ($setting['code'] == 'site_locale')
                <div class="input-box colored">
                    <label for="{{ $setting['code'] }}">@if (__('elfcms::default.'.$setting['code']) != 'elfcms::default.'.$setting['code']) {{__('elfcms::default.'.$setting['code'])}} @else {{ $setting['name'] }} @endif</label>
                    <div class="input-wrapper">
                        <select name="{{ $setting['code'] }}" id="{{ $setting['code'] }}">
                        @foreach ($locales as $locale)
                            <option value="{{ $locale['code'] }}" @if ($locale['code'] == $setting['value']) selected @endif>{{ $locale['name'] }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                @endif
            @endforeach
            </div>
            <div class="button-box single-box">
                <button type="submit" class="default-btn success-button">{{ __('elfcms::default.submit') }}</button>
            </div>
        </form>
    </div>
</div>
<script>
const useStatisticCheckbox = document.querySelector('[name="site_statistics_use"]')
if (useStatisticCheckbox) {
    useStatisticCheckbox.addEventListener('change',function(e){
        if (this.checked) {
            self = this
            let aa = popup({
                title:'{{ __('elfcms::default.warning') }}',
                content:'<p>{!! __('elfcms::default.user_data_warning_html') !!}</p>',
                buttons:[
                    {
                        title:'{{ __('elfcms::default.confirm') }}',
                        class:'default-btn delete-button',
                        callback: 'close'
                    },
                    {
                        title:'{{ __('elfcms::default.cancel') }}',
                        class:'default-btn cancel-button',
                        callback: [
                            function(){
                                self.checked = false;
                            },
                            'close'
                        ]
                    }
                ],
                class:'danger'
            })
        }
    })
}
</script>

@endsection
