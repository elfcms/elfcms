@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="item-form">
        <h2>{{ __('elfcms::default.create_page') }}</h2>
        <form action="{{ route('admin.page.pages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="active">
                        {{ __('elfcms::default.active') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="active" id="active" checked="true" />
                </div>
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('elfcms::default.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" autocomplete="off">
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug textid="name" slugid="slug" checked="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="is_dynamic">
                        {{ __('elfcms::default.display_by_url') . ': ' . config('elfcms.elfcms.page_path') }}/&lt;slug&gt;
                    </label>
                    <x-elfcms::ui.checkbox.switch name="is_dynamic" id="is_dynamic" />
                </div>
                <div class="input-box colored">
                    <label for="path">{{ __('elfcms::default.path') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="path" id="path" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="image">{{ __('elfcms::default.image') }}</label>
                    <div class="input-wrapper">
                        <x-elf-input-file value="" :params="['name' => 'image']" :download="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="content">{{ __('elfcms::default.text') }}</label>
                    <div class="input-wrapper">
                        <textarea name="content" id="content" cols="30" rows="10"></textarea>
                    </div>
                </div>
                {{-- <div class="input-box colored">
                    <label for="template">{{ __('elfcms::default.template') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="template" id="template" autocomplete="off">
                    </div>
                </div> --}}
                <div class="input-box colored">
                    <label for="template">{{ __('elfcms::default.template') }}</label>
                    <div class="input-wrapper">
                        <select name="template" id="template">
                            <option value="">--default--</option>
                            @foreach ($templates as $template)
                                <option value="{{ $template }}">
                                    {{ $template }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_keywords">{{ __('elfcms::default.meta_keywords') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3"></textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_description">{{ __('elfcms::default.meta_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="button color-text-button green-button">{{ __('elfcms::default.submit') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button blue-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.page.pages') }}" class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
        checkInactive()
        //add editor
        runEditor('#content')
    </script>
@endsection
