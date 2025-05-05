@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.fragment.items') }}" class="button round-button theme-button"
            style="color:var(--default-color);">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.back') }}
            </span>
        </a>
    </div>

    <div class="item-form">
        <h2>{{ __('elfcms::default.create_item') }}</h2>
        <form action="{{ route('admin.fragment.items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="code">{{ __('elfcms::default.code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="code" id="code" autocomplete="off" data-isslug>
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug textid="title" slugid="code" checked="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="text">{{ __('elfcms::default.text') }}</label>
                    <div class="input-wrapper">
                        <textarea name="text" id="text" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="image">{{ __('elfcms::default.image') }}</label>
                    <x-elf-input-file value="" :params="['name' => 'image']" :download="true" />
                </div>
                <div class="input-box colored" id="optionsbox">
                    <label for="">{{ __('elfcms::default.options') }}</label>
                    <div class="input-wrapper">
                        <div>
                            <div class="sb-input-options-table">
                                <div class="options-table-head-line">
                                    <div class="options-table-head">
                                        {{ __('elfcms::default.type') }}
                                    </div>
                                    <div class="options-table-head">
                                        {{ __('elfcms::default.name') }}
                                    </div>
                                    <div class="options-table-head">
                                        {{ __('elfcms::default.value') }}
                                    </div>
                                    <div class="options-table-head">
                                        {{ __('elfcms::default.delete') }}
                                    </div>
                                </div>
                                <div class="options-table-string-line" data-line="0">
                                    <div class="options-table-string">
                                        <select name="options_new[0][type]" id="option_new_type_0" data-option-type>
                                            @foreach ($data_types as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="options-table-string">
                                        <input type="text" name="options_new[0][name]" id="option_new_name_0"
                                            data-option-name data-isslug>
                                    </div>
                                    <div class="options-table-string">
                                        <input type="text" name="options_new[0][value]" id="option_new_value_0"
                                            data-option-value>
                                    </div>
                                    <div class="options-table-string">
                                        <div class="small-checkbox-wrapper" style="--switch-color:var(--danger-color)">
                                            <div class="small-checkbox">
                                                <input type="checkbox" name="options_new[0][deleted]"
                                                    id="option_new_disabled_0" data-option-deleted>
                                                <i></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="button simple-button"
                                id="addoptionline">{{ __('elfcms::default.add_option') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit"
                    class="button color-text-button success-button">{{ __('elfcms::default.submit') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.fragment.items') }}"
                    class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
    </div>
    </form>
    </div>
    <script src="{{ asset('elfcms/admin/js/fragment.js') }}"></script>
    <script>
        autoSlug('.autoslug')
        inputSlugInit()
        const imageInput = document.querySelector('#image')
        if (imageInput) {
            inputFileImg(imageInput)
        }
        //add editor
        runEditor('#text')


        fragmentOptionInit();
    </script>
@endsection
