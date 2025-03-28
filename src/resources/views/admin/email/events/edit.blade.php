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
        <h2>{{ __('elfcms::default.edit_email_event') }} #{{ $event->id }}</h2>
        <form action="{{ route('admin.email.events.update',$event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off" value="{{ $event->name }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="code">{{ __('elfcms::default.code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="code" id="code" autocomplete="off" value="{{ $event->code }}">
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug checked="true" textid="name" slugid="code" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="subject">{{ __('elfcms::default.subject') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="subject" id="subject" autocomplete="off" value="{{ $event['subject'] }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="description">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="description" id="description" autocomplete="off" value="{{ $event->description }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="content">{{ __('elfcms::default.content') }}</label>
                    <div class="input-wrapper">
                        <textarea type="text" name="content" id="content" cols="30" rows="6">{{ $event->content }}</textarea>
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
                                @forelse ($params as $param => $value)
                                <div class="params-table-string-line" data-line="{{ $loop->index }}">
                                    <div class="params-table-string">
                                        <input type="text" name="params_new[{{ $loop->index }}][name]" id="param_new_name_{{ $loop->index }}" value="{{$param}}" data-param-name>
                                    </div>
                                    <div class="params-table-string">
                                        <input type="text" name="params_new[{{ $loop->index }}][value]" id="param_new_value_{{ $loop->index }}" value="{{$value}}" data-param-value>
                                    </div>
                                    <div class="params-table-string">
                                        <button type="button" class="button" onclick="eventParamDelete({{ $loop->index }})">&#215;</button>
                                    </div>
                                </div>
                                @empty
                                <div class="params-table-string-line" data-line="0">
                                    <div class="params-table-string">
                                        <input type="text" name="params_new[0][name]" id="param_new_name_0" data-param-name>
                                    </div>
                                    <div class="params-table-string">
                                        <input type="text" name="params_new[0][value]" id="param_new_value_0" data-param-value>
                                    </div>
                                    <div class="params-table-string">
                                        <button type="button" class="button" onclick="eventParamDelete(0)">&#215;</button>
                                    </div>
                                </div>
                                @endforelse


                            </div>
                            <button type="button" class="button simple-button" id="addparamline">{{ __('elfcms::default.add_parameter') }}</button>
                        </div>
                    </div>
                </div>

                <div class="input-box colored">
                    <label for="view">{{ __('elfcms::default.view') }}</label>
                    <div class="input-wrapper">
                        <select name="view" id="view">
                        @if (empty($event->view))
                            <option value="">--default--</option>
                        @elseif (!in_array($event->view, $views))
                            <option value="{{ $event->view }}">{{ $event->view }}</option>
                        @endif
                        @foreach ($views as $view)
                            <option value="{{ $view }}" @if ($view == $event->view) selected @endif>{{ $view }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <h4>Fields</h4>
            <div class="colored-rows-box">
            {{-- @foreach ($event->fields() as $fieldName => $field)
                <div class="input-box colored">
                    <label for="{{ $fieldName }}">{{ $fieldName }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="{{ $fieldName }}" id="{{ $fieldName }}" autocomplete="off" value="{{ $event->description }}">
                    </div>
                </div>
            @endforeach --}}
            @foreach ($event->emailFields as $fieldName)
                <div class="input-box colored">
                    <label for="{{ $fieldName }}" class="capitalize">{{ $fieldName }}</label>
                    <div class="input-wrapper">
                        {{-- <input type="text" name="{{ $fieldName }}" id="{{ $fieldName }}" autocomplete="off" value="@if(!empty($fields[$fieldName])) {{$fields[$fieldName]}} @endif"> --}}
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
        eventParamBoxInit('#addparamline', {{count($params)-1}})

        //add editor
        //runEditor('#content')
    </script>

@endsection
