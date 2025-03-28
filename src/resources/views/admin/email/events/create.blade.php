@extends('elfcms::admin.layouts.main')

@section('pagecontent')

    {{-- @if (Session::has('eeventedited'))
        <div class="alert alert-success">{{ Session::get('eeventedited') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="errors-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif --}}

    <div class="item-form">
        <h2>{{ __('elfcms::default.create_email_event') }}</h2>
        <form action="{{ route('admin.email.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="code">{{ __('elfcms::default.code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="code" id="code" autocomplete="off">
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug checked="true" textid="name" slugid="code" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="subject">{{ __('elfcms::default.subject') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="subject" id="subject" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="description">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="description" id="description" autocomplete="off">
                    </div>
                </div>

                <div class="input-box colored">
                    <label for="content">{{ __('elfcms::default.content') }}</label>
                    <div class="input-wrapper">
                        <textarea type="text" name="content" id="content" cols="30" rows="6"></textarea>
                    </div>
                </div>

                <div class="input-box colored" id="paramsbox">
                    <label>{{ __('elfcms::default.parameters') }}</label>
                    <div class="input-wrapper">
                        <div>
                            <div class="input-params-table">
                                <div class="params-table-head-line">
                                    <div class="params-table-head">
                                        {{ __('elfcms::default.name') }}
                                    </div>
                                    <div class="params-table-head">
                                        {{ __('elfcms::default.value') }}
                                    </div>
                                    <div class="params-table-head"></div>
                                </div>
                                <div class="params-table-string-line" data-line="0">
                                    <div class="params-table-string">
                                        <input type="text" name="params_new[0][name]" id="param_new_name_0" data-param-name>
                                    </div>
                                    <div class="params-table-string">
                                        <input type="text" name="params_new[0][value]" id="param_new_value_0" data-param-value>
                                    </div>
                                    <div class="params-table-string"></div>
                                </div>
                            </div>
                            <button type="button" class="button simple-button" id="addparamline">{{ __('elfcms::default.add_parameter') }}</button>
                        </div>
                    </div>
                </div>

                <div class="input-box colored">
                    <label for="view">{{ __('elfcms::default.view') }}</label>
                    <div class="input-wrapper">
                        <select name="view" id="view">
                            <option value="">--default--</option>
                        @foreach ($views as $view)
                            <option value="{{ $view }}">{{ $view }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <h3>Fields</h3>
            <div class="colored-rows-box">
            {{-- @foreach ($event->fields() as $fieldName => $field)
                <div class="input-box colored">
                    <label for="{{ $fieldName }}">{{ $fieldName }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="{{ $fieldName }}" id="{{ $fieldName }}" autocomplete="off" value="{{ $event->description }}">
                    </div>
                </div>
            @endforeach --}}
            @foreach ($fields as $fieldName)
                <div class="input-box colored">
                    <label for="{{ $fieldName }}" class="capitalize">{{ $fieldName }}</label>
                    <div class="input-wrapper">
                        <select name="{{ $fieldName }}" id="{{ $fieldName }}">
                            <option value="">None</option>
                        @foreach ($addresses as $address)
                            <option value="{{$address->id}}" @if (isset($fields[$fieldName]) && $fields[$fieldName]->email==$address->email) selected @endif>{{$address->name}} &lt;{{$address->email}}&gt;</option>
                        @endforeach
                        </select>
                    </div>
                </div>
            @endforeach
            </div>

            <div class="button-box single-box">
                <button type="submit" class="button color-text-button success-button">{{ __('elfcms::default.submit') }}</button>
                <a href="{{ route('admin.email.events') }}" class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
        eventParamBoxInit('#addparamline')
        //add editor
        //new Gnommy('#content')
        runEditor('#content')
    </script>

@endsection
