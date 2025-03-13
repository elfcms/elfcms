@extends('elfcms::admin.layouts.main')

@section('pagecontent')

    <div class="item-form">
        <h2>{{ __('elfcms::default.create_form_field_group') }}</h2>
        <form action="{{ route('admin.forms.groups.store', $form) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label>{{ __('elfcms::default.form') }}</label>
                    <div class="input-wrapper">
                        #{{ $form->id }} {{ $form->title ?? $form->name ?? $form->code }}
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="active">
                        {{ __('elfcms::default.active') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="active" id="active" checked="true" />
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" autocomplete="off" @class(['failed' => !empty($errorFields['title'])]) value="{{ $fields['title'] ?? '' }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="code">{{ __('elfcms::default.code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="code" id="code" autocomplete="off" data-isslug @class(['failed' => !empty($errorFields['code'])]) value="{{ $fields['code'] ?? '' }}">
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug textid="title" slugid="code" slugspace="_" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off" data-isslug @class(['failed' => !empty($errorFields['name'])]) value="{{ $fields['name'] ?? '' }}">
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug textid="title" slugid="name" checked="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="description">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="3">{{ $fields['description'] ?? '' }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="position">{{ __('elfcms::default.position') }}</label>
                    <div class="input-wrapper">
                        <input type="number" name="position" id="position" autocomplete="off" value="{{ $fields['position'] ?? 0 }}">
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="button color-text-button success-button">{{ __('elfcms::default.submit') }}</button>
                <button type="submit" name="submit" value="save_and_close" class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.forms.show', $form) }}" class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
    inputSlugInit()
    const errorFields = document.querySelectorAll('.failed');
    if (errorFields) {
        errorFields.forEach(field => {
            field.addEventListener('change',function(){
                this.classList.remove('failed');
            });
        });
    }
    const position = document.querySelector('input[name="position"]');
    if (position) {
        position.addEventListener("input", function () {
            if (this.value == '' || this.value === undefined || this.value === null || this.value === false || isNaN(this.value)) {
                this.value = 0;
            }
        });
    }
    </script>

@endsection
