@extends('elfcms::admin.layouts.form')

@section('formpage-content')

    @if (Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
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
        <h3>{{ __('elfcms::default.edit_field') }} #{{ $field->id }}</h3>
        <form action="{{ route('admin.forms.fields.update',['form'=>$form, 'field'=>$field]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="group_id">{{ __('elfcms::default.group') }}</label>
                    <div class="input-wrapper">
                        <select name="group_id" id="group_id">
                            <option value="null"> {{ __('elfcms::default.none') }} </option>
                        @foreach ($groups as $item)
                            <option value="{{ $item->id }}" @if ($field->group && $item->id == $field->group->id) selected @endif>{{ $item->name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <div class="checkbox-wrapper">
                        <div class="checkbox-switch-wrapper">
                            <div class="checkbox-switch blue">
                                <input type="checkbox" name="active" id="active" value="1" @if ($field->active == 1) checked @endif>
                                <i></i>
                            </div>
                            <label for="active">
                                {{ __('elfcms::default.active') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" autocomplete="off" value="{{ $field->title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off" data-isslug value="{{ $field->name }}">
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
                        <input type="text" name="placeholder" id="placeholder" autocomplete="off" value="{{ $field->placeholder }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="description">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="description" id="description" autocomplete="off" value="{{ $field->description }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="value">{{ __('elfcms::default.value') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="value" id="value" autocomplete="off" value="{{ $field->value }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <div class="checkbox-switch-wrapper">
                        <div class="checkbox-switch blue">
                            <input type="checkbox" name="required" id="required" value="1" @if ($field->required == 1) checked @endif>
                            <i></i>
                        </div>
                        <label for="required">
                            {{ __('elfcms::default.field_is_required') }}
                        </label>
                    </div>
                </div>
                <div class="input-box colored">
                    <div class="checkbox-switch-wrapper">
                        <div class="checkbox-switch blue">
                            <input type="checkbox" name="disabled" id="disabled" value="1" @if ($field->disabled == 1) checked @endif>
                            <i></i>
                        </div>
                        <label for="disabled">
                            {{ __('elfcms::default.field_is_disabled') }}
                        </label>
                    </div>
                </div>
                <div class="input-box colored">
                    <div class="checkbox-switch-wrapper">
                        <div class="checkbox-switch blue">
                            <input type="checkbox" name="checked" id="checked" value="1" @if ($field->checked == 1) checked @endif>
                            <i></i>
                        </div>
                        <label for="checked">
                            {{ __('elfcms::default.field_is_checked') }}
                        </label>
                    </div>
                </div>
                <div class="input-box colored">
                    <div class="checkbox-switch-wrapper">
                        <div class="checkbox-switch blue">
                            <input type="checkbox" name="readonly" id="readonly" value="1" @if ($field->readonly == 1) checked @endif>
                            <i></i>
                        </div>
                        <label for="readonly">
                            {{ __('elfcms::default.readonly_field') }}
                        </label>
                    </div>
                </div>

                <div class="input-box colored">
                    <label for="position">{{ __('elfcms::default.position') }}</label>
                    <div class="input-wrapper">
                        <input type="number" name="position" id="position" autocomplete="off" value="{{ $field->position }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label>{{ __('elfcms::default.form') }}</label>
                    <div class="input-wrapper">
                        <span class="info-field">{{ $field->form->name }} [{{ $field->form->id }}]</span>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="type_id">{{ __('elfcms::default.field_type') }}</label>
                    <div class="input-wrapper">
                        <select name="type_id" id="type_id">
                        @foreach ($types as $item)
                            <option value="{{ $item->id }}" @if ($item->id == $field->type->id) selected @endif>{{ $item->name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored hidden" id="optionsbox">
                    <label>{{ __('elfcms::default.field_options') }}</label>
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
                                @foreach ($field->options as $item)
                                <div class="options-table-string-line" data-exist-line="{{ $item->id }}">
                                    <div class="options-table-string">
                                        <input type="text" name="options_exist[{{ $item->id }}][value]" id="options_exist_value_{{ $item->id }}" data-option-value value="{{ $item->value }}">
                                    </div>
                                    <div class="options-table-string">
                                        <input type="text" name="options_exist[{{ $item->id }}][text]" id="options_exist_text_{{ $item->id }}" data-option-text value="{{ $item->text }}">
                                    </div>
                                    <div class="options-table-string">
                                        <input type="checkbox" name="options_exist[{{ $item->id }}][selected]" id="options_exist_selected_{{ $item->id }}" data-option-selected @if ($item->selected == 1) checked @endif>
                                    </div>
                                    <div class="options-table-string">
                                        <input type="checkbox" name="options_exist[{{ $item->id }}][disabled]" id="option_exist_disabled_{{ $item->id }}" data-option-disabled @if ($item->disabled == 1) checked @endif>
                                    </div>
                                    <div class="options-table-string">
                                        <input type="checkbox" name="options_exist[{{ $item->id }}][deleted]" id="options_exist_deleted_{{ $item->id }}" data-option-deleted>
                                    </div>
                                    <div class="options-table-string"></div>
                                </div>
                                @endforeach
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
                                        <input type="checkbox" name="options_new[0][deleted]" id="option_new_deleted_0" data-option-deleted>
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
                <button type="submit" class="default-btn success-button">{{ __('elfcms::default.submit') }}</button>
                <button type="submit" name="submit" value="save_and_close" class="default-btn alternate-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.forms.show', $form) }}" class="default-btn">{{ __('elfcms::default.cancel') }}</a>
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
