@extends('elfcms::admin.layouts.default')

@section('innerpage-content')

    @if (Session::has('success'))
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
    @endif

    <div class="item-form">
        <h3>{{ __('elfcms::default.create_storage') }}</h3>
        <form action="{{ route('admin.filestorage.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                {{-- <div class="input-box colored">
                    <x-elfcms-input-checkbox code="active" label="{{ __('elfcms::default.active') }}" checked style="blue" />
                </div> --}}
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" value="{{ old('name') }}">
                    </div>
                    <div class="input-wrapper">
                        <div class="icon-checkbox-round input-checker none" data-inpcheck="name" data-listen="name"></div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="code">{{ __('elfcms::default.code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="code" id="code" data-isslug
                            value="{{ old('code') }}">
                    </div>
                    <div class="input-wrapper">
                        <div class="icon-checkbox-round input-checker none" data-inpcheck="code" data-listen="name"></div>
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <input type="checkbox" data-text-id="name" data-slug-id="code" data-slug-space="_"
                                class="autoslug" checked>
                            <div class="autoslug-button"></div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="path">{{ __('elfcms::default.path') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="path" id="path" data-isslug
                            value="{{ old('code') }}" readonly>
                    </div>
                    <div class="input-wrapper">
                        <div class="icon-checkbox-round input-checker none" data-inpcheck="path" data-listen="name"></div>
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper autoslug-invisible">
                            <input type="checkbox" data-text-id="name" data-slug-id="path"
                                class="autoslug" checked>
                            <div class="autoslug-button"></div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">

                    <label for="group_id">{{ __('elfcms::default.group') }}</label>
                    <div class="input-wrapper">
                        <select name="group_id" id="group_id">
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}" @if (old('group_id') == $group->id) selected @endif data-group="{{ $group->id }}" data-code="{{ $group->code }}">
                                    {{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="input-box colored">
                    <label>{{ __('elfcms::default.types') }}</label>
                    <div class="input-wrapper wrap-wrapper">
                        @foreach ($groups as $group)
                        <div @class(['input-column']) data-group="{{ $group->id }}" data-code="{{ $group->code }}">
                            @foreach ($group->types as $type)
                                <div class="small-checkbox-wrapper">
                                    <div class="small-checkbox">
                                        <input type="checkbox" name="types[]" id="type_{{ $type->id }}"
                                            value="{{ $type->id }}">
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
                        <textarea name="description" id="description" cols="30" rows="3">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="default-btn success-button">{{ __('elfcms::default.submit') }}</button>
                <button type="submit" name="submit" value="save_and_open"
                    class="default-btn alternate-button">{{ __('elfcms::default.save_and_open') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="default-btn alternate-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.filestorage.index') }}"
                    class="default-btn">{{ __('elfcms::default.cancel') }}</a>
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
