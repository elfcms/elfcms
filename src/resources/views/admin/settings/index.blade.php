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
                        <div class="checkbox-inner">
                            <input
                                type="checkbox"
                                name="{{ $setting['code'] }}"
                                id="{{ $setting['code'] }}"
                                value="1"
                                @if ($setting['value'] && $setting['value'] == 1)
                                    checked
                                @endif
                            >
                            <i></i>
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
                        <input type="hidden" name="{{ $setting['code'] }}_path" id="{{ $setting['code'] }}_path" value="{{$setting['value']}}">
                        <div class="image-button">
                            <div class="delete-image @if (empty($setting['value'])) hidden @endif">&#215;</div>
                            <div class="image-button-img">
                            @if (!empty($setting['value']))
                                <img src="{{ asset($setting['value']) }}" alt="">
                            @else
                                <img src="{{ asset('/elfcms/admin/images/icons/upload.png') }}" alt="Upload file">
                            @endif
                            </div>
                            <div class="image-button-text">
                            @if (!empty($setting['value']))
                                {{ __('elfcms::default.change_file') }}
                            @else
                                {{ __('elfcms::default.choose_file') }}
                            @endif
                            </div>
                            <input type="file" name="{{ $setting['code'] }}" id="{{ $setting['code'] }}" accept="image/*">
                        </div>
                    </div>
                </div>
                <script>
                    const {{ Str::camel($setting['code']) }} = document.querySelector('#{{ $setting['code'] }}')
                    if ({{ Str::camel($setting['code']) }}) {
                        inputFileImg({{ Str::camel($setting['code']) }})
                    }
                </script>
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
                {{-- <div class="input-box colored">
                    <label for="site_name">{{ __('elfcms::default.site_name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="site_name" id="site_name" autocomplete="off" value="{{ $settings->site_name }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="site_title">{{ __('elfcms::default.site_title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="site_title" id="site_title" autocomplete="off" value="{{ $settings->site_title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="site_slogan">{{ __('elfcms::default.site_slogan') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="site_slogan" id="site_slogan" autocomplete="off" value="{{ $settings->site_slogan }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="site_logo">{{ __('elfcms::default.site_logo') }}</label>
                    <div class="input-wrapper">
                        <input type="hidden" name="site_logo_path" id="site_logo_path" value="{{$settings->site_logo}}">
                        <div class="image-button">
                            <div class="delete-image @if (empty($settings->site_logo)) hidden @endif">&#215;</div>
                            <div class="image-button-img">
                            @if (!empty($settings->site_logo))
                                <img src="{{ asset($settings->site_logo) }}" alt="Site logo">
                            @else
                                <img src="{{ asset('/elfcms/admin/images/icons/upload.png') }}" alt="Upload file">
                            @endif
                            </div>
                            <div class="image-button-text">
                            @if (!empty($settings->site_logo))
                                {{ __('elfcms::default.change_file') }}
                            @else
                                {{ __('elfcms::default.choose_file') }}
                            @endif
                            </div>
                            <input type="file" name="site_logo" id="site_logo">
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="site_icon">{{ __('elfcms::default.site_icon') }}</label>
                    <div class="input-wrapper">
                        <input type="hidden" name="site_icon_path" id="site_icon_path" value="{{$settings->site_icon}}">
                        <div class="image-button">
                            <div class="delete-image @if (empty($settings->site_icon)) hidden @endif">&#215;</div>
                            <div class="image-button-img">
                            @if (!empty($settings->site_icon))
                                <img src="{{ asset($settings->site_icon) }}" alt="Site icon">
                            @else
                                <img src="{{ asset('/elfcms/admin/images/icons/upload.png') }}" alt="Upload file">
                            @endif
                            </div>
                            <div class="image-button-text">
                            @if (!empty($settings->site_icon))
                                {{ __('elfcms::default.change_file') }}
                            @else
                                {{ __('elfcms::default.choose_file') }}
                            @endif
                            </div>
                            <input type="file" name="site_icon" id="site_icon">
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="site_keyword">{{ __('elfcms::default.site_keyword') }}</label>
                    <div class="input-wrapper">
                        <textarea name="site_keyword" id="site_keyword" cols="30" rows="3">{{ $settings->site_keyword }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="site_description">{{ __('elfcms::default.site_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="site_description" id="site_description" cols="30" rows="3">{{ $settings->site_description }}</textarea>
                    </div>
                </div> --}}
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
