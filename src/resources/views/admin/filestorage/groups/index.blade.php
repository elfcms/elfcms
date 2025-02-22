@extends('elfcms::admin.layouts.default')
@section('innerpage-content')
{{-- <div class="pagenav">
    <a href="{{ route('admin.filestorage.groups.edit', $infobox) }}">{{ __('infobox::default.infobox') . ' "' . $infobox->title . '"' }}</a>
</div> --}}
    @if (Session::has('action_result'))
    <div class="alert alert-alternate">{{ Session::get('action_result') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
<form name="groupform" class="data-table-box" method="post" action="{{ route('admin.ajax.filestorage.group.fullsave') }}">
    @csrf
    <div class="widetable-wrapper">
        <table class="grid-table filestorage-group-table table-cols-7" style="--first-col:60px; --last-col:100px; --minw:800px">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('elfcms::default.name') }}</th>
                    <th></th>
                    <th>{{ __('elfcms::default.code') }}</th>
                    <th>{{ __('elfcms::default.description') }}</th>
                    <th>{{ __('elfcms::default.mime_prefix') }}</th>
                    {{-- <th>{{ __('infobox::default.is_filter') }}</th> --}}
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @include('elfcms::admin.filestorage.groups.content.list')
            </tbody>
        </table>
    </div>
    <div class="dynamic-table-buttons">
        <button class="default-btn alternate-button" title="{{__('elfcms::default.add_group')}}" data-action="additem">{{ __('elfcms::default.add_group') }}</button>
        {{-- <button class="default-btn" title="{{__('elfcms::default.reset_button')}}" data-action="reset">{{ __('elfcms::default.reset_button') }}</button> --}}
        <button type="submit" class="default-btn submit-button" disabled="" data-action="save">{{ __('elfcms::default.save') }}</button>
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
            let response = await fetch('{{ route("admin.ajax.filestorage.group.empty-item") }}',{headers: {'X-Requested-With': 'XMLHttpRequest'}});
            emptyItem = await response.text();

            return emptyItem;
        }

        async function getItemsList () {
            if (unitListData !== null && typeof unitListData == 'object') {
                return unitListData;
            }
            let response = await fetch('{{ route("admin.ajax.filestorage.group.list",true) }}',{headers: {'X-Requested-With': 'XMLHttpRequest'}});
            unitListData = await response.json();
            return unitListData;
        }

        /* function showOptions(element) {
            if (typeof element === 'string') {
                element = document.querySelector(element);
            }
            if (!element || !(element instanceof HTMLSelectElement)) {
                return false;
            }
            const row = element.closest('tr[data-id="' + element.dataset.id + '"]');
            if (row) {
                const subrow = row.querySelector('.table-subrow');
                if (subrow) {
                    if (element.options[element.selectedIndex].dataset.code == 'list') {
                        subrow.classList.add('showed');
                    }
                    else {
                        subrow.classList.remove('showed');
                    }
                }
            }
        }

        function addOption(button,isnew=true) {
            if (typeof button === 'string') {
                button = document.querySelector(button);
            }
            if (!button || !(button instanceof HTMLElement)) {
                return false;
            }
            let newprop = 'new';
            if (!isnew) {
                newprop = '';
            }
            const table = button.closest('.infobox-option-table');
            //const parent = button.closest('tr[data-id]');
            if (table) {
                const box = table.querySelector('.infobox-option-table-body');
                if (box) {
                    let i = 0;
                    const rows = box.querySelectorAll('.infobox-option-table-row');
                    if (rows && rows.length && rows.length > 0) {
                        i = rows.length;
                    }
                    const rowString = `
                    <div class="infobox-option-table-row">
                        <div class="infobox-option-table-column">
                            <input type="text" name="${newprop}property[${button.dataset.id}][options][${i}][key]" value="" oninput="checkOptionChange(this)" data-loop="${i}" data-name="key">
                        </div>
                        <div class="infobox-option-table-column">
                            <input type="text" name="${newprop}property[${button.dataset.id}][options][${i}][value]" value="" oninput="checkOptionChange(this)" data-loop="${i}" data-name="value">
                        </div>
                        <div class="infobox-option-table-column">
                            <div class="checkbox-switch red">
                                <input type="checkbox" name="${newprop}property[${button.dataset.id}][options][${i}][delete]" value="1" oninput="checkOptionChange(this)" data-loop="${i}" data-name="delete">
                                <i></i>
                            </div>
                        </div>
                    </div>
                    `;
                    box.insertAdjacentHTML('beforeend',rowString);
                }
            }
        } */

        let startPreload = preloadSet('form[name="groupform"]');

        let dataLoadInterval = setInterval(() => {
            if (typeof unitListData === 'object') {
                for (let key in unitListData.data) {
                    let subdata = {};
                    for (let subkey in unitListData.data[key]){
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
                input.addEventListener('input',function(){
                    //checkParamChange(this,true);
                    setDynamicSaveEnabled();
                });
                /* input.addEventListener('change',function(){
                    checkUnitChange(this);
                }); */
            });
        }

        if (addButton) {
            addButton.addEventListener('click',addFilestorageGroupItem);
        }

        if (form) {
            form.addEventListener('submit',function(e){
                e.preventDefault();

            });
        }

        if (saveButton) {
            saveButton.addEventListener('click',function(e){
                e.preventDefault();
                popup({
                    title: '{{__("elfcms::default.are_you_sure")}}',
                    content: '{{__("elfcms::default.do_you_want_to_save_your_changes")}}',
                    buttons:[
                        {
                            title:'OK',
                            class:'default-btn alternate-button',
                            callback: [
                                saveForm,
                                'close'
                            ]
                        },
                        {
                            title:'{{__("elfcms::default.cancel")}}',
                            class:'default-btn cancel-button',
                            callback:'close'
                        }
                    ],
                    class:'alternate'
                });
                function saveForm() {
                    const formData = new FormData(form);
                    const itemsBox = form.querySelector('tbody');
                    let preloader = preloadSet('.big-container');
                    fetch(form.action,{
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        credentials: 'same-origin',
                        body: formData
                    }).then(
                        (result) => result.json()
                    ).then (
                        (data) => {
                            if (data.result && data.result == 'success') {
                                itemsBox.innerHTML = '';
                                itemsBox.insertAdjacentHTML('beforeend',data.data);
                                const reInputs = itemsBox.querySelectorAll('tr[data-id] td [data-name]');
                                if (reInputs) {
                                    reInputs.forEach(input => {
                                        input.addEventListener('input',function(){
                                            checkParamChange(this,true);
                                        });
                                        /* input.addEventListener('change',function(){
                                            checkUnitChange(this);
                                        }); */
                                        autoSlug('.autoslug');
                                    });
                                }
                                popup({
                                    title: '{{__("elfcms::default.done")}}',
                                    content: data.message,
                                    buttons:[
                                        {
                                            title:'OK',
                                            class:'default-btn alternate-button',
                                            callback:'close'
                                        }
                                    ],
                                    class:'alternate'
                                });
                            }
                            else {
                                if ((data.error || (data.result && data.result == 'error'))) {
                                    if (!data.message) {
                                        data.message = '{{ __("elfcms::default.error") }}';
                                    }
                                    popup({
                                        title:'{{__("elfcms::default.error")}}',
                                        content:data.message,
                                        buttons:[
                                            {
                                                title:'OK',
                                                class:'default-btn delete-button',
                                                callback:'close'
                                            }
                                        ],
                                        class:'danger'
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
