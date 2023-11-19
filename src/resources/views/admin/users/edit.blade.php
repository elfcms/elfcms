@extends('elfcms::admin.layouts.users')

@section('userpage-content')

    @if (Session::has('useredited'))
        <div class="alert alert-success">{{ Session::get('useredited') }}</div>
    @endif
    @error('email')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @error('password')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="errors-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
<div class="user-form item-form">
    <h3>{{ __('elfcms::default.edit_user') }}{{ $user->id }}</h3>
    <form action="{{ route('admin.users.update',$user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="colored-rows-box">
            <input type="hidden" name="id" id="id" value="{{ $user->id }}">
            <div class="input-box colored">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <input type="email" name="email" id="email" autocomplete="off" value="{{ $user->email }}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="password">{{ __('elfcms::default.password') }}</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" autocomplete="off" value="">
                </div>
            </div>
            <div class="input-box colored">
                <label for="password_confirmation">{{ __('elfcms::default.confirm_password') }}</label>
                <div class="input-wrapper">
                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="off" value="">
                </div>
            </div>

            <div class="input-box colored">
                <div class="checkbox-wrapper">
                    <div class="checkbox-switch-wrapper">
                        <div class="checkbox-switch blue">
                            <input type="checkbox" name="is_confirmed" id="is_confirmed" value="1" @if ($user->is_confirmed == 1) checked @endif>
                            <i></i>
                        </div>
                        <label for="is_confirmed">
                            {{ __('elfcms::default.confirmed') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="roles-list">
                <h4>{{ __('elfcms::default.roles') }}</h4>
                @foreach ($roles as $role)
                <div class="input-box">
                    <div class="checkbox-wrapper">
                        <div class="checkbox-switch-wrapper">
                            <div class="checkbox-switch blue">
                                <input type="checkbox" name="role[]" id="role_{{ $role->id }}" value="{{ $role->id }}" @if (in_array($role->id,$user_roles)) checked @endif>
                                <i></i>
                            </div>
                            <label for="role_{{ $role->id }}">
                                {{ $role->name }}
                            </label>
                        </div>
                    </div>
                    {{-- <div class="checkbox-wrapper low-wrapper">
                        <div class="checkbox-inner">
                            <input
                                type="checkbox"
                                name="role[]"
                                id="role_{{ $role->id }}"
                                value="{{ $role->id }}"
                                @if (in_array($role->id,$user_roles))
                                checked
                                @endif
                            >
                            <i></i>
                            <label for="role_{{ $role->id }}">
                                {{ $role->name }}
                            </label>
                        </div>
                    </div> --}}
                </div>
                @endforeach
            </div>
        </div>

        <h4>{{ __('elfcms::default.user_data') }}</h4>

        <div class="colored-rows-box">
            <div class="input-box colored">
                <label for="first_name">{{ __('elfcms::default.first_name') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="data[first_name]" id="first_name" value="{{$user->data['first_name']}}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="second_name">{{ __('elfcms::default.second_name') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="data[second_name]" id="second_name" value="{{$user->data['second_name']}}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="last_name">{{ __('elfcms::default.last_name') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="data[last_name]" id="last_name" value="{{$user->data['last_name']}}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="zip_code">{{ __('elfcms::default.zip_code') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="data[zip_code]" id="zip_code" value="{{$user->data['zip_code']}}" data-type="number">
                </div>
            </div>
            <div class="input-box colored">
                <label for="country">{{ __('elfcms::default.country') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="data[country]" id="country" value="{{$user->data['country']}}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="city">{{ __('elfcms::default.city') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="data[city]" id="city" value="{{$user->data['city']}}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="street">{{ __('elfcms::default.street') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="data[street]" id="street" value="{{$user->data['street']}}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="house">{{ __('elfcms::default.house') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="data[house]" id="house" value="{{$user->data['house']}}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="full_address">{{ __('elfcms::default.full_address') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="data[full_address]" id="full_address" value="{{$user->data['full_address']}}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="phone_code">{{ __('elfcms::default.phone_code') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="data[phone_code]" id="phone_code" value="{{$user->data['phone_code']}}" data-type="number">
                </div>
            </div>
            <div class="input-box colored">
                <label for="phone_number">{{ __('elfcms::default.phone_number') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="data[phone_number]" id="phone_number" value="{{$user->data['phone_number']}}" data-type="number">
                </div>
            </div>
            <div class="input-box colored">
                <label for="photo">{{ __('elfcms::default.photo') }}</label>
                <div class="input-wrapper">
                    <input type="hidden" name="data[photo_path]" id="photo_path" value="{{$user->data['photo']}}">
                    <div class="image-button">
                        <div class="image-button-img">
                        @if (!empty($user->data['photo']))
                            <img src="{{ asset($user->data['photo']) }}" alt="User avatar">
                        @else
                            <img src="{{ asset('/elfcms/admin/images/icons/upload.png') }}" alt="Upload file">
                        @endif
                        </div>
                        <div class="image-button-text">
                        @if (!empty($user->data['photo']))
                            {{ __('elfcms::default.change_file') }}
                        @else
                            {{ __('elfcms::default.choose_file') }}
                        @endif
                        </div>
                        <input type="file" name="data[photo]" id="photo">
                    </div>
                </div>
            </div>
            <div class="input-box colored">
                <label for="thumbnail">{{ __('elfcms::default.thumbnail') }}</label>
                <div class="input-wrapper">
                    <input type="hidden" name="data[thumbnail_path]" id="thumbnail_path" value="{{$user->data['thumbnail']}}">
                    <div class="image-button">
                        <div class="image-button-img">
                        @if (!empty($user->data['thumbnail']))
                            <img src="{{ asset($user->data['thumbnail']) }}" alt="User avatar thumbnail">
                        @else
                            <img src="{{ asset('/elfcms/admin/images/icons/upload.png') }}" alt="Upload file">
                        @endif
                        </div>
                        <div class="image-button-text">
                        @if (!empty($user->data['thumbnail']))
                            {{ __('elfcms::default.change_file') }}
                        @else
                            {{ __('elfcms::default.choose_file') }}
                        @endif
                        </div>
                        <input type="file" name="data[thumbnail]" id="thumbnail">
                    </div>
                </div>
            </div>
        </div>
        <div class="button-box single-box">
            <button type="submit" class="default-btn success-button">{{ __('elfcms::default.submit') }}</button>
            <a href="{{ route('admin.users') }}" class="default-btn">{{ __('elfcms::default.cancel') }}</a>
        </div>
    </form>
    <script>
    const photoInput = document.querySelector('#photo')
    if (photoInput) {
        inputFileImg(photoInput)
    }
    const thumbInput = document.querySelector('#thumbnail')
    if (thumbInput) {
        inputFileImg(thumbInput)
    }
    const passInput = document.querySelector('#password')
    if (passInput) {
        setTimeout(() => {
            passInput.value = ''
        }, 1000);
    }
    </script>
@endsection
