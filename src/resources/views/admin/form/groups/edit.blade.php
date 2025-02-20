@extends('elfcms::admin.layouts.form')

@section('formpage-content')

    @if (Session::has('groupedited'))
        <div class="alert alert-success">{{ Session::get('groupedited') }}</div>
    @endif
    @if (Session::has('groupcreated  '))
        <div class="alert alert-success">{{ Session::get('groupcreated') }}</div>
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
        <h3>{{ __('elfcms::default.edit_form_field_group') }}</h3>
        <form action="{{ route('admin.forms.groups.update',['form'=>$form,'group'=>$group]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label>{{ __('elfcms::default.form') }}</label>
                    <div class="input-wrapper">
                        <input type="hidden" name="form_id" id="form_id" value="{{ $form->id }}">
                        #{{ $form->id }} {{ $form->title ?? $form->name ?? $form->code }}
                    </div>
                </div>
                <div class="input-box colored">
                    <div class="checkbox-wrapper">
                        <div class="checkbox-switch-wrapper">
                            <div class="checkbox-switch blue">
                                <input type="checkbox" name="active" id="active" value="1" @if ($group->active == 1) checked @endif>
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
                        <input type="text" name="title" id="title" autocomplete="off" value="{{ $group->title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="code">{{ __('elfcms::default.code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="code" id="code" autocomplete="off" data-isslug value="{{ $group->code }}">
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
                        <input type="text" name="name" id="name" autocomplete="off" data-isslug value="{{ $group->name }}">
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
                        <textarea name="description" id="description" cols="30" rows="3">{{ $group->description }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="position">{{ __('elfcms::default.position') }}</label>
                    <div class="input-wrapper">
                        <input type="number" name="position" id="position" autocomplete="off" value="{{ $group->position }}">
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="button success-button">{{ __('elfcms::default.submit') }}</button>
                <button type="submit" name="submit" value="save_and_close" class="button alternate-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.forms.show', $form) }}" class="button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
    autoSlug('.autoslug')
    inputSlugInit()
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
