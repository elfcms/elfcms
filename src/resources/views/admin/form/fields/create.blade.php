@extends('elfcms::admin.layouts.form')

@section('formpage-content')

    @if (Session::has('fieldcreated'))
        <div class="alert alert-success">{{ Session::get('fieldcreated') }}</div>
    @endif
    @if (Session::has('fieldedited'))
        <div class="alert alert-success">{{ Session::get('fielddited') }}</div>
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
        <h3>{{ __('elfcms::default.create_field') }}</h3>
        <form action="{{ route('admin.form.fields.store') }}" method="POST" enctype="multipart/form-data">
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
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off" data-isslug>
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <input type="checkbox" data-text-id="title" data-slug-id="name" class="autoslug" checked>
                            <div class="autoslug-button"></div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="placeholder">{{ __('elfcms::default.placeholder') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="placeholder" id="placeholder" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="description">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="description" id="description" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <div class="checkbox-wrapper">
                        <div class="checkbox-inner">
                            <input
                                type="checkbox"
                                name="required"
                                id="required"
                            >
                            <i></i>
                            <label for="required">
                                {{ __('elfcms::default.field_is_required') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <div class="checkbox-wrapper">
                        <div class="checkbox-inner">
                            <input
                                type="checkbox"
                                name="disabled"
                                id="disabled"
                            >
                            <i></i>
                            <label for="disabled">
                                {{ __('elfcms::default.field_is_disabled') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <div class="checkbox-wrapper">
                        <div class="checkbox-inner">
                            <input
                                type="checkbox"
                                name="checked"
                                id="checked"
                            >
                            <i></i>
                            <label for="checked">
                                {{ __('elfcms::default.field_is_checked') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <div class="checkbox-wrapper">
                        <div class="checkbox-inner">
                            <input
                                type="checkbox"
                                name="readonly"
                                id="readonly"
                            >
                            <i></i>
                            <label for="readonly">
                                {{ __('elfcms::default.readonly_field') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="position">{{ __('elfcms::default.position') }}</label>
                    <div class="input-wrapper">
                        <input type="number" name="position" id="position" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="form_id">{{ __('elfcms::default.form') }}</label>
                    <div class="input-wrapper">
                        <select name="form_id" id="form_id">
                        @foreach ($forms as $item)
                            <option value="{{ $item->id }}" @if($form_id == $item->id) selected @endif>{{ $item->name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="group_id">{{ __('elfcms::default.group') }}</label>
                    <div class="input-wrapper">
                        <select name="group_id" id="group_id">
                            <option value="null"> {{ __('elfcms::default.none') }} </option>
                        @foreach ($groups as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="type_id">{{ __('elfcms::default.field_type') }}</label>
                    <div class="input-wrapper">
                        <select name="type_id" id="type_id">
                        @foreach ($types as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored hidden" id="optionsbox">
                    <label for="">{{ __('elfcms::default.field_options') }}</label>
                    <div class="input-wrapper">
                        <div>
                            <div class="input-options-table">
                                <div class="options-table-head-line">
                                    <div class="options-table-head">
                                        {{ __('elfcms::default.value') }}
                                    </div>
                                    <div class="options-table-head">
                                        {{ __('elfcms::default.text') }}
                                    </div>
                                    <div class="options-table-head">
                                        {{ __('elfcms::default.selected') }}
                                    </div>
                                    <div class="options-table-head">
                                        {{ __('elfcms::default.disabled') }}
                                    </div>
                                    <div class="options-table-head">
                                        {{ __('elfcms::default.delete') }}
                                    </div>
                                    <div class="options-table-head"></div>
                                </div>
                                <div class="options-table-string-line" data-line="0">
                                    <div class="options-table-string">
                                        <input type="text" name="options_new[0][value]" id="option_new_value_0" data-option-value>
                                    </div>
                                    <div class="options-table-string">
                                        <input type="text" name="options_new[0][text]" id="option_new_text_0" data-option-text>
                                    </div>
                                    <div class="options-table-string">
                                        <input type="checkbox" name="options_new[0][selected]" id="option_new_selected_0" data-option-selected>
                                    </div>
                                    <div class="options-table-string">
                                        <input type="checkbox" name="options_new[0][disabled]" id="option_new_disabled_0" data-option-disabled>
                                    </div>
                                    <div class="options-table-string">
                                        <input type="checkbox" name="options_new[0][deleted]" id="option_new_disabled_0" data-option-deleted>
                                    </div>
                                    <div class="options-table-string"></div>
                                </div>
                            </div>
                            <button type="button" class="default-btn option-table-add" id="addoptionline">{{ __('elfcms::default.add_option') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.submit') }}</button>
            </div>
        </form>
    </div>
    <script>
    autoSlug('.autoslug')
    inputSlugInit()

    fieldGroupInit()

    showOptionsSelect('select#type_id','#optionsbox')
    optionBoxInit()
    onlyOneCheckedInit('[data-option-selected]','[data-option-selected]')
    </script>

@endsection
