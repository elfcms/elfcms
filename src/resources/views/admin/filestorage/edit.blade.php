@extends('elfcms::admin.layouts.main')

@section('pagecontent')
<div class="table-search-box">
    <a href="{{ route('admin.filestorage.index') }}" class="button round-button theme-button"
        style="color:var(--default-color);">
        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
        <span class="button-collapsed-text">
            {{ __('elfcms::default.back') }}
        </span>
    </a>
</div>

    <div class="item-form">
        <h2>{{ __('elfcms::default.edit_storage') }}</h2>
        <form action="{{ route('admin.filestorage.update', $storage->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                {{-- <div class="input-box colored">
                    <x-elfcms-input-checkbox code="active" label="{{ __('elfcms::default.active') }}" checked style="blue" />
                </div> --}}
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" value="{{ $storage->name }}">
                    </div>
                    <div class="input-wrapper">
                        <div class="icon-checkbox-round input-checker none" data-inpcheck="name" data-listen="name"></div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="code">{{ __('elfcms::default.code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="code" id="code" data-isslug
                            value="{{ $storage->code }}">
                    </div>
                    <div class="input-wrapper">
                        <div class="icon-checkbox-round input-checker none" data-inpcheck="code" data-listen="name"></div>
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug textid="name" slugid="code" checked="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="path">{{ __('elfcms::default.path') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="path" id="path" data-isslug
                            value="{{ $storage->path }}" readonly>
                    </div>
                    <div class="input-wrapper">
                        <div class="icon-checkbox-round input-checker none" data-inpcheck="path" data-listen="name"></div>
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug textid="name" slugid="path" checked="true" hidden />
                    </div>
                </div>
                <div class="input-box colored">

                    <label for="group_id">{{ __('elfcms::default.group') }}</label>
                    <div class="input-wrapper">
                        <select name="group_id" id="group_id">
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}" @if ($storage->group_id == $group->id) selected @endif data-group="{{ $group->id }}" data-code="{{ $group->code }}">
                                    {{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="input-box colored">
                    <label>{{ __('elfcms::default.types') }}</label>
                    <div class="input-wrapper wrap-wrapper">
                        @foreach ($groups as $group)
                        <div @class(['input-column', 'hidden-column' => ($group->id != $storage->group_id && $storage->group->code != 'mixed')]) data-group="{{ $group->id }}" data-code="{{ $group->code }}">
                            @foreach ($group->types as $type)
                                <div class="small-checkbox-wrapper">
                                    <div class="small-checkbox">
                                        <input type="checkbox" name="types[]" id="type_{{ $type->id }}"
                                            value="{{ $type->id }}" @if (in_array($type->id, $storage->types->pluck('id')->toArray())) checked @endif>
                                        <i></i>
                                    </div>
                                    <label for="type_{{ $type->id }}">{{ $type->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        @endforeach
                    </div>
                </div>

                <div class="input-box colored">
                    <label for="description">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="3">{{ $storage->description }}</textarea>
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="button color-text-button success-button">{{ __('elfcms::default.submit') }}</button>
                <button type="submit" name="submit" value="save_and_open"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_open') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.filestorage.index') }}"
                    class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
        autoSlug('.autoslug')
        inputSlugInit()

        const groupSelect = document.getElementById('group_id')
        if (groupSelect) {
            groupSelect.addEventListener('change', function () {
                const group = document.querySelector(`.input-column[data-group="${this.value}"]`)
                const groupCode = group.getAttribute('data-code')
                const allColumns = document.querySelectorAll('.input-column')
                allColumns.forEach(column => {
                    column.classList.add('hidden-column')
                })
                group.classList.remove('hidden-column')
                if (groupCode == 'mixed') {
                    allColumns.forEach(column => {
                        column.classList.remove('hidden-column')
                    })
                }
            })
        }

        let values = {
            name: [{!! $storages->pluck('name')->map(function ($item) { return "'".strtolower($item)."'"; })->implode(',') !!}],
            code: [{!! $storages->pluck('code')->map(function ($item) { return "'".strtolower($item)."'"; })->implode(',') !!}],
            path: [{!! $storages->pluck('path')->map(function ($item) { return "'".strtolower($item)."'"; })->implode(',') !!}],
        };
        inputCheckValue(values);
    </script>

@endsection
