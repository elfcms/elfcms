@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.user.roles.create') }}" class="button round-button theme-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.create_new_role') }}
            </span>
        </a>
        <form action="{{ route('admin.user.roles') }}" method="get">
            <div class="round-input-wrapper">
                <button type="submit" class="button round-button theme-button inner-button default-highlight-button">
                    {!! iconHtmlLocal('elfcms/admin/images/icons/search.svg', width: 18, height: 18, svg: true) !!}
                </button>
                <input type="search" name="search" id="search" value="{{ $search ?? '' }}" placeholder="">
            </div>
        </form>
        @if (!empty($search))
            <div class="table-search-result-title">
                {{ __('elfcms::default.search_result_for') }} "{{ $search }}" <a
                    href="{{ route('admin.user.roles') }}" title="{{ __('elfcms::default.reset_search') }}">&#215;</a>
            </div>
        @endif
    </div>
    {{-- @if (Session::has('success'))
    <x-elf-notify type="success" title="{{ __('elfcms::default.success') }}" text="{{ Session::get('success') }}" />
@endif
@if ($errors->any())
    <x-elf-notify type="error" title="{{ __('elfcms::default.error') }}" text="{!! '<ul><li>' . implode('</li><li>', $errors->all()) . '</li></ul>' !!}" />
@endif --}}
    {{-- 
    <div class="table-search-box">
        <a href="{{ route('admin.user.roles.create') }}" class="button success-button icon-text-button light-icon plus-button">{{__('elfcms::default.create_new_role')}}</a>
        <div class="table-search-result-title">
            @if (!empty($search))
                {{ __('elfcms::default.search_result_for') }} "{{ $search }}" <a href="{{ route('admin.user.users') }}" title="{{ __('elfcms::default.reset_search') }}">&#215;</a>
            @endif
        </div>

    </div>
    @if (Session::has('userdeleted'))
    <div class="alert alert-alternate">{{ Session::get('userdeleted') }}</div>
    @endif
    @if (Session::has('useredited'))
    <div class="alert alert-alternate">{{ Session::get('useredited') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <h4>{{ __('elfcms::default.errors') }}</h4>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif --}}
    <div class="grid-table-wrapper">
        <table class="grid-table table-cols" style="--first-col:65px; --last-col:100px; --minw:800px;  --cols-count:7;">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.user.roles', UrlParams::addArr(['order' => 'id', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['id' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.name') }}
                        <a href="{{ route('admin.user.roles', UrlParams::addArr(['order' => 'name', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['name' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.code') }}
                        <a href="{{ route('admin.user.roles', UrlParams::addArr(['order' => 'code', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['code' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.description') }}
                        <a href="{{ route('admin.user.roles', UrlParams::addArr(['order' => 'description', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['description' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.created') }}
                        <a href="{{ route('admin.user.roles', UrlParams::addArr(['order' => 'created_at', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['created_at' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.updated') }}
                        <a href="{{ route('admin.user.roles', UrlParams::addArr(['order' => 'updated_at', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['updated_at' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr data-id="{{ $role->id }}" @if ($notedit && !in_array($role->code, $notedit)) class="editable" @endif>
                        <td>{{ $role->id }}</td>
                        <td>
                            @if ($notedit && !in_array($role->code, $notedit))
                                <a href="{{ route('admin.user.roles.edit', $role->id) }}">{{ $role->name }}</a>
                            @else
                                <span>{{ $role->name }}</span>
                            @endif
                        </td>
                        <td>{{ $role->code }}</td>
                        <td>{{ $role->description }}</td>
                        <td>{{ $role->created_at }}</td>
                        <td>{{ $role->updated_at }}</td>
                        <td class="table-button-column">
                            @if ($notedit && !in_array($role->code, $notedit))
                                <a href="{{ route('admin.user.roles.edit', $role->id) }}" class="button icon-button"
                                    title="{{ __('elfcms::default.edit') }}">
                                    {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
                                </a>
                                <form action="{{ route('admin.user.roles.destroy', $role->id) }}" method="POST"
                                    data-submit="check">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $role->id }}">
                                    <input type="hidden" name="name" value="{{ $role->name }}">
                                    <button type="submit" class="button icon-button icon-alarm-button"
                                        title="{{ __('elfcms::default.delete') }}">
                                        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/delete.svg', svg: true) !!}
                                    </button>
                                </form>
                            @endif
                            {{-- <div class="contextmenu-content-box">
                            <a href="{{ route('admin.user.users',UrlParams::addArr(['role'=>$role->id])) }}" class="contextmenu-item">{{ __('elfcms::default.show_users') }}</a>
                        @if ($notedit && !in_array($role->code, $notedit))
                            <a href="{{ route('admin.user.roles.edit',$role->id) }}" class="contextmenu-item">{{ __('elfcms::default.edit') }}</a>
                            <form action="{{ route('admin.user.roles.destroy',$role->id) }}" method="POST" data-submit="check">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $role->id }}">
                                <input type="hidden" name="name" value="{{ $role->name }}">
                                <button type="submit" class="contextmenu-item">{{ __('elfcms::default.delete') }}</button>
                            </form>
                        @endif
                        </div> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $roles->links('elfcms::admin.layouts.pagination') }}

    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')

        /* if (checkForms) {
            checkForms.forEach(form => {
                form.addEventListener('submit',function(e){
                    e.preventDefault();
                    let roleId = this.querySelector('[name="id"]').value,
                        roleName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title:'{{ __('elfcms::default.deleting_of_element') }}' + roleId,
                        content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_role') }} "' + roleName + '" (ID ' + roleId + ')?</p>',
                        buttons:[
                            {
                                title:'{{ __('elfcms::default.delete') }}',
                                class:'button color-button red-button',
                                callback: function(){
                                    self.submit()
                                }
                            },
                            {
                                title:'{{ __('elfcms::default.cancel') }}',
                                class:'button cancel-button',
                                callback:'close'
                            }
                        ],
                        class:'danger'
                    })
                })
            })
        } */

        function setConfirmDelete(forms) {
            if (forms) {
                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        let roleId = this.querySelector('[name="id"]').value,
                            roleName = this.querySelector('[name="name"]').value,
                            self = this
                        popup({
                            title: '{{ __('elfcms::default.deleting_of_element') }}' + roleId,
                            content: '<p>{{ __('elfcms::default.are_you_sure_to_deleting_role') }} "' +
                                roleName + '" (ID ' + roleId + ')?</p>',
                            buttons: [{
                                    title: '{{ __('elfcms::default.delete') }}',
                                    class: 'button color-button red-button',
                                    callback: function() {
                                        self.submit()
                                    }
                                },
                                {
                                    title: '{{ __('elfcms::default.cancel') }}',
                                    class: 'button cancel-button',
                                    callback: 'close'
                                }
                            ],
                            class: 'danger'
                        })
                    })
                })
            }
        }

        setConfirmDelete(checkForms)

        const tablerow = document.querySelectorAll('.roletable tbody tr');
        if (tablerow) {
            tablerow.forEach(row => {
                row.addEventListener('contextmenu', function(e) {
                    e.preventDefault()
                    let content = row.querySelector('.contextmenu-content-box').cloneNode(true)
                    let forms = content.querySelectorAll('form[data-submit="check"]')
                    setConfirmDelete(forms)
                    contextPopup(content, {
                        'left': e.x,
                        'top': e.y
                    })
                })
            })
        }
    </script>
@endsection
