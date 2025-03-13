@extends('elfcms::admin.layouts.main')

@section('pagecontent')

    <div class="item-form">
        <h2>{{ __('elfcms::default.edit_field') }} #{{ $field->id }}</h2>
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
                    <label for="active">
                        {{ __('elfcms::default.active') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="active" id="active" checked="{{$field->active == 1}}" />
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
                        <x-elfcms::ui.checkbox.autoslug textid="title" slugid="name" checked="true" />
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
                    <label for="required">
                        {{ __('elfcms::default.field_is_required') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="required" id="required" checked="{{$field->required == 1}}" />
                </div>
                <div class="input-box colored">
                    <label for="disabled">
                        {{ __('elfcms::default.field_is_disabled') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="disabled" id="disabled" checked="{{$field->disabled == 1}}" />
                </div>
                <div class="input-box colored">
                    <label for="checked">
                        {{ __('elfcms::default.field_is_checked') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="checked" id="checked" checked="{{$field->checked == 1}}" />
                </div>
                <div class="input-box colored">
                    <label for="readonly">
                        {{ __('elfcms::default.readonly_field') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="readonly" id="readonly" checked="{{$field->readonly == 1}}" />
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
                            <button type="button" class="button simple-button" id="addoptionline">{{ __('elfcms::default.add_option') }}</button>
                        </div>
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
    autoSlug('.autoslug')
    inputSlugInit()

    fieldGroupInit()

    showOptionsSelect('select#type_id','#optionsbox')
    optionBoxInit()

    onlyOneCheckedInit('[data-option-selected]','[data-option-selected]')
    </script>

@endsection
