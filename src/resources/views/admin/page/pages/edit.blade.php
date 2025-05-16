@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.page.pages') }}" class="button round-button theme-button" style="color:var(--default-color);">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.back') }}
            </span>
        </a>
    </div>
    <div class="item-form">
        <h2>{{ __('elfcms::default.edit_page') }} #{{ $pageData->id }}</h2>
        <form action="{{ route('admin.page.pages.update', $pageData->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <div class="input-box colored" id="module-box">
                    <label for="module">{{ __('elfcms::default.module') }}</label>
                    <div class="input-wrapper">
                        {{ $module['name'] }}
                    </div>
                    <input type="hidden" name="module" value="{{ $moduleName }}">
                </div>
                @if ($moduleName != 'standard')
                    @include($module['options_view'])
                @endif
                <div class="input-box colored">
                    <label for="active">
                        {{ __('elfcms::default.active') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="active" id="active" checked="{{ $pageData->active }}" />
                </div>
                <input type="hidden" name="id" id="id" value="{{ $pageData->id }}">
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off"
                            value="{{ $pageData->name }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('elfcms::default.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" autocomplete="off"
                            value="{{ $pageData->slug }}">
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug textid="name" slugid="slug" checked="true" />
                    </div>
                </div>
                @if ($moduleName == 'standard')
                    <div class="input-box colored">
                        <label for="title">{{ __('elfcms::default.title') }}</label>
                        <div class="input-wrapper">
                            <input type="text" name="title" id="title" autocomplete="off"
                                value="{{ $pageData->title }}">
                        </div>
                    </div>
                    <div class="input-box colored">
                        <label for="is_dynamic">
                            {{ __('elfcms::default.display_by_url') . ': ' . config('elfcms.elfcms.page_path') }}/&lt;slug&gt;
                        </label>
                        <x-elfcms::ui.checkbox.switch name="is_dynamic" id="is_dynamic"
                            checked="{{ !empty($pageData->is_dynamic) }}" />
                    </div>
                    <div class="input-box colored">
                        <label for="path">{{ __('elfcms::default.path') }}</label>
                        <div class="input-wrapper">
                            <input type="text" name="path" id="path" autocomplete="off"
                                value="{{ $pageData->path }}">
                        </div>
                    </div>
                @endif
                <div class="input-box colored">
                    <label for="image">{{ __('elfcms::default.image') }}</label>
                    <div class="input-wrapper">
                        <x-elf-input-file value="{{ $pageData->image }}" :params="['name' => 'image']" :download="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="content">{{ __('elfcms::default.content') }}</label>
                    <div class="input-wrapper">
                        <textarea name="content" id="content" cols="30" rows="10">{{ $pageData->getOriginal('content') }}</textarea>
                    </div>
                </div>
                {{-- <div class="input-box colored">
                    <label for="template">{{ __('elfcms::default.template') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="template" id="template" autocomplete="off"
                            value="{{ $pageData->template }}">
                    </div>
                </div> --}}
                @if ($moduleName == 'standard')
                    <div class="input-box colored">
                        <label for="template">{{ __('elfcms::default.template') }}</label>
                        <div class="input-wrapper">
                            <select name="template" id="template">
                                @if (empty($pageData->template))
                                    <option value="">--default--</option>
                                @elseif (!in_array($pageData->template, $templates))
                                    <option value="{{ $pageData->template }}">{{ $pageData->template }}</option>
                                @endif
                                @foreach ($templates as $template)
                                    <option value="{{ $template }}" @if ($template == $pageData->template) selected @endif>
                                        {{ $template }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="input-box colored">
                        <label for="meta_keywords">{{ __('elfcms::default.meta_keywords') }}</label>
                        <div class="input-wrapper">
                            <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3">{{ $pageData->meta_keywords }}</textarea>
                        </div>
                    </div>
                    <div class="input-box colored">
                        <label for="meta_description">{{ __('elfcms::default.meta_description') }}</label>
                        <div class="input-wrapper">
                            <textarea name="meta_description" id="meta_description" cols="30" rows="3">{{ $pageData->meta_description }}</textarea>
                        </div>
                    </div>
                @endif
            </div>
            <div class="button-box single-box">
                <button type="submit"
                    class="button color-text-button success-button">{{ __('elfcms::default.save') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.page.pages') }}"
                    class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
        checkInactive()
        //add editor
        runEditor('#content')
    </script>

@endsection
