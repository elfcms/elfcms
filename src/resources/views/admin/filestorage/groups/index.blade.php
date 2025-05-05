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

    <form name="groupform" class="data-table-box" method="post" action="{{ route('admin.ajax.filestorage.group.fullsave') }}">
        @csrf
        <div class="grid-table-wrapper">
            <table class="grid-table filestorage-group-table table-cols"
                style="--first-col:60px; --last-col:7.5rem; --minw:50rem; --cols-count:7;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('elfcms::default.name') }}</th>
                        <th></th>
                        <th>{{ __('elfcms::default.code') }}</th>
                        <th>{{ __('elfcms::default.description') }}</th>
                        <th>{{ __('elfcms::default.mime_prefix') }}</th>
                        {{-- <th>{{ __('infobox::default.is_filter') }}</th> --}}
                        <th>{{ __('elfcms::default.delete') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @include('elfcms::admin.filestorage.groups.content.list')
                </tbody>
            </table>
        </div>
        <div class="dynamic-table-buttons">
            <button class="button round-button theme-button" title="{{ __('elfcms::default.add_group') }}"
                data-action="additem">
                {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
                <span class="button-collapsed-text">{{ __('elfcms::default.add_group') }}</span>
            </button>
            {{-- <button class="button" title="{{__('elfcms::default.reset_button')}}" data-action="reset">{{ __('elfcms::default.reset_button') }}</button> --}}
            <button type="submit" class="button color-text-button success-button" disabled=""
                data-action="save">{{ __('elfcms::default.save') }}</button>
        </div>
    </form>
    <script>
        const inputs = document.querySelectorAll('tr[data-id] td [data-name]');
        const addButton = document.querySelector('button[data-action="additem"]');
        const saveButton = document.querySelector('button[data-action="save"]');
        const form = document.querySelector('form[name="groupform"]');
        let emptyItem;
        let unitListData;
        let controlData = {};
        let newItemId = 0;

        async function getEmptyItem() {
            let response = await fetch('{{ route('admin.ajax.filestorage.group.empty-item') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            emptyItem = await response.text();

            return emptyItem;
        }

        async function getItemsList() {
            if (unitListData !== null && typeof unitListData == 'object') {
                return unitListData;
            }
            let response = await fetch('{{ route('admin.ajax.filestorage.group.list', true) }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            unitListData = await response.json();
            return unitListData;
        }

        let startPreload = preloadSet('form[name="groupform"]');

        let dataLoadInterval = setInterval(() => {
            if (typeof unitListData === 'object') {
                for (let key in unitListData.data) {
                    let subdata = {};
                    for (let subkey in unitListData.data[key]) {
                        subdata[subkey] = unitListData.data[key][subkey];
                    }
                    controlData[key] = subdata;
                }
                clearInterval(dataLoadInterval);
                preloadUnset(startPreload);
            }
        }, 1000);

        if (inputs) {
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    //checkParamChange(this,true);
                    setDynamicSaveEnabled();
                });
                /* input.addEventListener('change',function(){
                    checkUnitChange(this);
                }); */
            });
        }

        if (addButton) {
            addButton.addEventListener('click', addFilestorageGroupItem);
        }

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

            });
        }

        if (saveButton) {
            saveButton.addEventListener('click', function(e) {
                e.preventDefault();
                popup({
                    title: '{{ __('elfcms::default.are_you_sure') }}',
                    content: '{{ __('elfcms::default.do_you_want_to_save_your_changes') }}',
                    buttons: [{
                            title: 'OK',
                            class: 'button color-text-button info-button',
                            callback: [
                                saveForm,
                                'close'
                            ]
                        },
                        {
                            title: '{{ __('elfcms::default.cancel') }}',
                            class: 'button color-text-button',
                            callback: 'close'
                        }
                    ],
                    class: 'alternate'
                });

                function saveForm() {
                    const formData = new FormData(form);
                    const itemsBox = form.querySelector('tbody');
                    let preloader = preloadSet('.big-container');
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        credentials: 'same-origin',
                        body: formData
                    }).then(
                        (result) => result.json()
                    ).then(
                        (data) => {
                            if (data.result && data.result == 'success') {
                                itemsBox.innerHTML = '';
                                itemsBox.insertAdjacentHTML('beforeend', data.data);
                                const reInputs = itemsBox.querySelectorAll('tr[data-id] td [data-name]');
                                if (reInputs) {
                                    reInputs.forEach(input => {
                                        input.addEventListener('input', function() {
                                            checkParamChange(this, true);
                                        });
                                        /* input.addEventListener('change',function(){
                                            checkUnitChange(this);
                                        }); */
                                        autoSlug('.autoslug');
                                    });
                                }
                                popup({
                                    title: '{{ __('elfcms::default.done') }}',
                                    content: data.message,
                                    buttons: [{
                                        title: 'OK',
                                        class: 'button color-text-button info-button',
                                        callback: 'close'
                                    }],
                                    class: 'alternate'
                                });
                            } else {
                                if ((data.error || (data.result && data.result == 'error'))) {
                                    if (!data.message) {
                                        data.message = '{{ __('elfcms::default.error') }}';
                                    }
                                    popup({
                                        title: '{{ __('elfcms::default.error') }}',
                                        content: data.message,
                                        buttons: [{
                                            title: 'OK',
                                            class: 'button color-text-button danger-button',
                                            callback: 'close'
                                        }],
                                        class: 'danger'
                                    });
                                }
                            }
                            preloadUnset(preloader);
                        }
                    ).catch(error => {
                        preloadUnset(preloader);
                    });
                }
            });
        }

        getItemsList();
        getEmptyItem();
        autoSlug('.autoslug');
    </script>
@endsection
