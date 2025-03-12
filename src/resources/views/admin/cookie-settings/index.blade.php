@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="item-form">
        <h2>{{ __('elfcms::default.edit_cookie_settings') }}</h2>
        <form action="{{ route('admin.cookie-settings.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <h3>{{ __('elfcms::default.notification_bar') }}</h3>
                <div class="input-box">
                    <label for="active">
                        {{ __('elfcms::default.active') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="active" id="active" checked="{{ $settings->active }}" />
                </div>
                <div class="input-box">
                    <label for="use_default_text">
                        {{ __('elfcms::default.use_default_text') }}
                        ({{ __('elfcms::default.field_below_can_be_left_blank') }})
                    </label>
                    <x-elfcms::ui.checkbox.switch name="use_default_text" id="use_default_text"
                        checked="{{ $settings->use_default_text }}" />
                </div>
                <div class="input-box">
                    <label for="text">{{ __('elfcms::default.text') }}</label>
                    <div class="input-wrapper">
                        <textarea name="text" id="text" cols="30" rows="10">{{ $settings->text }}</textarea>
                    </div>
                </div>
                {{-- <div class="input-box">
                    <label for="text">{{ __('elfcms::default.text') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="privacy_path" id="privacy_path" value="{{ $settings->privacy_path }}">
                    </div>
                </div> --}}
                <div class="input-box">
                    <label for="text">{{ __('elfcms::default.cookie_lifetime') }}</label>
                    <div class="input-wrapper">
                        <input type="number" min="0" name="cookie_lifetime" id="cookie_lifetime"
                            value="{{ $cookie_lifetime }}">
                    </div>
                </div>
                <div class="cookie-categories-box">
                    <h3>{{ __('elfcms::default.categories') }}</h3>
                    <div class="input-box">
                        <div></div>
                        <div class="cookie-category-list">
                            @forelse ($categories as $category)
                                <div class="input-box cookie-category-box">
                                    <input type="text" name="category[{{ $category->id }}]"
                                        value="{{ $category->name }}">
                                    <label for="category_required_{{ $category->id }}">
                                        {{ __('elfcms::default.required') }}
                                    </label>
                                    <x-elfcms::ui.checkbox.switch name="category_required[{{ $category->id }}]"
                                        id="category_required_{{ $category->id }}" />
                                    <label for="category_remove_{{ $category->id }}">
                                        {{ __('elfcms::default.delete') }}
                                    </label>
                                    <x-elfcms::ui.checkbox.switch name="category_remove[{{ $category->id }}]"
                                        id="category_remove_{{ $category->id }}" />
                                </div>
                            @empty
                                <div class="cookie-category-box">
                                    <input type="text" name="category_new[0]" value="">
                                    <label for="category_new_required_0">{{ __('elfcms::default.required') }}</label>
                                    <x-elfcms::ui.checkbox.switch name="category_new_required[0]"
                                        id="category_new_required_0" />
                                    <label for="category_new_required_0">{{ __('elfcms::default.delete') }}</label>
                                    <x-elfcms::ui.checkbox.switch name="category_new_remove[0]" id="category_new_remove_0"
                                        color="var(--red-color)" />
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="input-box">
                    <div></div>
                    <div class="cookie-add-button">
                        <button id="cookieCategorieAdd" type="button"
                            class="button simple-button">{{ __('elfcms::default.create_category') }}</button>
                    </div>
                </div>

            </div>
            <div class="button-box single-box">
                <button type="submit"
                    class="button color-text-button green-button">{{ __('elfcms::default.submit') }}</button>
            </div>
        </form>
    </div>
    <script>
        runEditor('#text');
        let cookieCategoryNum = 0;

        function addCookieCategory(e) {
            e.preventDefault();
            const cookieCategoryList = document.querySelector('.cookie-category-list');
            if (cookieCategoryList) {
                cookieCategoryNum++;
                let newBox = `
        <div class="cookie-category-box">
            <input type="text" name="category_new[${cookieCategoryNum}]" value="">

            <label for="category_required_${cookieCategoryNum}">
                {{ __('elfcms::default.required') }}
            </label>
            <div class="switchbox">
                <input type="checkbox" name="category_new_required[${cookieCategoryNum}]" id="category_new_required_${cookieCategoryNum}" value="1" title="">
                <i></i>
            </div>

            <label for="category_remove_${cookieCategoryNum}">
                {{ __('elfcms::default.delete') }}
            </label>
            <div class="switchbox" style="color:var(--red-color)">
                <input type="checkbox" name="category_new_remove[${cookieCategoryNum}]" id="category_new_remove_${cookieCategoryNum}" value="1" title="">
                <i></i>
            </div>

        </div>
        `;
                cookieCategoryList.insertAdjacentHTML('beforeend', newBox);
            }
        }
        const cookieCategorieAdd = document.querySelector('#cookieCategorieAdd');
        if (cookieCategorieAdd) {
            cookieCategorieAdd.addEventListener('click', addCookieCategory);
        }
    </script>
@endsection
