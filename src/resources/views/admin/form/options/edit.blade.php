@extends('elfcms::admin.layouts.form')

@section('formpage-content')

    @if (Session::has('formedited'))
        <div class="alert alert-success">{{ Session::get('formedited') }}</div>
    @endif
    @if (Session::has('formcreated  '))
        <div class="alert alert-success">{{ Session::get('formcreated') }}</div>
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
        <h3>{{ __('elfcms::default.edit_form') }}</h3>
        <form action="{{ route('admin.form.forms.update',$form->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" autocomplete="off" value="{{ $form->title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="code">{{ __('elfcms::default.code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="code" id="code" autocomplete="off" data-isslug value="{{ $form->code }}">
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <input type="checkbox" data-text-id="title" data-slug-id="code" data-slug-space="_" class="autoslug" >
                            <div class="autoslug-button"></div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off" data-isslug value="{{ $form->name }}">
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <input type="checkbox" data-text-id="title" data-slug-id="name" class="autoslug" >
                            <div class="autoslug-button"></div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="description">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="description" id="description" autocomplete="off" value="{{ $form->description }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="submit_button">{{ __('elfcms::default.submit_button') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="submit_button" id="submit_button" autocomplete="off" value="{{ $form->submit_button }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="submit_name">{{ __('elfcms::default.submit_name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="submit_name" id="submit_name" autocomplete="off" value="{{ $form->submit_name }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="submit_title">{{ __('elfcms::default.submit_title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="submit_title" id="submit_title" autocomplete="off" value="{{ $form->submit_title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="submit_value">{{ __('elfcms::default.submit_value') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="submit_value" id="submit_value" autocomplete="off" value="{{ $form->submit_value }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="reset_button">{{ __('elfcms::default.reset_button') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="reset_button" id="reset_button" autocomplete="off" value="{{ $form->reset_button }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="reset_title">{{ __('elfcms::default.reset_title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="reset_title" id="reset_title" autocomplete="off" value="{{ $form->reset_title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="reset_value">{{ __('elfcms::default.reset_value') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="reset_value" id="reset_value" autocomplete="off" value="{{ $form->reset_value }}">
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
    </script>

@endsection
