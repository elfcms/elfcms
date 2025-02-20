@extends('elfcms::admin.layouts.main')

@section('pagecontent')

<div class="big-container">
    @if (Session::has('settingedited'))
        <div class="alert alert-success">{{ Session::get('settingedited') }}</div>
    @endif
    @if (Session::has('settingcreated  '))
        <div class="alert alert-success">{{ Session::get('settingcreated') }}</div>
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
        <h3>{{ __('elfcms::default.edit_cookie_settings') }}</h3>
        <form action="{{ route('admin.cookie-settings.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <h4>{{ __('elfcms::default.notification_bar') }}</h4>
                <div class="input-box">
                    <x-elfcms-input-checkbox code="active" label="{{ __('elfcms::default.active') }}" :checked="$settings->active" style="blue" />
                </div>
                <div class="input-box">
                    <x-elfcms-input-checkbox code="use_default_text" label="{{ __('elfcms::default.use_default_text') }} ({{ __('elfcms::default.field_below_can_be_left_blank') }})" :checked="$settings->use_default_text" style="blue" />
                </div>
                <div class="input-box">
                    <label for="text">{{ __('elfcms::default.text') }}</label>
                    <div class="input-wrapper">
                        <textarea name="text" id="text" cols="30" rows="10">{{ $settings->text }}</textarea>
                    </div>
                </div>
                <div class="input-box">
                    <label for="text">{{ __('elfcms::default.text') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="privacy_path" id="privacy_path" value="{{ $settings->privacy_path }}">
                    </div>
                </div>
                <div class="input-box">
                    <label for="text">{{ __('elfcms::default.cookie_lifetime') }}</label>
                    <div class="input-wrapper">
                        <input type="number" min="0" name="cookie_lifetime" id="cookie_lifetime" value="{{ $cookie_lifetime }}">
                    </div>
                </div>
                <div class="cookie-categories-box">
                    <h4>{{ __('elfcms::default.categories') }}</h4>
                    <div class="cookie-category-list">
                    @forelse ($categories as $category)
                        <div class="input-box cookie-category-box">
                            <input type="text" name="category[{{ $category->id }}]" value="{{ $category->name }}">
                            <x-elfcms-input-checkbox code="category_required[{{ $category->id }}]" id="category_required_{{ $category->id }}" label="{{ __('elfcms::default.required') }}" style="blue" />
                            <x-elfcms-input-checkbox code="category_remove[{{ $category->id }}]" id="category_remove_{{ $category->id }}" label="{{ __('elfcms::default.delete') }}" style="red" />
                        </div>
                    @empty
                        <div class="cookie-category-box">
                            <input type="text" name="category_new[0]" value="">
                            <x-elfcms-input-checkbox code="category_new_required[0]" id="category_new_required_0" label="{{ __('elfcms::default.required') }}" style="blue" />
                            <x-elfcms-input-checkbox code="category_new_remove[0]" id="category_new_remove_0" label="{{ __('elfcms::default.delete') }}" style="red" />
                        </div>
                    @endforelse
                    </div>
                </div>
                <div class="cookie-add-button">
                    <button id="cookieCategorieAdd" type="button" class="button success-button icon-text-button light-icon plus-button">{{ __('elfcms::default.create_category') }}</button>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="button success-button">{{ __('elfcms::default.submit') }}</button>
            </div>
        </form>
    </div>
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
            <div class="checkbox-switch-wrapper">
                <div class="checkbox-switch blue">
                    <input type="checkbox" name="category_new_required[${cookieCategoryNum}]" id="category_new_required_${cookieCategoryNum}" value="1">
                    <i></i>
                </div>
                <label for="category_required_${cookieCategoryNum}">
                    {{ __('elfcms::default.required') }}
                </label>
            </div>
            <div class="checkbox-switch-wrapper">
                <div class="checkbox-switch red">
                    <input type="checkbox" name="category_new_remove[${cookieCategoryNum}]" id="category_new_remove_${cookieCategoryNum}" value="1">
                    <i></i>
                </div>
                <label for="category_remove_${cookieCategoryNum}">
                    {{ __('elfcms::default.delete') }}
                </label>
            </div>
        </div>
        `;
        cookieCategoryList.insertAdjacentHTML('beforeend', newBox);
    }
}
const cookieCategorieAdd = document.querySelector('#cookieCategorieAdd');
if (cookieCategorieAdd) {
    cookieCategorieAdd.addEventListener('click',addCookieCategory);
}
</script>
@endsection
