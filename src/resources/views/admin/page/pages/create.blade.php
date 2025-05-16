@extends('elfcms::admin.layouts.main')

@section('pagecontent')
<div class="table-search-box">
    <a href="{{ route('admin.page.pages') }}" class="button round-button theme-button"
        style="color:var(--default-color);">
        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
        <span class="button-collapsed-text">
            {{ __('elfcms::default.back') }}
        </span>
    </a>
</div>
    <div class="item-form">
        <h2>{{ __('elfcms::default.create_page') }}</h2>
        <form action="{{ route('admin.page.pages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                @if (!empty($modules))
                    <div class="input-box colored" id="module-box">
                        <label for="module">{{ __('elfcms::default.module') }}</label>
                        <div class="input-wrapper">
                            <select name="module" id="module">
                                <option value="standard">{{ __('elfcms::default.standard_page') }}</option>
                                @foreach ($modules as $moduleCode => $module)
                                    <option value="{{ $moduleCode }}">{{ $module['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <script>
                        const moduleSelectBox = document.getElementById('module-box');
                        const moduleSelect = document.getElementById('module');
                        if (moduleSelect && moduleSelectBox) {
                            moduleSelect.addEventListener('change', function() {
                                const selectedModule = this.value;
                                const moduleOptionsDiv = document.getElementById('module-options');
                                if (moduleSelectBox && selectedModule) {
                                    moduleBoxes = document.querySelectorAll('[data-module]');
                                    if (moduleBoxes) {
                                        moduleBoxes.forEach(box => {
                                            if (box.dataset.module === selectedModule) {
                                                box.classList.remove('hidden');
                                            } else if (box.dataset.module != 'standard') {
                                                box.remove();
                                            } else {
                                                box.classList.add('hidden');
                                            }
                                        });
                                    }
                                    fetch(`/admin/page/module-options/${selectedModule}`)
                                        .then(response => response.text())
                                        .then(html => {
                                            moduleSelectBox.insertAdjacentHTML('afterend', html);
                                        })
                                        .catch(error => console.error('Error fetching module options:', error));
                                }
                            });
                        }
                    </script>
                @endif
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
                <div class="input-box colored"{{--  data-module="standard" --}}>
                    <label for="slug">{{ __('elfcms::default.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" autocomplete="off">
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug textid="name" slugid="slug" checked="true" />
                    </div>
                </div>
                <div class="input-box colored" data-module="standard">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored" data-module="standard">
                    <label for="is_dynamic">
                        {{ __('elfcms::default.display_by_url') . ': ' . config('elfcms.elfcms.page_path') }}/&lt;slug&gt;
                    </label>
                    <x-elfcms::ui.checkbox.switch name="is_dynamic" id="is_dynamic" />
                </div>
                <div class="input-box colored" data-module="standard">
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
                <div class="input-box colored" data-module="standard">
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
                <div class="input-box colored" data-module="standard">
                    <label for="meta_keywords">{{ __('elfcms::default.meta_keywords') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3"></textarea>
                    </div>
                </div>
                <div class="input-box colored" data-module="standard">
                    <label for="meta_description">{{ __('elfcms::default.meta_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3"></textarea>
                    </div>
                </div>
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
