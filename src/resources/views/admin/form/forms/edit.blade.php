@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.forms.index') }}" class="button round-button theme-button"
            style="color:var(--default-color);">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.back') }}
            </span>
        </a>
    </div>
    <div class="item-form">
        <h2>{{ __('elfcms::default.edit_form') }}</h2>
        <form action="{{ route('admin.forms.update', $form->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="active">
                        {{ __('elfcms::default.active') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="active" id="active" checked="{{ $form->active }}" />
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" autocomplete="off" value="{{ $form->title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="code">{{ __('elfcms::default.code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="code" id="code" autocomplete="off" data-isslug
                            value="{{ $form->code }}">
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug textid="title" slugid="code" slugspace="_" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off" data-isslug
                            value="{{ $form->name }}">
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug textid="title" slugid="name" checked="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="action">{{ __('elfcms::default.action') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="action" id="action" autocomplete="off" value="{{ $form->action }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="enctype">{{ __('elfcms::default.enctype') }}</label>
                    <div class="input-wrapper">
                        <select name="enctype" id="enctype">
                            <option value="">None</option>
                            @foreach ($enctypes as $enctype)
                                <option value="{{ $enctype }}" @if ($form->enctype == $enctype) selected @endif>
                                    {{ $enctype }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="description">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="3">{{ $form->description }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="redirect_to">{{ __('elfcms::default.redirect_to') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="redirect_to" id="redirect_to" autocomplete="off"
                            value="{{ $form->redirect_to }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="success_text">{{ __('elfcms::default.success_text') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="success_text" id="success_text" autocomplete="off"
                            value="{{ $form->success_text }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="error_text">{{ __('elfcms::default.error_text') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="error_text" id="error_text" autocomplete="off"
                            value="{{ $form->error_text }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="submit_button">{{ __('elfcms::default.submit_button') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="submit_button" id="submit_button" autocomplete="off"
                            value="{{ $form->submit_button }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="submit_name">{{ __('elfcms::default.submit_name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="submit_name" id="submit_name" autocomplete="off"
                            value="{{ $form->submit_name }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="submit_title">{{ __('elfcms::default.submit_title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="submit_title" id="submit_title" autocomplete="off"
                            value="{{ $form->submit_title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="submit_value">{{ __('elfcms::default.submit_value') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="submit_value" id="submit_value" autocomplete="off"
                            value="{{ $form->submit_value }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="reset_button">{{ __('elfcms::default.reset_button') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="reset_button" id="reset_button" autocomplete="off"
                            value="{{ $form->reset_button }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="reset_title">{{ __('elfcms::default.reset_title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="reset_title" id="reset_title" autocomplete="off"
                            value="{{ $form->reset_title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="reset_value">{{ __('elfcms::default.reset_value') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="reset_value" id="reset_value" autocomplete="off"
                            value="{{ $form->reset_value }}">
                    </div>
                </div>

                <div class="input-box colored">
                    <label for="event_id">{{ __('elfcms::default.events') }}</label>
                    <div class="input-wrapper">
                        <select name="event_id" id="event_id">
                            <option value="">--none--</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" @if ($event->id == $form->event_id) selected @endif>
                                    {{ $event->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <div class="button-box single-box">
                <button type="submit" name="submit" value="save"
                    class="button color-text-button success-button">{{ __('elfcms::default.save') }}</button>
                <button type="submit" name="submit" value="save_and_open"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_open') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.forms.index') }}"
                    class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
        inputSlugInit()
    </script>
@endsection
