@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    {{-- @if (Session::has('success'))
        <x-elf-notify type="success" title="{{ __('elfcms::default.success') }}" text="{{ Session::get('success') }}" />
    @endif
    @if ($errors->any())
        <x-elf-notify type="error" title="{{ __('elfcms::default.error') }}" text="{!! '<ul><li>' . implode('</li><li>', $errors->all()) . '</li></ul>' !!}" />
    @endif --}}
    <div class="user-form item-form">
        <h2>{{ __('elfcms::default.edit_user') }}{{ $user->id }}</h2>
        <form action="{{ route('admin.user.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
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
                        <x-elfcms::ui.input.password name="password" id="password" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="password_confirmation">{{ __('elfcms::default.confirm_password') }}</label>
                    <div class="input-wrapper">
                        <x-elfcms::ui.input.password name="password_confirmation" id="password_confirmation" />
                    </div>
                </div>

                <div class="input-box colored">
                    <label for="is_confirmed">
                        {{ __('elfcms::default.confirmed') }}
                    </label>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.switch name="is_confirmed" id="is_confirmed" checked="{{$user->is_confirmed == 1}}" />
                    </div>
                </div>

                <div class="roles-list">
                    <h3>{{ __('elfcms::default.roles') }}</h3>
                    @foreach ($roles as $role)
                        <div class="input-box">
                            <label for="role_{{ $role->id }}">
                                {{ $role->name }}
                            </label>
                            <div class="input-wrapper">
                                <x-elfcms::ui.checkbox.switch name="role[]" id="role_{{ $role->id }}" checked="{{in_array($role->id, $user_roles)}}" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <h3>{{ __('elfcms::default.user_data') }}</h3>

            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="first_name">{{ __('elfcms::default.first_name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="data[first_name]" id="first_name"
                            value="{{ $user->data['first_name'] }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="second_name">{{ __('elfcms::default.second_name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="data[second_name]" id="second_name"
                            value="{{ $user->data['second_name'] }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="last_name">{{ __('elfcms::default.last_name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="data[last_name]" id="last_name" value="{{ $user->data['last_name'] }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="zip_code">{{ __('elfcms::default.zip_code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="data[zip_code]" id="zip_code" value="{{ $user->data['zip_code'] }}"
                            data-type="number">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="country">{{ __('elfcms::default.country') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="data[country]" id="country" value="{{ $user->data['country'] }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="city">{{ __('elfcms::default.city') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="data[city]" id="city" value="{{ $user->data['city'] }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="street">{{ __('elfcms::default.street') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="data[street]" id="street" value="{{ $user->data['street'] }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="house">{{ __('elfcms::default.house') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="data[house]" id="house" value="{{ $user->data['house'] }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="full_address">{{ __('elfcms::default.full_address') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="data[full_address]" id="full_address"
                            value="{{ $user->data['full_address'] }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="phone_code">{{ __('elfcms::default.phone_code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="data[phone_code]" id="phone_code"
                            value="{{ $user->data['phone_code'] }}" data-type="number">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="phone_number">{{ __('elfcms::default.phone_number') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="data[phone_number]" id="phone_number"
                            value="{{ $user->data['phone_number'] }}" data-type="number">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="photo">{{ __('elfcms::default.photo') }}</label>
                    <div class="input-wrapper">
                        <x-elf-input-file value="{{ $user->data['photo'] }}" :params="['name'=>'data[photo]','value_name'=>'data[photo_path]']" :download="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="thumbnail">{{ __('elfcms::default.thumbnail') }}</label>
                    <div class="input-wrapper">
                        <x-elf-input-file value="{{ $user->data['thumbnail'] }}" :params="['name'=>'data[thumbnail]','value_name'=>'data[thumbnail_path]']" :download="true" />
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="button color-text-button success-button">{{ __('elfcms::default.save') }}</button>
                <a href="{{ route('admin.user.users') }}" class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    @endsection
