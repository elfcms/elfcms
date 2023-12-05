@extends('elfcms::admin.layouts.fragment')

@section('fragmentpage-content')

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
        <h3>{{ __('elfcms::default.edit_item') }} {{$item->id}}</h3>
        <form action="{{ route('admin.fragment.items.update',$item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="code">{{ __('elfcms::default.code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="code" id="code" data-isslug value="{{ $item->code }}">
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <input type="checkbox" data-text-id="title" data-slug-id="code" class="autoslug" checked>
                            <div class="autoslug-button"></div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" value="{{ $item->title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="text">{{ __('elfcms::default.text') }}</label>
                    <div class="input-wrapper">
                        <textarea name="text" id="text" cols="30" rows="10">{{ $item->text }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="image">{{ __('elfcms::default.image') }}</label>
                    <div class="input-wrapper">
                        <x-elfcms-input-image code="image" value="{{$item->image}}" />
                        {{-- <input type="hidden" name="image_path" id="image_path" value="{{$item->image}}">
                        <div class="image-button">
                            <div class="delete-image @if (empty($item->image)) hidden @endif">&#215;</div>
                            <div class="image-button-img">
                            @if (!empty($item->image))
                                <img src="{{ asset($item->image) }}" alt="Image">
                            @else
                                <img src="{{ asset('/vendor/elfcms/basic/admin/images/icons/upload.png') }}" alt="Upload file">
                            @endif
                            </div>
                            <div class="image-button-text">
                            @if (!empty($item->image))
                                {{ __('elfcms::default.change_file') }}
                            @else
                                {{ __('elfcms::default.choose_file') }}
                            @endif
                            </div>
                            <input type="file" name="image" id="image">
                        </div> --}}
                    </div>
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
                                @foreach ($item->options as $option)
                                <div class="options-table-string-line" data-line="{{$option->id}}">
                                    <div class="options-table-string">
                                        <select name="options_exist[{{$option->id}}][type]" id="option_exist_type_{{$option->id}}" data-option-type>
                                        @foreach ($data_types as $type)
                                            <option value="{{ $type->id }}" @if($type->id == $option->data_type_id) selected @endif>{{ $type->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="options-table-string">
                                        <input type="text" name="options_exist[{{$option->id}}][name]" id="option_exist_name_{{$option->id}}" data-option-name data-isslug value="{{ $option->name }}">
                                    </div>
                                    <div class="options-table-string">
                                        <input type="text" name="options_exist[{{$option->id}}][value]" id="option_exist_value_{{$option->id}}" data-option-value value="{{ $option->value }}">
                                    </div>
                                    <div class="options-table-string">
                                        <input type="checkbox" name="options_exist[{{ $option->id }}][deleted]" id="options_exist_disabled_{{ $option->id }}" data-option-deleted>
                                    </div>
                                </div>
                                @endforeach
                                <div class="options-table-string-line" data-line="0">
                                    <div class="options-table-string">
                                        <select name="options_new[0][type]" id="option_new_type_0" data-option-type>
                                        @foreach ($data_types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="options-table-string">
                                        <input type="text" name="options_new[0][name]" id="option_new_name_0" data-option-name data-isslug>
                                    </div>
                                    <div class="options-table-string">
                                        <input type="text" name="options_new[0][value]" id="option_new_value_0" data-option-value>
                                    </div>
                                    <div class="options-table-string">
                                        <input type="checkbox" name="options_new[0][deleted]" id="option_new_disabled_0" data-option-deleted>
                                    </div>
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
