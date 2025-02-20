@extends('elfcms::admin.layouts.email')

@section('emailpage-content')

    @if (Session::has('eaddredited'))
        <div class="alert alert-success">{{ Session::get('eaddredited') }}</div>
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
        <h3>{{ __('elfcms::default.edit_email_address') }} #{{ $address->id }}</h3>
        <form action="{{ route('admin.email.addresses.update',$address->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off" value="{{ $address->name }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="email">{{ __('elfcms::default.email_address') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="email" id="email" autocomplete="off" value="{{ $address->email }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="description">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="description" id="description" autocomplete="off" value="{{ $address->description }}">
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="button success-button">{{ __('elfcms::default.submit') }}</button>
                <a href="{{ route('admin.email.addresses') }}" class="button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>

@endsection
