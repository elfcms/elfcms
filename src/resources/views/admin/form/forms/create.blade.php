@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.forms.index') }}" class="button round-button theme-button"
            style="color:var(--default-color);">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.back') }}
            </span>
        </a>
    </div>
    <div class="item-form">
        <h2>{{ __('elfcms::default.create_form') }}</h2>
        <form action="{{ route('admin.forms.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="active">
                        {{ __('elfcms::default.active') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="active" id="active" checked="true" />
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input @class(['failed' => !empty($errorFields['title'])]) type="text" name="title" id="title" autocomplete="off"
                            value="{{ $fields['title'] ?? '' }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="code">{{ __('elfcms::default.code') }}</label>
                    <div class="input-wrapper">
                        <input @class(['failed' => !empty($errorFields['code'])]) type="text" name="code" id="code" autocomplete="off"
                            data-isslug value="{{ $fields['code'] ?? '' }}">
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug textid="title" slugid="code" slugspace="_" checked="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input @class(['failed' => !empty($errorFields['name'])]) type="text" name="name" id="name" autocomplete="off"
                            data-isslug value="{{ $fields['name'] ?? '' }}">
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug textid="title" slugid="name" checked="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="action">{{ __('elfcms::default.action') }}</label>
                    <div class="input-wrapper">
                        <input @class(['failed' => !empty($errorFields['action'])]) type="text" name="action" id="action" autocomplete="off"
                            value="{{ $fields['action'] ?? '' }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="enctype">{{ __('elfcms::default.enctype') }}</label>
                    <div class="input-wrapper">
                        <select name="enctype" id="enctype">
                            <option value="">None</option>
                            @foreach ($enctypes as $enctype)
                                <option value="{{ $enctype }}" @if (!empty($fields['enctype']) && $fields['enctype'] == $enctype) selected @endif>
                                    {{ $enctype }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="description">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <textarea @class(['failed' => !empty($errorFields['description'])]) name="description" id="description" cols="30" rows="3">{{ $fields['description'] ?? '' }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="submit_button">{{ __('elfcms::default.submit_button') }}</label>
                    <div class="input-wrapper">
                        <input @class(['failed' => !empty($errorFields['submit_button'])]) type="text" name="submit_button" id="submit_button"
                            autocomplete="off" value="{{ $fields['submit_button'] ?? '' }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="redirect_to">{{ __('elfcms::default.redirect_to') }}</label>
                    <div class="input-wrapper">
                        <input @class(['failed' => !empty($errorFields['redirect_to'])]) type="text" name="redirect_to" id="redirect_to"
                            autocomplete="off" value="{{ $fields['redirect_to'] ?? '' }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="success_text">{{ __('elfcms::default.success_text') }}</label>
                    <div class="input-wrapper">
                        <input @class(['failed' => !empty($errorFields['success_text'])]) type="text" name="success_text" id="success_text"
                            autocomplete="off" value="{{ $fields['success_text'] ?? '' }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="error_text">{{ __('elfcms::default.error_text') }}</label>
                    <div class="input-wrapper">
                        <input @class(['failed' => !empty($errorFields['error_text'])]) type="text" name="error_text" id="error_text"
                            autocomplete="off" value="{{ $fields['error_text'] ?? '' }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="submit_name">{{ __('elfcms::default.submit_name') }}</label>
                    <div class="input-wrapper">
                        <input @class(['failed' => !empty($errorFields['submit_name'])]) type="text" name="submit_name" id="submit_name"
                            autocomplete="off" value="{{ $fields['submit_name'] ?? '' }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="submit_title">{{ __('elfcms::default.submit_title') }}</label>
                    <div class="input-wrapper">
                        <input @class(['failed' => !empty($errorFields['submit_title'])]) type="text" name="submit_title" id="submit_title"
                            autocomplete="off" value="{{ $fields['submit_title'] ?? '' }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="submit_value">{{ __('elfcms::default.submit_value') }}</label>
                    <div class="input-wrapper">
                        <input @class(['failed' => !empty($errorFields['submit_value'])]) type="text" name="submit_value" id="submit_value"
                            autocomplete="off" value="{{ $fields['submit_value'] ?? '' }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="reset_button">{{ __('elfcms::default.reset_button') }}</label>
                    <div class="input-wrapper">
                        <input @class(['failed' => !empty($errorFields['reset_button'])]) type="text" name="reset_button" id="reset_button"
                            autocomplete="off" value="{{ $fields['reset_button'] ?? '' }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="reset_title">{{ __('elfcms::default.reset_title') }}</label>
                    <div class="input-wrapper">
                        <input @class(['failed' => !empty($errorFields['reset_title'])]) type="text" name="reset_title" id="reset_title"
                            autocomplete="off" value="{{ $fields['reset_title'] ?? '' }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="reset_value">{{ __('elfcms::default.reset_value') }}</label>
                    <div class="input-wrapper">
                        <input @class(['failed' => !empty($errorFields['reset_value'])]) type="text" name="reset_value" id="reset_value"
                            autocomplete="off" value="{{ $fields['reset_value'] ?? '' }}">
                    </div>
                </div>

                <div class="input-box colored">
                    <label for="event_id">{{ __('elfcms::default.events') }}</label>
                    <div class="input-wrapper">
                        <select name="event_id" id="event_id">
                            <option value="">--none--</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" @if (!empty($fields['event_id']) && $fields['event_id'] == $event->id) selected @endif>
                                    {{ $event->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <div class="button-box single-box">
                <button type="submit" name="submit" value="save"
                    class="button color-text-button success-button">{{ __('elfcms::default.save') }}</button>
                <button type="submit" name="submit" value="save_and_open"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_open') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.forms.index') }}"
                    class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
        inputSlugInit()
        const errorFields = document.querySelectorAll('.failed');
        if (errorFields) {
            errorFields.forEach(field => {
                field.addEventListener('change', function() {
                    this.classList.remove('failed');
                });
            });
        }
    </script>
@endsection
