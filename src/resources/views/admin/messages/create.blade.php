@extends('elfcms::admin.layouts.message')

@section('message-content')

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
        <h3>{{ __('elfcms::default.create_message') }}</h3>
        <form action="{{ route('admin.messages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <div class="checkbox-switch-wrapper">
                        <div class="checkbox-switch blue">
                            <input type="checkbox" name="active" id="active" value="1" @if(!empty($message->active)) checked @endif>
                            <i></i>
                        </div>
                        <label for="active">
                            {{ __('elfcms::default.active') }}
                        </label>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" value="{{ $message->name ?? '' }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="code">{{ __('elfcms::default.code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="code" id="code" value="{{ $message->code ?? '' }}">
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <input type="checkbox" data-text-id="name" data-slug-id="code" data-slug-space="_" class="autoslug" checked>
                            <div class="autoslug-button"></div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="text">{{ __('elfcms::default.text') }}</label>
                    <div class="input-wrapper">
                        <textarea  name="text" id="text">{{ $message->text ?? '' }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label>{{ __('elfcms::default.period') }}</label>
                    <div class="input-wrapper">
                        <input type="date" name="date_from" id="date_from" value="{{ $message->date_from ?? '' }}">
                        <span class="input-period-separator"></span>
                        <input type="date" name="date_to" id="date_to" value="{{ $message->date_to ?? '' }}">
                    </div>
                </div>
            </div>

            <div class="button-box single-box">
                <button type="submit" class="button submit-button">{{ __('elfcms::default.submit') }}</button>
                <button type="submit" name="submit" value="save_and_close" class="button alternate-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.messages.index') }}" class="button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
    autoSlug('.autoslug')
    inputSlugInit()
    </script>


@endsection
