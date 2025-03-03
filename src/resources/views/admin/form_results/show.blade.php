@extends('elfcms::admin.layouts.form')

@section('formpage-content')

    {{-- @if (Session::has('success'))
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
    @endif --}}

    <div class="item-form">
        <h2>{{ __('elfcms::default.form_result') }} #{{ $result->id }}</h2>
        {{-- <form action="{{ route('admin.forms.update',$form->id) }}" method="POST" enctype="multipart/form-data"> --}}
        <div>
            <div class="colored-rows-box">
                @foreach ($fields as $field)
                @if(isset($result->data[$field->name]))
                <div class="input-box colored">
                    <span class="input-box-label">{{ $field->title }}</span>
                    <div class="input-wrapper">
                        <span>{{ $field->type->name == 'checkbox' ? ($result->data[$field->name] == 1 ? '✓' : '-') : $result->data[$field->name] }}</span>
                    </div>
                </div>
                @endif
                @endforeach

            </div>
            {{-- <div class="button-box single-box">
                <button type="submit" class="button success-button">{{ __('elfcms::default.submit') }}</button>
                <button type="submit" name="submit" value="save_and_open" class="button alternate-button">{{ __('elfcms::default.save_and_open') }}</button>
                <button type="submit" name="submit" value="save_and_close" class="button alternate-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.forms.index') }}" class="button">{{ __('elfcms::default.cancel') }}</a>
            </div> --}}
        </div>
        {{-- </form> --}}
    </div>

@endsection
